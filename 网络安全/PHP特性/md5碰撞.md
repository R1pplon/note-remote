---
title: "md5碰撞"
date: 2024-11-04
---
# md5碰撞

```php
<?php
function  readmyfile($path){
    $fh = fopen($path, "rb");
    $data = fread($fh, filesize($path));
    fclose($fh);
    return $data;
}
echo '二进制md5加密 '. md5( (readmyfile("1.txt"))).PHP_EOL;
echo  urlencode(readmyfile("1.txt")).PHP_EOL;
echo '二进制md5加密 '.md5( (readmyfile("2.txt"))).PHP_EOL;
echo  urlencode(readmyfile("2.txt")).PHP_EOL;
```

fastcoll

生成1.txt,内容为test，用fastcoll打开，输出两个文件，他们md5相同，开头为test
