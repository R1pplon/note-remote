# 启动 Kubernetes 集群

Kubernetes 集群需要一个运行环境。在本实验中，我们将使用 **Minikube**，这是一个允许你在本地运行 Kubernetes 集群的工具。请按照以下步骤操作：

1. 打开终端并确保你位于 `/home/labex/project` 目录下：

   ```bash
   cd ~/project
   ```

2. 启动 Minikube 集群：

   ```bash
   minikube start
   ```

   - Minikube 会创建一个单节点的 Kubernetes 集群。
   - 此过程可能需要几分钟，具体取决于你的系统。在初始化过程中，将设置各种 Kubernetes 组件。

   ![Minikube 集群初始化](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-display-cluster-info/zh/../assets/screenshot-20241205-ukSju1L0@2x.png)
