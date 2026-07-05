# 创建你的第一个 Deployment

现在我们的集群已经运行起来了，让我们创建第一个 Kubernetes Deployment。Deployment 是一个 Kubernetes 对象，用于管理一组相同的 Pod。（别担心，我们很快就会解释什么是 Pod！）

我们将创建一个运行单个 NGINX Web 服务器容器的 Deployment。NGINX 是一个流行的 Web 服务器，我们将在这个示例中使用它。

运行以下命令：

```bash
kubectl create deployment hello-kubernetes --image=nginx:latest --port=80
```

让我们分解一下这个命令：

- `kubectl` 是与 Kubernetes 交互的命令行工具。
- `create deployment` 告诉 Kubernetes 创建一个新的 Deployment。
- `hello-kubernetes` 是我们为 Deployment 指定的名称。
- `--image=nginx:latest` 指定我们希望使用最新版本的 NGINX Docker 镜像。
- `--port=80` 告诉 Kubernetes 容器将监听 80 端口（Web 流量的标准端口）。

运行此命令后，你应该会看到：

```
deployment.apps/hello-kubernetes created
```

这意味着 Kubernetes 已成功创建了你的 Deployment。但它具体做了什么呢？

1. Kubernetes 下载了 NGINX Docker 镜像。
2. 它创建了一个 Pod（一个或多个容器的组），并在其中启动了 NGINX 容器。
3. 它设置了网络，以便可以通过 80 端口访问该 Pod。
