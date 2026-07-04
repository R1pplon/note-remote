---
title: "php绕过"
date: 2026-07-02
---
# php绕过

## 科学计数法绕过

```php
money=100000000

Nember lenth is too long

money=1e9
```

## 数组绕过

```php
int strcmp ( string $str1 , string $str2 )
```

参数 str1第一个字符串。str2第二个字符串。
如果 str1 小于 str2     返回 < 0
如果 str1 大于 str2     返回 > 0
如果两者相等，          返回 0

```php
define('FLAG','pwnhub{this_is_flag}');
if(strcmp($_GET['flag'],FLAG) == 0){
     echo "success,flag:".FLAG;
}
```

strcmp比较的是字符串类型，如果强行传入其他类型参数，会出错，出错后返回值0，正是利用这点进行绕过。
传入数组flag[]

```php
flag[]=xxx
```

5.3之前的php
php官方在后面的版本中修复了这个漏洞，使得报错的时候函数不返回任何值
