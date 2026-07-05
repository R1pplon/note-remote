---
title: "创建一个简单的 Deployment"
date: 2026-06-20
---

# 创建一个简单的 Deployment

在这一步中，我们将创建一个包含单个 Pod 的简单 Deployment。

1. 创建一个名为 `simple-deployment.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: simple-deployment
spec:
  replicas: 1
  selector:
    matchLabels:
      app: simple-app
  template:
    metadata:
      labels:
        app: simple-app
    spec:
      containers:
        - name: simple-container
          image: nginx:latest
```

2. 使用 `kubectl` 创建 Deployment：

```bash
kubectl apply -f simple-deployment.yaml
```

3. 验证 Deployment 是否已创建：

```bash
kubectl get deployments
```
