# 启动 Minikube

要在本地运行 Kubernetes，我们使用 Minikube，它会设置一个单节点的 Kubernetes 集群。在进行任何 Kubernetes 操作之前，请确保 Minikube 已启动并运行。

1. 打开终端并使用以下命令启动 Minikube：

   ```bash
   minikube start
   ```

   这将初始化集群。如果需要，你可以使用 `--cpus` 和 `--memory` 等标志指定资源限制，以确保集群有足够的资源。

2. 验证 Minikube 是否正在运行：

   ```bash
   minikube status
   ```

   你应该会看到集群及其组件正在成功运行。

启动 Minikube 可确保 Kubernetes 已准备好管理后续步骤中的部署和服务。
