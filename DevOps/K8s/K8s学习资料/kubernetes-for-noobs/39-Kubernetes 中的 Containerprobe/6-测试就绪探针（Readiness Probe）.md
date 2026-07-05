# 测试就绪探针（Readiness Probe）

现在我们已经添加了就绪探针，可以测试它是否正常工作。

1. 获取 Pod 名称：

```bash
kubectl get pods -l app=containerprobe -o jsonpath='{.items[0].metadata.name}'
```

此命令获取由 Deployment 创建的 Pod 的名称。

2. 获取就绪探针的状态：

```bash
kubectl describe pod <pod-name>
```

将 `<pod-name>` 替换为上一步中获取的 Pod 名称。

你应该会看到包含以下内容的输出：

```
Readiness: http-get http://:80/ delay=0s timeout=1s period=10s #success=1 #failure=3
```

这表明就绪探针已正确配置。
