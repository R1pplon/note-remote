---
title: "创建 ConfigMap"
date: 2026-06-20
---

# 创建 ConfigMap

让我们为 Web 服务器创建一个配置文件。我们不会将其打包进镜像，而是将其存储在 **ConfigMap** 中。

这种模式是容器设计原则的一部分：构建一个可复用的镜像，然后在运行时提供特定环境的配置。这使得镜像更具可移植性，因为开发、测试和生产环境可以使用同一个容器，但配置却各不相同。

首先，创建一个名为 `nginx.conf` 的简单 Nginx 配置文件：

```bash
echo 'events {} http { server { listen 80; location / { return 200 "Hello from ConfigMap!"; } } }' > nginx.conf
```

现在，根据此文件创建一个名为 `nginx-config` 的 ConfigMap。这实际上是将文件「钉」在了 Kubernetes 集群的公告板上。

```bash
kubectl create configmap nginx-config --from-file=nginx.conf
```

验证它是否存在：

```bash
kubectl describe configmap nginx-config
```

你应该能在「Data」部分看到 `nginx.conf` 的内容。
