---
title: "启动 Kubernetes 集群"
date: 2026-06-20
---

# 启动 Kubernetes 集群

在与 Kubernetes 资源交互之前，请确保 Kubernetes 集群正在运行。在本实验中，我们将使用 Minikube 来设置一个单节点的 Kubernetes 集群。

1. 打开终端并启动 Minikube：

   ```bash
   minikube start
   ```

   这将初始化一个本地的 Kubernetes 集群。Minikube 会自动分配适当的资源，但你可以根据需要使用 `--cpus` 和 `--memory` 等标志进行自定义。

2. 验证 Minikube 是否正在运行：

   ```bash
   kubectl cluster-info
   ```

   确保输出确认集群已正常运行。
