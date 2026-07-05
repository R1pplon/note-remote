# 回滚 Deployment

1. 将 `my-deployment` Deployment 回滚到上一个版本：

```bash
kubectl rollout undo deployment/my-deployment
```

这将把 `my-deployment` Deployment 回滚到上一个版本。

2. 验证 Deployment 是否已回滚：

```bash
kubectl rollout status deployment/my-deployment
```

这将显示 `my-deployment` Deployment 的最新滚动更新状态。
