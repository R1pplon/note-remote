# 扩展 Deployment

1. 将 `my-deployment` Deployment 扩展到 5 个副本：

```bash
kubectl scale deployment my-deployment --replicas=5
```

这将把 `my-deployment` Deployment 的副本数量增加到 5 个。

2. 验证 Deployment 是否已扩展：

```bash
kubectl get deployments
```

这将显示集群中的 Deployments，包括具有 5 个副本的 `my-deployment` Deployment。
