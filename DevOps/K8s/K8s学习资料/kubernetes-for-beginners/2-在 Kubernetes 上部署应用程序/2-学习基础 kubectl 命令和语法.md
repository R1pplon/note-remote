---
title: "学习基础 kubectl 命令和语法"
date: 2026-06-20
---

# 学习基础 kubectl 命令和语法

在此步骤中，你将探索基础的 `kubectl` 命令。`kubectl` 是允许你与 Kubernetes 集群交互的命令行工具。它是管理 Kubernetes 资源的关键。我们将演示如何使用 `kubectl` 来查看资源并理解基本的 Kubernetes 对象管理。

让我们从探索 Kubernetes 集群中的命名空间（namespaces）开始。命名空间是组织 Kubernetes 资源的一种方式。默认情况下，Kubernetes 集群包含多个用于系统组件和用户资源的命名空间。要查看命名空间列表，请运行以下命令：

```bash
kubectl get namespaces
```

此命令将列出你集群中所有可用的命名空间。示例输出：

```
NAME              STATUS   AGE
default           Active   10m
kube-node-lease   Active   10m
kube-public       Active   10m
kube-system       Active   10m
```

你通常会看到至少这些默认命名空间：

- `default`: 如果未指定其他命名空间，则为用户创建资源的默认命名空间。
- `kube-node-lease`: 用于节点租约，这有助于控制平面跟踪节点健康状况。
- `kube-public`: 用于应公开访问的资源（尽管很少用于敏感信息）。
- `kube-system`: 包含系统级资源，例如核心 Kubernetes 组件。

接下来，让我们查看在 `kube-system` 命名空间中运行的系统组件。许多核心 Kubernetes 组件作为 Pod 在此命名空间中运行。要查看特定命名空间中的 Pod，请使用 `-n` 或 `--namespace` 标志，后跟命名空间名称。运行以下命令以查看 `kube-system` 命名空间中的 Pod：

```bash
kubectl get pods -n kube-system
```

此命令将列出在 `kube-system` 命名空间中运行的所有 Pod。示例输出：

```
NAME                               READY   STATUS    RESTARTS   AGE
coredns-787d4945fb-j8rhx           1/1     Running   0          15m
etcd-minikube                       1/1     Running   0          15m
kube-apiserver-minikube             1/1     Running   0          15m
kube-controller-manager-minikube    1/1     Running   0          15m
kube-proxy-xb9rz                    1/1     Running   0          15m
kube-scheduler-minikube             1/1     Running   0          15m
storage-provisioner                 1/1     Running   0          15m
```

此输出显示了 Pod 的名称、它们的 `READY` 状态（Pod 中有多少容器已准备好相对于总数）、它们的 `STATUS`（例如 `Running`）、它们 `RESTARTED` 的次数以及它们的 `AGE`。这些是使 Kubernetes 正常工作的基本组件。

现在，让我们探索一些基本的 `kubectl` 命令模式。`kubectl` 命令的通用语法是：

```bash
kubectl [command] [TYPE] [NAME] [flags]
```

让我们分解一下：

- `kubectl`: 命令行工具本身。
- `[command]`: 指定你想要执行的操作。常用命令包括：
  - `get`: 显示一个或多个资源。
  - `describe`: 显示关于特定资源的详细信息。
  - `create`: 创建一个新资源。
  - `delete`: 删除资源。
  - `apply`: 将配置应用于资源。我们稍后会经常使用它。
- `[TYPE]`: 指定你想要交互的 Kubernetes 资源类型。常用资源类型包括：
  - `pods`: Kubernetes 中最小的可部署单元。
  - `deployments`: 管理 Pod 集合以进行扩展和更新。
  - `services`: 暴露运行在 Pod 中的应用程序。
  - `nodes`: Kubernetes 集群中的工作节点。
  - `namespaces`: 资源的逻辑分组。
- `[NAME]`: 特定资源的名称。这是可选的；如果你省略名称，`kubectl` 将操作指定类型的所有资源。
- `[flags]`: 可选标志，用于修改命令的行为（例如，`-n <namespace>`，`-o wide`）。

让我们看一些例子：

```bash
# 获取默认命名空间中的所有资源
kubectl get all

# 描述特定资源类型（'minikube' 节点）
kubectl describe nodes minikube
```

`kubectl get all` 命令将检索 `default` 命名空间中所有资源类型（services、deployments、pods 等）的信息。示例输出可能如下所示：

```
NAME                 TYPE        CLUSTER-IP   EXTERNAL-IP   PORT(S)   AGE
service/kubernetes   ClusterIP   10.96.0.1    <none>        443/TCP   20m
```

这表明在 `default` 命名空间中，我们有一个名为 `kubernetes` 的 `service`。

`kubectl describe nodes minikube` 命令将提供关于 `minikube` 节点的大量详细信息，包括其状态、容量、地址等。这有助于理解你的节点的状态和配置。

这些命令可以帮助你：

1. 查看 Kubernetes 集群中的资源。
2. 获取有关集群组件及其当前状态的详细信息。
3. 理解 `kubectl` 的基本命令结构和语法。
