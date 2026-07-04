---
layout: post
title: "MD5、HASH相关漏洞"
date: 2024-09-29
tags: [PHP, MD5, CTF, web, SQL注入]
comments: true
author: MK-KM1542
---



## MD5弱比较

```php
<?php
if(md5($_GET['a']) == md5($_GET['b'])){
    echo $flag;
}
?>
```

输入两个值，然后对比两个值的MD5值，如果相等，则输出flag。

典型的弱类型比较，符合两个条件：

1. MD5计算后结果开头是0e    0e开头是让PHP把这段字符串认为是科学记数法字符串的先决条件
2. 0e后面全是数字。例如，0e123==0e234，0的N次方始终是0

满足以上两个条件的字符串有：

1. QNKCDZO
2. 240610708
3. s155964671a
4. s878926199a
5. s214587387a
6. s1885207154a
7. s1836677006a

md5(md5())后开头是0e的字符串：

1. CbDLytmyGm2xQyaLNhWn
2. 770hQgrBOjrcqftrlaZk
3. 7r4lGXCH2Ksu2JNT3BYM

```php
CbDLytmyGm2xQyaLNhWn

md5(CbDLytmyGm2xQyaLNhWn) => 0ec20b7c66cafbcc7d8e8481f0653d18

md5(md5(CbDLytmyGm2xQyaLNhWn)) => 0e3a5f2a80db371d4610b8f940d296af

770hQgrBOjrcqftrlaZk

md5(770hQgrBOjrcqftrlaZk) => 0e689b4f703bdc753be7e27b45cb3625

md5(md5(770hQgrBOjrcqftrlaZk)) => 0e2756da68ef740fd8f5a5c26cc45064

7r4lGXCH2Ksu2JNT3BYM

md5(7r4lGXCH2Ksu2JNT3BYM) => 0e269ab12da27d79a6626d91f34ae849

md5(md5(7r4lGXCH2Ksu2JNT3BYM)) => 0e48d320b2a97ab295f5c4694759889f
```

## MD5相同的二进制数据

注意URL编码

md5(param1) === md5(param2)

```txt
param1=%4d%c9%68%ff%0e%e3%5c%20%95%72%d4%77%7b%72%15%87%d3%6f%a7%b2%1b%dc%56%b7%4a%3d%c0%78%3e%7b%95%18%af%bf%a2%02%a8%28%4b%f3%6e%8e%4b%55%b3%5f%42%75%93%d8%49%67%6d%a0%d1%d5%5d%83%60%fb%5f%07%fe%a2
param2=%4d%c9%68%ff%0e%e3%5c%20%95%72%d4%77%7b%72%15%87%d3%6f%a7%b2%1b%dc%56%b7%4a%3d%c0%78%3e%7b%95%18%af%bf%a2%00%a8%28%4b%f3%6e%8e%4b%55%b3%5f%42%75%93%d8%49%67%6d%a0%d1%55%5d%83%60%fb%5f%07%fe%a2
```

## MD4

MD4也满足这两个条件的字符串：

1. 0e251288019

## SHA1

SHA1也满足这两个条件的字符串：

1. aa3OFF9m
2. aaO8zKZF
3. aaroZmOk
4. aaK1STfY

## MD5数组绕过

MD5不能处理数组，若有以下判断则可用数组绕过

```php
if(md5($_GET['a']) === md5($_GET['b']))
{
    echo "yes";
}
//http://127.0.0.1/1.php?a[]=1&b[]=2
```

## sql注入MD5

**ffifdyop**

```sql
select * from 'admin' where password =md5($pass,ture)
```

$pass = ffifdyop

ffifdyop
md5加密后形成'or'6XXXXXXXXX'(这里的XXXXX是一些乱码和不可见字符)
md5值为：
'or'6\xc9]\x99\xe9!r,\xf9\xedb\x1c
这里的SQL语句会变成

```sql
select * from `admin` where password=''or'6XXXXXXXXX'  
```

形成sql注入
