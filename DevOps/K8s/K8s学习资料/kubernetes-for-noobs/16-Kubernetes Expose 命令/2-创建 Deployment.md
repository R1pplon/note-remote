---
title: "创建 Deployment"
date: 2026-06-20
---

# 创建 Deployment

在 Kubernetes 中，Deployment 是一种资源对象，用于确保你的应用程序始终运行所需数量的副本。Deployment 管理 Pod 并帮助维护它们在集群中的状态。在此步骤中，你将创建一个运行 Nginx Web 服务器的 Deployment。

1. 使用以下命令创建一个名为 `hello-world` 的 Deployment，并使用 `nginx` 镜像：

   ```bash
   kubectl create deployment hello-world --image=nginx
   ```

   此命令会创建一个运行 Nginx Web 服务器的单 Pod 的 Deployment。`--image` 标志指定了要使用的容器镜像。由于未显式设置副本数量，Kubernetes 默认会创建一个 Pod。

2. 通过运行以下命令检查 Deployment 是否成功创建：

   ```bash
   kubectl get deployments
   ```

   此命令会列出当前命名空间中的所有 Deployment，显示它们的名称、所需副本数量和状态。

通过创建此 Deployment，你可以确保 Kubernetes 自动处理 Pod 的创建和重启，以维持所需的应用程序状态。
