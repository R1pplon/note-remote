---
title: "ez_hash"
date: 2024-08-31
---
# ez_hash
```python
from hashlib import sha256
from secret import flag, secrets

assert flag == b'moectf{' + secrets + b'}'
assert secrets[:4] == b'2100' and len(secrets) == 10
hash_value = sha256(secrets).hexdigest()
print(f"{hash_value = }")
secrets = 2100360168
```
已知
* `hash_value`
* `secrets`前4位为`2100`
* `secrets`长度为10

爆破`secrets`
```python
from hashlib import sha256

# 已知的SHA-256散列值
known_hash = 

# 爆破函数
def brute_force_secrets(known_hash):
    for i in range(1000000):  # 6位数字的范围是000000到999999
        # 构造可能的secrets值
        candidate = b'2100' + str(i).zfill(6).encode()
        # 计算散列值
        hash_value = sha256(candidate).hexdigest()
        # 检查散列值是否匹配
        if hash_value == known_hash:
            return candidate
    return None

# 执行爆破
secrets = brute_force_secrets(known_hash)
if secrets:
    print(f"Found secrets: {secrets}")
else:
    print("No matching secrets found.")
```
