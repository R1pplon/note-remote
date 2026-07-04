x = 2949512038986137685036398826630440282631884919219498674716380193882113
from Crypto.Util.number import long_to_bytes
for i in range(9999999999999999999999999999999999):
    x=x-i
    if b'ctf{' in long_to_bytes(x) :
        print(long_to_bytes(x))