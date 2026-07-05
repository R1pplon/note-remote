---
title: "启动 Kubernetes 集群"
date: 2026-06-20
---

# 启动 Kubernetes 集群

在这一步中，你将学习如何使用 Minikube 启动并验证一个本地 Kubernetes 集群。这是设置 Kubernetes 开发环境的关键第一步。

首先，确保你位于项目目录中：

```bash
cd ~/project
```

启动 Minikube 集群：

```bash
minikube start
```

示例输出：

```
😄  minikube v1.29.0 on Ubuntu 22.04
✨  Automatically selected the docker driver
📌  Using Docker driver with root permissions
🔥  Creating kubernetes in kubernetes cluster
🔄  Restarting existing kubernetes cluster
🐳  Preparing Kubernetes v1.26.1 on Docker 20.10.23 ...
🚀  Launching Kubernetes ...
🌟  Enabling addons: storage-provisioner, default-storageclass
🏄  Done! kubectl is now configured to use "minikube" cluster and "default" namespace
```

验证集群状态：

```bash
minikube status
```

示例输出：

```
minikube
type: Control Plane
host: Running
kubelet: Running
apiserver: Running
kubeconfig: Configured
```

检查集群节点：

```bash
kubectl get nodes
```

示例输出：

```
NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   1m    v1.26.1
```

本步骤的关键点：

1. `minikube start` 创建一个本地单节点 Kubernetes 集群
2. 集群默认使用 Docker 作为驱动
3. Kubernetes v1.26.1 会自动配置
4. `minikube status` 和 `kubectl get nodes` 用于确认集群的准备状态
