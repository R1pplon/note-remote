---
title: "定义 Pod"
date: 2026-06-20
---

# 定义 Pod

在这一步中，你将定义你的第一个 Pod。Kubernetes 使用 **YAML** 语言。YAML 文件就像是一份蓝图或配方，精确描述了你希望应用程序呈现的状态。

Pod 是 Kubernetes 调度的最小对象，但它与容器并不等同。容器是正在运行的应用程序进程及其文件系统镜像。Pod 是 Kubernetes 对一个或多个容器的封装。这种封装为它们提供了共享的网络标识、共享的生命周期以及可选的共享存储。

Pod 的定义需要四个主要要素：

- **apiVersion**：我们所使用的 Kubernetes 语言版本。
- **kind**：我们要创建的对象类型（在本例中为 `Pod`）。
- **metadata**：用于标识对象的元数据，例如名称和标签。
- **spec**：即“规格说明”（specification）——这是配方的核心，描述了要运行的容器。

你将创建一个名为 `nginx-pod.yaml` 的文件。该文件将定义一个名为 `nginx-pod` 的 Pod，它使用 `nginx` 镜像（一种流行的 Web 服务器）运行单个容器。

使用 `nano` 文本编辑器创建该文件：

```bash
nano nginx-pod.yaml
```

将以下内容复制并粘贴到文件中。请仔细阅读，看看我们讨论的那四个部分：

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: nginx-pod
  labels:
    app: nginx
spec:
  containers:
    - name: nginx
      image: nginx:latest
      ports:
        - containerPort: 80
```

要保存并退出 `nano`，请按 `Ctrl+X`，然后输入 `Y` 确认保存，最后按 `Enter` 键写入文件名。

现在你已经编写了第一个 Kubernetes 蓝图！它告诉 Kubernetes：“我想要一个名为 **nginx-pod** 的 **Pod**。请在其中运行一个使用 **nginx** 镜像的 **容器**。”
