# 启动 Kubernetes 集群

在运行任何 Kubernetes 命令之前，你需要一个正在运行的集群。在本实验中，我们将使用 Minikube 来设置一个本地 Kubernetes 集群。

首先，打开终端并确保你位于 `/home/labex/project` 目录下：

```bash
cd ~/project
```

接下来，使用以下命令启动 Minikube 集群：

```bash
minikube start
```

此命令将初始化一个单节点的 Kubernetes 集群。Minikube 提供了一种简单的方法来在本地测试 Kubernetes 命令，而无需远程集群。该过程可能需要几分钟，因为 Minikube 会设置集群并安装所需的组件。

为了确保集群正在运行，你可以通过运行以下命令检查其状态：

```bash
minikube status
```

你应该会看到一个状态，表明集群正在运行。如果没有，请尝试使用 `minikube delete` 删除集群，然后再次运行 `minikube start` 重新启动。
