---
title: "delete注入"
date: 2024-07-24
---
# delete 注入

一个留言板，允许用户留言，并可以删除留言。
![](https://i-blog.csdnimg.cn/blog_migrate/62a675aef7e9b3868de0d4a28322fb2b.png)
进行删除操作后，查看网络历史记录，可以看到请求为 get 请求，并带有参数**id**  
发送到**Repeater**模块进行下一步操作  
浏览器 F12 也能查看
![](https://i-blog.csdnimg.cn/blog_migrate/71429eadc913fb60c03099d8b2e79461.png)
**id 的值会直接拼入后台的 SQL 语句，导致 SQL 注入。**

1. 传入的是数值，所以没有引号闭合
2. 因为不是查询操作，所以不用`select`和`union`
3. **用函数报错来进行信息获取**

```sql
# payload
1 or updatexml(1,concat(0x7e database()),0)
```

将 payload 放在注入点 id 中，注意是 **get 请求**，**需要进行 url 编码**

- 浏览器输入框里不能自动编码
  - burpsuite 可以编码,选中`1 or updatexml(1,concat(0x7e database()),0)`,**快捷键`Ctrl+U`**，
  - 或者**右键选择`转换选中内容`->`URL`->`URL-encode key characters`**
  - 手动编码(好像只是把`空格`变成`+`号 🤔)
  ```sql
  # payload:
  ?id=1+or+updatexml(1,concat(0x7e,database()),0)
  # 返回结果：
  XPATH syntax error: '~root'
  ```
- hackbar 可以自动编码
  ![](https://i-blog.csdnimg.cn/blog_migrate/b86333bd4f0ade4aae80cf676691f690.png)
  接下来只需要替换`database()`位置的命令就能得到我们想要的数据了 🤗
