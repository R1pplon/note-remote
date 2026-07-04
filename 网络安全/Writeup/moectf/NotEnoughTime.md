---
title: "NotEnoughTime"
date: 2024-09-14
---
# NotEnoughTime

做数学题加减乘除

直接上代码

```python
from pwn import *
import re

conn = remote('127.0.0.1', 1322)

try:
    while True:
        # data_str储存计算表达式，每次初始化为空字符串
        data_str = ''

        # 接受数据直到遇到“=”
        data_str_tmp = conn.recvuntil(' = ').decode('utf-8')

        # 打印服务器发送的数据
        print("Received data:" + data_str_tmp)

        # 以'\n'分隔
        parts = data_str_tmp.split('\n')

        # 判断带+-*/的是计算式
        for part in parts:
            if bool(re.search(r"[\+\-\*\/]", part)):
                # 计算式合并到data_str
                data_str += part

        # 输出查看计算表达式合并是否正确
        print("合并计算表达式： " + data_str)

        # 以' = '分隔，[0]的位置就是要执行的计算表达式
        expression = data_str.split(' = ')[0]

        # 将所有的除法操作替换为整数除法
        expression = expression.replace('/', '//')
        result = eval(expression)
        print(f"Calculated result: " + str(result))
        conn.sendline(str(result))

except EOFError:
    print("Server closed the connection.")

# 尝试接收剩余的数据，flag在剩余数据里
remaining_data = conn.recvall().decode('utf-8')
print(remaining_data)

```
