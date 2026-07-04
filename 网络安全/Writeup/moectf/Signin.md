---
title: "Signin"
date: 2024-08-31
---
# Signin
```python
from Crypto.Util.number import*
from secret import flag


m = bytes_to_long(flag)
p = getPrime(1024)
q = getPrime(1024)
n = p*q
e = 65537
c = pow(m,e,n)
pq = (p-1)*(q-2)
qp = (q-1)*(p-2)
p_q = p + q

print(f"{c = }")
print(f"{pq = }")
print(f"{qp = }")
print(f"{n = }")
print(f"{p_q = }")
'''
c = 
pq = 
qp = 
n = 
p_q = 
'''
```
小学数学
已知
* p + q
* (p-1)*(q-2)
* (q-1)*(p-2)

可得p,q

```python
# p-q
差 = pq-qp
# p+q
和 = p_q
p = (差 + 和)//2
q = 和 - p
e = 65537
from gmpy2 import *
from Crypto.Util.number import *
phi = (p - 1) * (q - 1)
d = invert(e, phi)
M = pow(c, d, n)
print(long_to_bytes(M))
```
