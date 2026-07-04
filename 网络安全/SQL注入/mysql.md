---
title: "mysql"
date: 2024-08-02
---
---
`phpstudy`已经安装了`mysql`     
可以直接使用,不需要再安装了        
`设置`->`文件位置`->`mysql`->`添加环境变量***\phpstudy_pro\Extensions\MySQL5.7.26\bin`

```batch
PS D:\Desktop> mysql -u root -p
Enter password: ****
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 4
Server version: 5.7.26 MySQL Community Server (GPL)

Copyright (c) 2000, 2019, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> SHOW DATABASES;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| dvwa               |
| mysql              |
| performance_schema |
| pikachu            |
| pkxss              |
| root               |
| sys                |
+--------------------+
8 rows in set (0.00 sec)

mysql>
```
