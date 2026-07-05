---
title: "验证 Daemonset"
date: 2026-06-20
---

# 验证 Daemonset

验证 DaemonSet 是否已创建，并确保 `myapp-pod` 的副本在集群中的每个节点上运行。使用以下命令列出集群中的节点：

```shell
kubectl get nodes
```

使用以下命令列出由 DaemonSet 创建的 Pod：

```shell
kubectl get pods -l app=myapp
```

你应该会看到集群中每个节点对应一个 Pod。
