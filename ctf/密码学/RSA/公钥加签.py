import rsa
import gmpy2
e = 65537
n = 86934482296048119190666062003494800588905656017203025617216654058378322103517
p = 285960468890451637935629440372639283459
q = 304008741604601924494328155975272418463
phi = (p-1)*(q-1)
d = gmpy2.invert(e, phi)

key = rsa.PrivateKey(n, e, d, q, p)  # 在pkcs标准中,pkcs#1规定,私钥包含(n,e,d,p,q)

with open("D:\\Downloads\\41c4e672-98c5-43e5-adf4-49d75db307e4\\output\\flagenc.txt", "rb") as f:  # 以二进制读模式，读取密文
    f = f.read()
    print(rsa.decrypt(f, key))  # f:公钥加密结果  key:私钥

