
import gmpy2

q = 1325465431
p = 152317153
 
e = 65537

d = gmpy2.invert(e, (p - 1) * (q - 1))
print(d)