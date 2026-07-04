---
title: "电院_Backend"
date: 2026-07-02
---
# 电院_Backend

题目提供了**login.zip**,打开是**login.php**文件

```php
<?php
error_reporting(0);
session_start();

if($_POST){
    $verify_code = $_POST['verify_code'];

    // 验证验证码
    if (empty($verify_code) || $verify_code !== $_SESSION['captcha_code']) {
        echo json_encode(array('status' => 0,'info' => '验证码错误啦，再输入吧'));
        unset($_SESSION['captcha_code']);
        exit;
    }

    $email = $_POST['email'];
    if(!preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\\.[a-zA-Z0-9]+/", $email)||preg_match("/or/i", $email)){
        echo json_encode(array('status' => 0,'info' => '不存在邮箱为： '.$email.' 的管理员账号！'));
        unset($_SESSION['captcha_code']);
        exit;
    }

    $pwd = $_POST['pwd'];
    $pwd = md5($pwd);
    $conn = mysqli_connect("localhost","root","123456","xdsec",3306);


    $sql = "SELECT * FROM admin WHERE email='$email' AND pwd='$pwd'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result);

    if($row){
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_email'] = $row['email'];
        echo json_encode(array('status' => 1,'info' => '登陆成功，moectf{testflag}'));
    } else{
        echo json_encode(array('status' => 0,'info' => '管理员邮箱或密码错误'));
        unset($_SESSION['captcha_code']);
    }
}


?>
```

分析代码，可以看到，首先会验证验证码，然后会验证邮箱的格式是否正确，然后会将邮箱和密码进行查询
查询通过，得到flag
题目直接给出了sql查询语句，很可能是在考察***sql注入***

打开网页只显示***where is flag?***
访问`/robots.txt`

```http
User-agent: *
Disallow: /admin/
```

访问`/admin/`，发现后台登录页面
需要输入**邮箱、密码、验证码**

验证码没想到绕过的方法
手动进行sql注入
分析sql查询语句

```sql
SELECT * FROM admin WHERE email='$email' AND pwd='$pwd'
```

查询email和pwd
email在前，pwd在后

对email进行注入，可以用注释符号将后面的pwd注释掉
如果对pwd进行注入，则无法保证email查询结果正确
**所以选择注入email**

邮箱的格式

```php
if(!preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\\.[a-zA-Z0-9]+/", $email)||preg_match("/or/i", $email))
```

**对`or`进行过滤，用`||`替代**

密码随意，验证码别输错了

构造payload

```
邮箱：
abc@abc.com'|| 1=1#

密码：
123456

验证码：
****

```

flag弹窗很快，F12建议打开网络记录查询响应
