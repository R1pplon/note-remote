---
title: "更新 Deployment"
date: 2026-06-20
---

# 更新 Deployment

1. 编辑 `my-deployment` Deployment 以使用 `nginx:1.19` 镜像：

```bash
kubectl edit deployment my-deployment
```

这将在默认文本编辑器中打开 Deployment。将 `image` 字段更改为 `nginx:1.19` 并保存文件。

2. 验证 Deployment 是否已更新：

```bash
kubectl rollout status deployment/my-deployment
```

这将显示 `my-deployment` Deployment 的最新滚动更新状态。
