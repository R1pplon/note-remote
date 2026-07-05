---
title: "创建 Deployment"
date: 2026-06-20
---

# 创建 Deployment

第一步是在 Kubernetes 中创建一个 Deployment。我们将使用这个 Deployment 来测试 ContainerProbe。

1. 在 `/home/labex/project` 目录下创建一个名为 `deployment.yaml` 的新文件。
2. 将以下代码复制并粘贴到文件中：

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
```

此代码创建了一个具有一个副本的 Deployment，一个带有标签 `app: containerprobe` 的选择器，以及一个运行 nginx 镜像的容器。

3. 将 Deployment 应用到你的集群中：

```bash
kubectl apply -f deployment.yaml
```
