# 启动 Kubernetes 集群

在这一步中，你将学习如何使用 Minikube 启动并验证本地 Kubernetes 集群。这是在你本地机器上开发和测试 Kubernetes 应用程序的重要第一步。

首先，启动 Minikube 集群：

```bash
minikube start
```

示例输出：

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

现在，使用多个命令验证集群状态：

```bash
minikube status
kubectl get nodes
```

示例输出：

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

这些命令确认了：

1. Minikube 正在成功运行
2. 一个本地 Kubernetes 集群已经被创建
3. 该集群已准备好使用
4. 你拥有一个具有控制平面（control plane）功能的单节点集群

让我们检查集群的上下文（context），以确保你连接到正确的集群：

```bash
kubectl config current-context
```

示例输出：

```
minikube
```

这验证了 `kubectl` 被配置为使用 Minikube 集群。

![Kubernetes 集群设置的图示](https://file.labex.io/namespace/df87b950-1f37-4316-bc07-6537a1f2c481/kubernetes/lab-explore-and-debug-kubernetes-applications/zh/../assets/20250516-08-52-53-8CQculvZ.png)
