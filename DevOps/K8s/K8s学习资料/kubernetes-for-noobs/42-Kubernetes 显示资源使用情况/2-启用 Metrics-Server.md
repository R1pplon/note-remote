# 启用 Metrics-Server

Metrics-Server 是 Kubernetes 的一个组件，它从各种 Kubernetes 对象中收集指标，并以可消费的格式提供给其他 Kubernetes 组件。在我们能够显示 Kubernetes 集群中的资源使用情况之前，需要先启用 metrics-server。

```bash
minikube addons enable metrics-server
```

此命令将在你的 Kubernetes 集群中启用 metrics-server。

执行以下命令以检查 metrics-server 是否正在运行：

```bash
kubectl get pods --namespace=kube-system | grep metrics-server
```
