---
title: "在 Deployment YAML 中更新应用程序镜像"
date: 2026-06-20
---

# 在 Deployment YAML 中更新应用程序镜像

在这一步中，你将学习如何更新 Kubernetes Deployment 中的容器镜像，模拟真实世界中的应用程序升级场景。

首先，确保你位于正确的目录中：

```bash
cd ~/project/k8s-manifests
```

打开现有的部署清单文件：

```bash
nano nginx-deployment.yaml
```

将镜像从 `nginx:1.23.3-alpine` 更新到新版本：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app
  labels:
    app: web
spec:
  replicas: 3
  selector:
    matchLabels:
      app: web
  template:
    metadata:
      labels:
        app: web
    spec:
      containers:
        - name: nginx
          image: nginx:1.24.0-alpine
          ports:
            - containerPort: 80
```

应用更新后的部署：

```bash
kubectl apply -f nginx-deployment.yaml
```

示例输出：

```
deployment.apps/web-app configured
```

观察部署更新过程：

```bash
kubectl rollout status deployment web-app
```

示例输出：

```
Waiting for deployment "web-app" to roll out...
Waiting for deployment spec update to be applied...
Waiting for available replicas to reach desired number...
deployment "web-app" successfully rolled out
```

验证新镜像版本：

```bash
kubectl get pods -l app=web -o jsonpath='{.items[*].spec.containers[0].image}'
```

示例输出：

```
nginx:1.24.0-alpine nginx:1.24.0-alpine nginx:1.24.0-alpine
```

镜像更新的关键点：

1. 使用 `kubectl apply` 来更新部署
2. Kubernetes 默认执行滚动更新
3. Pod 会逐步替换，以确保应用程序的可用性
4. 更新过程确保零停机部署
