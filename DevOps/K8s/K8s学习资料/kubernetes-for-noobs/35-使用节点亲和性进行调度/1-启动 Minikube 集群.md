# 启动 Minikube 集群

在创建资源之前，你需要一个正在运行的 Kubernetes 集群。Minikube 是一个在本地机器上运行的轻量级 Kubernetes 环境。

1. **导航到工作目录**：

   打开终端并导航到默认的项目文件夹：

   ```bash
   cd /home/labex/project
   ```

2. **启动 Minikube**：

   启动 Minikube 以初始化 Kubernetes 集群：

   ```bash
   minikube start
   ```

   - 此命令会在你的本地机器上设置一个单节点的 Kubernetes 集群。
   - 根据系统性能，Minikube 可能需要几分钟时间启动。

3. **验证 Minikube 是否正在运行**：

   检查 Minikube 集群的状态：

   ```bash
   minikube status
   ```

   - 查看 `kubelet` 和 `apiserver` 等组件是否显示为 `Running`。
   - 如果集群未运行，请重新执行 `minikube start`。

如果启动 Minikube 时遇到问题，必要时可以使用 `minikube delete` 重置环境。

请在 `/home/labex/project` 目录下执行本实验的后续步骤，以确保 YAML 文件保持在同一个工作目录中。
