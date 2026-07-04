---
title: "ez_http"
date: 2024-09-04
---
# ez_http
发送http请求

```python
import requests

url = "http://127.0.0.1:5135/?xt=%E5%A4%A7%E5%B8%85b"

# 请求头部
headers = {
    "Host": "127.0.0.1:5135",
    "User-Agent": "MoeDedicatedBrowser",
    "Referer": "https://www.xidian.edu.cn/",
    "X-Forwarded-For": "127.0.0.1",
}

# POST请求的数据
data = {
    "imoau": "sb"
}

# 设置cookie
cookies = {
    "user": "admin"
}

# 发送POST请求
response = requests.post(url, headers=headers, data=data, cookies=cookies)

# 打印响应内容
print(response.text)
```
