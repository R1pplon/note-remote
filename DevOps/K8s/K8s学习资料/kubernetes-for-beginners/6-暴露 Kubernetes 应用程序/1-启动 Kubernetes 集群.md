# 启动 Kubernetes 集群

在这一步中，你将学习如何使用 Minikube 启动并验证一个本地 Kubernetes 集群。这是在本地机器上开发和测试 Kubernetes 应用程序的重要第一步。

首先，启动 Minikube 集群：

```bash
minikube start
```

示例输出：

```
😄  minikube v1.29.0 on Ubuntu 22.04
✨  自动选择 docker 驱动
📌  使用 Docker 驱动并具有 root 权限
🔥  在 Kubernetes 集群中创建 Kubernetes
🔄  重启现有的 Kubernetes 集群
🐳  在 Docker 20.10.23 上准备 Kubernetes v1.26.1 ...
🚀  启动 Kubernetes ...
🌟  启用插件：storage-provisioner, default-storageclass
🏄  完成！kubectl 现在已配置为使用 "minikube" 集群和 "default" 命名空间
```

使用多个命令验证集群状态：

```bash
minikube status
kubectl get nodes
```

`minikube status` 的示例输出：

```
minikube
type: Control Plane
host: Running
kubelet: Running
apiserver: Running
kubeconfig: Configured
```

`kubectl get nodes` 的示例输出：

```
NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   1m    v1.26.1
```

这些命令确认了以下内容：

1. Minikube 已成功运行
2. 本地 Kubernetes 集群已创建
3. 集群已准备就绪
4. 你拥有一个具有控制平面功能的单节点集群
