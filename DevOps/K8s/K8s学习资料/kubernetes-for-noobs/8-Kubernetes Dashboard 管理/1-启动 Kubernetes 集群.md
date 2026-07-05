# 启动 Kubernetes 集群

在部署 Kubernetes Dashboard 之前，请确保你的集群正在运行。本实验将使用 **Minikube**。

1. 打开终端并导航到项目目录：

   ```bash
   cd ~/project
   ```

2. 启动 Minikube 集群：

   ```bash
   minikube start
   ```

   Minikube 会创建一个本地 Kubernetes 集群，便于测试 Dashboard 等功能。此过程可能需要几分钟。

3. 通过检查状态来验证 Minikube 是否正在运行：

   ```bash
   minikube status
   ```

   如果集群未运行，请使用 `minikube delete` 删除集群，然后重新启动 `minikube start`。
