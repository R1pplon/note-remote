---
title: "in_array缺陷"
date: 2025-11-12
---
# in_array 缺陷

## 描述

(PHP 4, PHP 5, PHP 7)

in_array — 检查数组中是否存在某个值

```php
in_array ( mixed $needle , array $haystack [, bool $strict = FALSE ] ) : bool
```

大海捞针，在大海（haystack）中搜索针（needle），如果没有设置 strict 则使用宽松的比较。

**参数**

- **needle**    待搜索的值。
    > 如果 needle 是字符串，则比较是区分大小写的。
- **haystack**  待搜索的数组。
- **strict**    默认为 FALSE。

如果第三个参数 **strict** 的值为 **TRUE** 则 `in_array()` 函数还会检查 **needle** 的类型是否和 **haystack** 中的相同。

返回值
如果找到 **needle** 则返回 **TRUE**，否则返回 **FALSE**。

## 漏洞点

参数 **strict** 默认为 **FALSE**
判断时先将 **needle** 转换成 **haystack** 的类型
类型转换时出现漏洞

```php
$white_list = array(1,2,3,4,5,6,7);
$shell_name = $_GET['shell_name'];
if(in_array($shell_name, $white_list)){
    echo "success";
}
else{
    echo "fail";
}
```

在以上案例中，未将第三个参数设置为 **true** ，会将 **7shell.php** 字符串强制转换成数字 **7** ,导致绕过 **in_array()** 函数判断

## 例题

### 文件上传审查

```php
class Challenge {
    Const UPLOAD_DIRECTORY = './solutions/';
    private $file;
    private $white_list;

    public function __construct($file) {
        $this->file = $file;
        $this->white_list = range(1, 24);
    }

    public function __destruct() {
        if (in_array($this->file['name'], $this->white_list)) {
            move_uploaded_file($this->file['tmp_name'], self::UPLOAD_DIRECTORY . $this->file['name']
            );
        }
    }
}

$challenge = new Challenge($_FILES['solution']);
```
