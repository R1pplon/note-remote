# 测试更新后的 Service

第五步是从另一个 Pod 访问更新后的 Service，以对其进行测试。使用以下命令创建一个新的测试 Pod：

```bash
kubectl run my-pod-2 --image=busybox --restart=Never -- sleep 3600
```

这将创建一个名为 `my-pod-2` 的新 Pod，其中包含一个运行 Busybox 镜像的容器。

进入容器并使用 `curl` 访问 Service，就像你在步骤 3 中所做的那样。运行以下命令进入容器：

```bash
kubectl exec -it my-pod-2 -- sh
```

这一次，你应该会收到一个错误，表明连接被拒绝。

这是因为 Service 现在指向的是与测试 Pod 运行的 Pod 不同的 Pod 集。要解决这个问题，你可以更新 Pod 的标签，使其与 Service 中的新选择器匹配。

运行以下命令更新测试 Pod 的标签：

```bash
kubectl label pod my-pod-2 app=busybox
```

这将为测试 Pod 添加标签 `app=busybox`。

现在，如果你再次运行 `curl` 命令，你应该会得到默认的 Nginx 页面，这表明 Service 正在正常工作。
