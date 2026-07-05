---
title: "在应用程序中使用 Configmap"
date: 2026-06-20
---

# 在应用程序中使用 Configmap

在本步骤中，你将在应用程序中使用 ConfigMap。

在 `/home/labex/project/` 目录下创建一个名为 `deployment.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: my-app
spec:
  replicas: 1
  selector:
    matchLabels:
      app: my-app
  template:
    metadata:
      labels:
        app: my-app
    spec:
      containers:
        - name: my-app
          image: nginx:latest
          env:
            - name: DATABASE_URL
              valueFrom:
                configMapKeyRef:
                  name: my-config
                  key: DATABASE_URL
```

此部署指定了一个运行应用程序的容器，该容器使用 `DATABASE_URL` 环境变量连接到 PostgreSQL 数据库。`DATABASE_URL` 的值从 `my-config` ConfigMap 中获取。

要创建部署，请运行以下命令：

```bash
kubectl apply -f deployment.yaml
```
