from Crypto.PublicKey import RSA
from Crypto.Util.number import *

f1 = open("publickey1.pem", "rb").read()
f2 = open("publickey2.pem", "rb").read()
c1 = open("cipher1.txt", "rb").read()
c2 = open("cipher2.txt", "rb").read()
pub1 = RSA.importKey(f1)
pub2 = RSA.importKey(f2)
n1 = pub1.n
e1 = pub1.e
n2 = pub2.n
e2 = pub2.e
c1 = bytes_to_long(c1)
c2 = bytes_to_long(c2)
print("n1 =", n1)
print("e1 =", e1)
print("c1 =", c1)
print("n2 =", n2)
print("e2 =", e2)
print("c2 =", c2)
