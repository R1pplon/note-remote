---
title: "创建 Deployment YAML"
date: 2026-06-20
---

# 创建 Deployment YAML

在这一步中，你将为你的管理器编写工作说明书。这是一个 YAML 文件，定义了要运行的镜像以及你想要的副本数量。

请注意，与普通的 Pod 清单相比，它多了一些结构。Deployment 包含一个 **selector**（选择器）和一个 **template**（模板）。选择器告诉 Kubernetes 哪些 Pod 属于此 Deployment，而模板描述了应该如何创建新的 Pod。这两部分必须保持一致，否则 Deployment 将无法正确管理预期的 Pod。

首先，确认你位于项目目录中：

```bash
cd ~/project
```

使用 `nano` 创建一个名为 `deployment.yaml` 的新文件：

```bash
nano deployment.yaml
```

将以下配置粘贴到文件中。请注意 `replicas: 3` 这一行——这是你定义团队人数的地方。

```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx-deployment
  labels:
    app: nginx
spec:
  replicas: 3
  selector:
    matchLabels:
      app: nginx
  template:
    metadata:
      labels:
        app: nginx
    spec:
      containers:
        - name: nginx
          image: nginx:1.14.2
          ports:
            - containerPort: 80
```

保存并退出：按 `Ctrl+X`，然后按 `Y`，最后按 `Enter`。

该文件告诉 Kubernetes：“创建一个名为 `nginx-deployment` 的 Deployment。确保始终有 **3** 个运行 `nginx:1.14.2` 镜像的 Pod。如果需要查找这些 Pod，请寻找标签为 `app: nginx` 的 Pod。”
