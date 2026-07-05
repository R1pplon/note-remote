---
title: "以 JSON 格式显示服务器版本信息"
date: 2026-06-20
---

# 以 JSON 格式显示服务器版本信息

Kubernetes 支持以结构化格式（如 JSON）输出信息，这种格式广泛用于自动化以及与其他工具的集成。要以 JSON 格式显示服务器版本信息，请运行以下命令：

```bash
kubectl version --output=json
```

此命令将检索客户端和服务器的版本信息，并将其格式化为 JSON 对象。JSON 输出是机器可读的，可以被脚本或外部应用程序解析。

以下是 JSON 输出的示例：

```json
{
  "clientVersion": {
    "major": "1",
    "minor": "26",
    "gitVersion": "v1.26.0",
    ...
  },
  "serverVersion": {
    "major": "1",
    "minor": "26",
    "gitVersion": "v1.26.0",
    ...
  }
}
```
