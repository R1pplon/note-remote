---
title: "添加就绪探针（Readiness Probe）"
date: 2026-06-20
---

# 添加就绪探针（Readiness Probe）

下一步是为 nginx 容器添加一个就绪探针（readiness probe）。就绪探针用于确定容器是否准备好接收流量。如果探针失败，Kubernetes 将不会向容器发送流量。

1. 在 `deployment.yaml` 文件的容器定义中添加以下代码：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: containerprobe-deployment
spec:
  replicas: 1
  selector:
    matchLabels:
      app: containerprobe
  template:
    metadata:
      labels:
        app: containerprobe
    spec:
      containers:
        - name: containerprobe
          image: nginx
          ports:
            - containerPort: 80
          livenessProbe:
            httpGet:
              path: /
              port: 80
          readinessProbe:
            httpGet:
              path: /
              port: 80
```

此代码指定就绪探针应向端口 80 的根路径发送 HTTP GET 请求。

2. 更新 Deployment：

```bash
kubectl apply -f deployment.yaml
```
