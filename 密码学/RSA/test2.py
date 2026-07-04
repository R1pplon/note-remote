k = 0x2227e398fc6ffcf5159863a345df85ba50d6845f8c06747769fee78f598e7cb1bcf875fb9e5a69ddd39da950f21cb49581c3487c29b7c61da0f584c32ea21ce1edda7f09a6e4c3ae3b4c8c12002bb2dfd0951037d3773a216e209900e51c7d78a0066aa9a387b068acbd4fb3168e915f306ba40

k_str = hex(k)  # 使用 hex() 函数将整数转换为16进制字符串
k10 = int(k_str, 16)  # 现在可以安全地使用 int() 转换为十进制数
print(k10)
