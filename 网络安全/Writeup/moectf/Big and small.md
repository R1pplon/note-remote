---
title: "Big and small"
date: 2026-07-02
---
# Big and small
解密：

```python
from gmpy2.gmpy2 import iroot
from Crypto.Util.number import *
n = 
e = 
c = 
for i in range(100000):
	t = iroot(i * n + c, e)	
	if t[1] == 1:
		print(long_to_bytes(t[0]))
		break
```
