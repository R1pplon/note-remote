---
title: "应用 YAML Manifest"
date: 2026-06-20
---

# 应用 YAML Manifest

在此步骤中，你将更详细地探索 `kubectl apply` 命令，并学习应用 Kubernetes manifest 的不同方法。基于上一步的 YAML 文件，我们将演示应用 manifest 的各种技术。

首先，请确保你位于正确的目录：

```bash
cd ~/project/k8s-manifests
```

让我们创建一个新的子目录来进一步组织我们的 manifest。创建一个名为 `manifests` 的目录并进入该目录：

```bash
mkdir -p manifests
cd manifests
```

现在，让我们创建一个包含 Deployment 和 Service 的简单 Web 应用程序的 manifest，所有内容都在一个文件中。使用 `nano` 创建一个名为 `web-app.yaml` 的新文件：

```bash
nano web-app.yaml
```

将以下内容添加到 `web-app.yaml`：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app
spec:
  replicas: 2
  selector:
    matchLabels:
      app: web
  template:
    metadata:
      labels:
        app: web
    spec:
      containers:
        - name: web
          image: nginx:alpine
          ports:
            - containerPort: 80
---
apiVersion: v1
kind: Service
metadata:
  name: web-service
spec:
  selector:
    app: web
  type: ClusterIP
  ports:
    - port: 80
      targetPort: 80
```

此 manifest 在单个文件中定义了两个 Kubernetes 资源：一个 Deployment 和一个 Service。`---` 分隔符用于区分它们。让我们分解一下新增的内容：

- **单个文件中的多个资源**: `web-app.yaml` 文件现在包含两个独立的 Kubernetes 资源定义：一个 Deployment 和一个 Service。`---` 分隔符用于区分它们。
- **`kind: Service`**: 这定义了一个 Service 资源。
  - **`spec.selector.app: web`**: 此 Service 将定位具有 `app: web` 标签的 Pod。这与我们为 `web-app` Deployment 创建的 Pod 设置的标签匹配。
  - **`spec.type: ClusterIP`**: 将服务类型指定为 `ClusterIP`。这意味着该服务将在集群内的内部 IP 地址上公开，通常用于集群内服务之间的通信。
  - **`spec.ports`**: 定义服务如何将端口映射到目标 Pod。
    - **`port: 80`**: 你将访问的服务本身的端口。
    - **`targetPort: 80`**: 服务将流量转发到的目标 Pod 上的端口。

现在，让我们使用不同的方法应用此 manifest。

**方法 1：应用整个文件**

这是应用 manifest 最常见的方式。使用 `kubectl apply -f` 后跟文件名：

```bash
kubectl apply -f web-app.yaml
```

此命令将创建 `web-app.yaml` 中定义的 Deployment 和 Service。你应该会看到类似以下的输出：

```
deployment.apps/web-app created
service/web-service created
```

**方法 2：从目录应用**

你可以一次性应用目录中的所有 manifest。如果你在 `manifests` 目录中有多个 manifest 文件，你可以通过指定目录而不是特定文件来应用所有文件：

```bash
kubectl apply -f .
```

`.` 代表当前目录。`kubectl` 将在此目录中查找 YAML 文件并应用所有文件。当你将 manifest 组织到目录中的多个文件中时，这非常有用。

**方法 3：从 URL 应用（可选）**

`kubectl apply` 也可以直接从 URL 应用 manifest。这对于快速部署在线托管的示例应用程序或配置非常有用。例如，你可以从 Kubernetes 示例存储库部署 Redis master Deployment：

```bash
kubectl apply -f https://raw.githubusercontent.com/kubernetes/examples/master/guestbook/redis-master-deployment.yaml
```

这将从 URL 下载 manifest 并将其应用到你的集群。注意：从不受信任的 URL 应用 manifest 时要小心，因为它们可能会修改你的集群。

让我们探索 `kubectl apply` 的一些附加选项。

**Dry Run（试运行）**

你可以使用 `--dry-run=client` 标志来模拟应用 manifest，而无需实际更改集群。这对于检查 manifest 是否有效以及查看将创建或修改哪些资源非常有用：

```bash
kubectl apply -f web-app.yaml --dry-run=client
```

此命令将输出 *将要* 创建或更改的内容，但实际上不会将更改应用到你的集群。

**Verbose Output（详细输出）**

为了获得 `kubectl apply` 的更详细输出，你可以使用 `-v` 标志后跟一个详细级别（例如 `-v=7`）。更高的详细级别提供更多信息，这对于调试很有帮助：

```bash
kubectl apply -f web-app.yaml -v=7
```

这将打印更多关于正在进行的 API 请求和 manifest 处理的信息。

通过应用 `web-app.yaml` 来验证创建的资源。使用 `kubectl get deployments` 和 `kubectl get services` 来列出集群中的 Deployment 和 Service：

```bash
# 列出 deployments
kubectl get deployments

# 列出 services
kubectl get services

# 描述 deployment 以查看更多详细信息
kubectl describe deployment web-app
```

`kubectl get deployments` 的示例输出：

```plaintext
NAME               READY   UP-TO-DATE   AVAILABLE   AGE
nginx-deployment   3/3     3            3           3m33s
redis-master       0/1     1            0           23s
web-app            2/2     2            2           42s
```

`kubectl get services` 的示例输出：

```plaintext
NAME          TYPE        CLUSTER-IP      EXTERNAL-IP   PORT(S)   AGE
kubernetes    ClusterIP   10.96.0.1       <none>        443/TCP   8m28s
web-service   ClusterIP   10.106.220.33   <none>        80/TCP    46s
```

请注意，现在你有一个 `web-app` Deployment，其 `READY` 副本数为 2/2，以及一个类型为 `ClusterIP` 的 `web-service`。

让我们简要讨论 Kubernetes 中声明式管理和命令式管理之间的区别，特别是在 `kubectl apply` 和 `kubectl create` 的上下文中。

- **`kubectl apply`**: 使用声明式方法。你在 manifest 文件中定义期望状态，而 `kubectl apply` 会尝试实现该状态。如果你多次运行具有相同 manifest 的 `kubectl apply`，只有当 manifest 中的期望状态与集群中的当前状态存在差异时，Kubernetes 才会进行更改。`kubectl apply` 通常推荐用于管理 Kubernetes 资源，因为它更健壮且易于随时间推移管理更改。它会跟踪你资源的配置，并允许进行增量更新。
- **`kubectl create`**: 使用命令式方法。你直接指示 Kubernetes 创建一个资源。如果你尝试为已存在的资源运行 `kubectl create`，通常会导致错误。与 `kubectl apply` 相比，`kubectl create` 在管理更新和更改方面灵活性较低。

在大多数情况下，尤其是在管理应用程序部署时，**`kubectl apply` 是首选且推荐的方法**，因为它具有声明性，并且在处理更新和配置管理方面表现更好。
