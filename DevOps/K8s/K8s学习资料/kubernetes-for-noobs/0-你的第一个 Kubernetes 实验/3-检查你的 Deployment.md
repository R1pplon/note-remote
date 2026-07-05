# 检查你的 Deployment

现在我们已经创建了一个 Deployment，让我们仔细看看 Kubernetes 为我们设置了什么。

首先，检查我们的 Deployment 状态：

```bash
kubectl get deployments
```

你应该会看到类似以下内容：

```
NAME               READY   UP-TO-DATE   AVAILABLE   AGE
hello-kubernetes   1/1     1            1           2m
```

这个输出告诉我们：

- `READY`: 1/1 表示一个 Pod 已准备就绪，符合期望的一个 Pod。
- `UP-TO-DATE`: 1 表示一个 Pod 正在运行最新的配置。
- `AVAILABLE`: 1 表示一个 Pod 可以处理流量。

接下来，让我们看看 Deployment 创建的 Pod：

```bash
kubectl get pods
```

你应该会看到类似以下内容：

```
NAME                                READY   STATUS    RESTARTS   AGE
hello-kubernetes-6b89d599b9-x7tpv   1/1     Running   0          3m
```

确切的 Pod 名称会有所不同，但你应该会看到一个状态为“Running”的 Pod。这个 Pod 包含了我们的 NGINX 容器。

如果你没有看到运行中的 Pod，请等待一分钟再试一次。Kubernetes 可能仍在创建 Pod 或下载 NGINX 镜像。

要获取有关 Pod 的更多详细信息，请运行：

```bash
kubectl describe pod hello-kubernetes-6b89d599b9-x7tpv
```

> **注意**: 将 `hello-kubernetes-6b89d599b9-x7tpv` 替换为 `kubectl get pods` 输出中显示的你的 Pod 名称。

此命令会输出大量信息。现在不必担心理解所有内容。需要注意的关键点包括：

- `Status`: 应该是“Running”
- `IP`: Pod 的内部 IP 地址
- `Containers`: 有关 Pod 中运行的 NGINX 容器的信息

如果你在此输出中看到任何错误，它们可以帮助诊断 Pod 的问题。
