---
title: "启动 Kubernetes 集群"
date: 2026-06-20
---

# 启动 Kubernetes 集群

在这个挑战中，你将使用必要的 `kubectl` 命令来验证本地 Kubernetes 集群的状态，以确保它正在正确运行并可供使用。

## 任务

- 运行 Kubernetes 集群（免费用户可以跳过此任务，因为集群已预先启动）
- 验证 Kubernetes 集群正在运行
- 检查集群信息

## 要求

- 使用 `kubectl` 命令来运行和检查集群状态
- 确保你在 `~/project` 目录下工作
- 使用默认的 Minikube 集群进行验证

## 示例

集群信息输出示例：

```
Kubernetes control plane is running at https://192.168.49.2:8443
CoreDNS is running at https://192.168.49.2:8443/api/v1/namespaces/kube-system/services/kube-dns:dns/proxy
```

节点状态输出示例：

```
NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   77s   v1.26.1
```

## 提示

- 记住使用 `kubectl` 命令与 Kubernetes 集群进行交互
- 使用不同的命令（如 `cluster-info` 和 `get nodes`）来检查集群状态
- 如果遇到任何问题，请确保 Minikube 已正确启动
