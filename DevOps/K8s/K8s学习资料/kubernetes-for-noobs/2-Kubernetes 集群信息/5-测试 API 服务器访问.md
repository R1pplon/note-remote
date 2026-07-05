---
title: "测试 API 服务器访问"
date: 2026-06-20
---

# 测试 API 服务器访问

API 服务器是 Kubernetes 控制平面的关键组件。你可以通过运行以下命令测试其可访问性：

```bash
curl $(kubectl config view --minify -o jsonpath='{.clusters[0].cluster.server}') --insecure
```

- 该命令获取 API 服务器的 URL 并对其执行简单的请求。
- `--insecure` 标志绕过 SSL 证书验证，在此本地设置中是可以接受的。

**示例输出：**

如果 API 服务器可访问，响应应如下所示：

```json
{
  "kind": "Status",
  "apiVersion": "v1",
  "metadata": {},
  "status": "Failure",
  "message": "forbidden: User \"system:anonymous\" cannot get path \"/\"",
  "reason": "Forbidden",
  "details": {},
  "code": 403
}
```

此响应确认 API 服务器正在运行，但由于缺少身份验证而拒绝访问，这是预期的结果。
