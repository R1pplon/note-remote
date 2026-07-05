# 启动你的 Kubernetes 集群

我们将从使用 Minikube 创建一个本地 Kubernetes 集群开始。Minikube 允许你在计算机上运行一个单节点的 Kubernetes 集群，非常适合学习和开发。

首先，打开你的终端。你应该位于 `/home/labex/project` 目录中。如果不确定，可以通过以下命令切换到该目录：

```bash
cd /home/labex/project
```

现在，让我们启动 Minikube 集群：

> 注意：免费用户无法连接到互联网，因此 Minikube 已预先安装在实验室环境中。你可以跳过此步骤。[升级为专业用户](https://labex.io/pricing?utm_source=labby) 以练习自行启动集群。

<details style="padding: 15px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">
<summary style="font-weight: bold; color: #0066cc; cursor: pointer;">Pro Users Only</summary>

```bash
minikube start
```

![Minikube 集群初始化](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-the-first-kubernetes-lab/zh/../assets/20240911-11-05-16-YrLGV24C.png)

此命令会在你的本地机器上初始化一个 Kubernetes 集群。以下是背后发生的事情：

1. Minikube 在你的计算机上创建一个虚拟机（VM）。
2. 它在这个虚拟机中安装并配置 Kubernetes。
3. 它设置网络，以便你的计算机可以与集群通信。

这个过程可能需要几分钟。你会看到 Minikube 工作时输出大量信息。如果不理解所有内容，请不要担心——关键是等待最后出现“Done!”的消息。

</details>
