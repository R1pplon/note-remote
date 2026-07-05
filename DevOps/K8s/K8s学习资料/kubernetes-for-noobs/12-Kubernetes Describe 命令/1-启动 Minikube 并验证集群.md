# 启动 Minikube 并验证集群

在使用 Kubernetes 之前，你需要一个正在运行的集群。Minikube 提供了一个轻量级的本地 Kubernetes 集群。

1. **导航到你的项目目录**：

   打开终端并导航到默认的工作目录：

   ```bash
   cd /home/labex/project
   ```

2. **启动 Minikube**：

   启动 Minikube 以初始化集群：

   ```bash
   minikube start
   ```

   - Minikube 会创建一个单节点的 Kubernetes 集群。此步骤可能需要几分钟时间。

3. **验证 Minikube 状态**：

   检查 Minikube 是否成功启动：

   ```bash
   minikube status
   ```

   查找 `apiserver` 和 `kubelet` 等组件是否显示为 `Running`。

4. **确认 Kubernetes 配置**：

   确保 `kubectl` 已连接到 Minikube 集群：

   ```bash
   kubectl cluster-info
   ```

   这将显示 API 服务器和其他组件的详细信息。

如果 Minikube 启动失败，请使用 `minikube delete` 重置并重试。
