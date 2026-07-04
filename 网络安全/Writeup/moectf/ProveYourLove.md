---
title: "ProveYourLove"
date: 2024-08-31
---
# ProveYourLove
抓包重发300次
```python
# 发送POST请求300次
for _ in range(299):
    response = requests.post(url, headers=headers, data=data_json)
    # 打印响应状态码和文本
    print(f"Response status code: {response.status_code}")
    print(response.text)
```
得到两个flag    
第一个是`ProveYourLove`的，第二个是`七夕限定`的
