# 启动本地 Kubernetes 集群

在这一步中，你将使用 Minikube 启动并验证一个本地 Kubernetes 集群。Minikube 提供了一种简单的方式来设置单节点的 Kubernetes 环境，适用于学习和开发。

首先，导航到项目目录：

```bash
cd ~/project
```

启动 Minikube 集群：

> 注意：免费用户无法连接到互联网，因此 Minikube 已预先启动。你可以跳到下面的代码部分来验证集群状态。[升级为专业用户](https://labex.io/pricing?utm_source=labby) 以练习自行启动集群。

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

使用多个命令验证集群状态：

```bash
minikube status
```

示例输出：

```
minikube
type: Control Plane
host: Running
kubelet: Running
apiserver: Running
kubeconfig: Configured
```

检查集群节点：

```bash
kubectl get nodes
```

示例输出：

```
NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   1m    v1.26.1
```

这些命令确认了以下几点：

1. Minikube 已成功启动
2. 本地 Kubernetes 集群正在运行
3. 集群已准备就绪
4. 你拥有一个具备控制平面功能的单节点集群

Minikube 集群在你的本地机器上提供了一个完整的 Kubernetes 环境，使你无需完整的多节点集群即可开发和测试应用程序。
