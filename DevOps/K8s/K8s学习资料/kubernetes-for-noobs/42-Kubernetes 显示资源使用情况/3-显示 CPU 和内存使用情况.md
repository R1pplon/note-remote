---
title: "显示 CPU 和内存使用情况"
date: 2026-06-20
---

# 显示 CPU 和内存使用情况

要显示 Kubernetes 集群中的 CPU 和内存使用情况，我们将使用 `kubectl top` 命令。该命令允许你实时查看 Kubernetes 对象的资源使用情况。

```bash
# 显示特定命名空间中所有 Pod 的 CPU 和内存使用情况
kubectl top pods --namespace=kube-system

# 显示集群中所有节点的 CPU 和内存使用情况
kubectl top nodes
```

此命令将显示指定命名空间中所有 Pod 或集群中所有节点的当前 CPU 和内存使用统计信息。
