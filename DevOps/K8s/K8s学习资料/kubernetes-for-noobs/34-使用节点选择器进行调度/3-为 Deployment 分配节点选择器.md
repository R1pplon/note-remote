---
title: "为 Deployment 分配节点选择器"
date: 2026-06-20
---

# 为 Deployment 分配节点选择器

在这一步中，我们将为在第一步中创建的 Deployment 分配一个节点选择器（Node Selector）。

1. 为节点添加标签：

```bash
kubectl label nodes minikube disk=ssd
```

2. 编辑 `node-selector-deployment.yaml` 文件，并在 `spec.template.spec` 部分下添加 `nodeSelector` 字段：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: selector-deployment
spec:
  replicas: 1
  selector:
    matchLabels:
      app: selector-app
  template:
    metadata:
      labels:
        app: selector-app
    spec:
      nodeSelector:
        disk: ssd
      containers:
        - name: selector-container
          image: nginx:latest
```

3. 使用 `kubectl` 应用更改：

```bash
kubectl apply -f node-selector-deployment.yaml
```

4. 验证 Pod 是否已调度到带有 `disk=ssd` 标签的节点上：

```bash
kubectl get pods -o wide | grep selector-deployment
```
