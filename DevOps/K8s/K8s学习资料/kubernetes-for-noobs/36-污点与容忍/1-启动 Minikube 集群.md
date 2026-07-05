---
title: "启动 Minikube 集群"
date: 2026-06-20
---

# 启动 Minikube 集群

在创建资源之前，你需要一个正在运行的 Kubernetes 集群。Minikube 是一个轻量级的 Kubernetes 环境，可以在你的本地机器上运行。

1. **导航到工作目录**：

   打开终端并导航到默认的项目文件夹：

   ```bash
   cd /home/labex/project
   ```

2. **启动 Minikube**：

   启动 Minikube 以初始化一个 Kubernetes 集群：

   ```bash
   minikube start
   ```

   - 此命令将在你的本地机器上设置一个单节点的 Kubernetes 集群。
   - 根据系统性能，Minikube 可能需要几分钟才能启动。

3. **验证 Minikube 是否正在运行**：

   检查 Minikube 集群的状态：

   ```bash
   minikube status
   ```

   - 查找 `kubelet` 和 `apiserver` 等组件是否显示为 `Running`。
   - 如果集群未运行，请重新运行 `minikube start`。

如果启动 Minikube 时遇到问题，可以使用 `minikube delete` 重置环境（如有需要）。
