# 启动 Kubernetes 集群

在此步骤中，我们将使用 Minikube 启动一个 Kubernetes 集群。Minikube 是一个非常适合开发和学习 Kubernetes 的工具，因为它允许你在虚拟化环境中运行一个单节点 Kubernetes 集群。然后，我们将验证集群是否正常运行并已准备好使用。

首先，打开你的终端。你将在这里输入命令与你的计算机进行交互。要启动 Minikube 集群，请键入以下命令并按 Enter：

```bash
minikube start
```

此命令将启动创建和启动你的 Kubernetes 集群的过程。Minikube 将下载必要的组件并配置你的集群。当 Minikube 启动时，你将在终端中看到输出。以下是输出可能样子的示例：

```
😄  minikube v1.29.0 on Ubuntu 22.04
✨  Automatically selected the docker driver
📌  Using Docker driver with root permissions
🔥  Creating kubernetes in kubernetes cluster
🔄  Restarting existing kubernetes cluster
🐳  Preparing Kubernetes v1.26.1 on Docker 20.10.23 ...
🚀  Launching Kubernetes ...
🌟  Enabling addons: storage-provisioner, default-storageclass
🏄  Done! kubectl is now configured to use "minikube" cluster and "default" namespace
```

Minikube 启动后，让我们验证它是否正在运行以及 Kubernetes 集群是否已准备就绪。依次运行以下命令，在每个命令后按 Enter：

```bash
minikube status
kubectl get nodes
```

`minikube status` 命令将告诉你 Minikube 本身的状态。`kubectl get nodes` 命令将与你的 Kubernetes 集群通信，并检索有关你集群中节点（计算机）的信息。由于 Minikube 是一个单节点集群，你应该会看到列出一个节点。

以下是你可能看到的输出示例：

```
minikube
type: Control Plane
host: Running
kubelet: Running
apiserver: Running
kubeconfig: Configured

NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   1m    v1.26.1
```

让我们分解一下这些输出告诉我们的信息：

1. `minikube` 状态显示 `host`、`kubelet` 和 `apiserver` 为 `Running`。这表明 Minikube 的核心组件正在正常运行。
2. `kubectl get nodes` 显示一个名为 `minikube` 的节点，其 `STATUS` 为 `Ready`。`Ready` 表示该节点已准备好运行应用程序。`ROLES` 下的 `control-plane` 表明该节点正在作为 Kubernetes 集群的控制平面，负责管理和编排集群。

这些命令确认了以下几点：

1. Minikube 正在虚拟化环境中运行。
2. Kubernetes 集群已由 Minikube 创建和配置。
3. Kubernetes 集群处于 `Ready` 状态，已准备好使用。
4. 你拥有一个单节点 Kubernetes 集群，其中 `minikube` 节点充当控制平面。
