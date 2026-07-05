---
title: "验证 Deployment 状态"
date: 2026-06-20
---

# 验证 Deployment 状态

在此步骤中，你将学习如何使用各种 `kubectl` 命令来检查和验证 Kubernetes Deployment 和其他资源的状 态。你将探索收集有关正在运行的应用程序及其在 Kubernetes 集群中健康状况信息的不同方法。

首先，请确保你位于项目中的 `manifests` 目录：

```bash
cd ~/project/k8s-manifests/manifests
```

让我们开始列出当前命名空间（除非你已更改，否则为 `default`）中的所有 Deployment。使用 `kubectl get deployments`：

```bash
kubectl get deployments
```

此命令提供了 Deployment 的简洁概览。示例输出：

```
NAME               READY   UP-TO-DATE   AVAILABLE   AGE
nginx-deployment   3/3     3            3           4m44s
redis-master       1/1     1            1           94s
web-app            2/2     2            2           113s
```

以下是每列的含义：

- `NAME`: Deployment 的名称。
- `READY`: 显示就绪副本的数量与期望副本的数量（例如，`3/3` 表示 3 个期望的副本已就绪）。
- `UP-TO-DATE`: 指示有多少副本已更新到最新的期望状态。
- `AVAILABLE`: 显示当前有多少副本可用于处理流量。
- `AGE`: Deployment 运行了多长时间。

要以更宽的格式获取更详细的信息，你可以将 `-o wide` 标志与 `kubectl get deployments` 一起使用：

```bash
kubectl get deployments -o wide
```

示例输出：

```
NAME               READY   UP-TO-DATE   AVAILABLE   AGE     CONTAINERS   IMAGES                      SELECTOR
nginx-deployment   3/3     3            3           4m58s   nginx        nginx:latest                app=nginx
redis-master       1/1     1            1           108s    master       registry.k8s.io/redis:e2e   app=redis,role=master,tier=backend
web-app            2/2     2            2           2m7s    web          nginx:alpine                app=web
```

`-o wide` 输出包含 `CONTAINERS`、`IMAGES` 和 `SELECTOR` 等附加列，提供了有关 Deployment 的更多上下文信息。

要检查特定 Deployment 所属的 Pod，你可以使用标签。请记住，在我们 `web-app` Deployment manifest 中，我们为 Pod 设置了 `app: web` 标签。你可以使用此标签通过 `kubectl get pods -l <label_selector>` 过滤 Pod。要查看与 `web-app` Deployment 关联的 Pod，请运行：

```bash
kubectl get pods -l app=web
```

示例输出：

```
NAME                      READY   STATUS    RESTARTS   AGE
web-app-xxx-yyy           1/1     Running   0          10m
web-app-xxx-zzz           1/1     Running   0          10m
```

这将列出匹配标签选择器 `app=web` 的 Pod，这些 Pod 是由 `web-app` Deployment 管理的。

要获取特定 Deployment 的详细信息，请使用 `kubectl describe deployment <deployment_name>`。让我们描述一下 `web-app` Deployment：

```bash
kubectl describe deployment web-app
```

`kubectl describe` 提供有关 Deployment 的大量信息，包括：

- **Name, Namespace, CreationTimestamp, Labels, Annotations**: Deployment 的基本元数据。
- **Selector**: 用于标识此 Deployment 管理的 Pod 的标签选择器。
- **Replicas**: 期望的、已更新的、总共的、可用的和不可用的副本计数。
- **StrategyType, RollingUpdateStrategy**: 有关更新策略的详细信息（例如，RollingUpdate）。
- **Pod Template**: 用于为此 Deployment 创建 Pod 的规范。
- **Conditions**: 指示 Deployment 状态的条件（例如，`Available`、`Progressing`）。
- **Events**: 与 Deployment 相关的事件列表，这对于故障排除很有帮助。

查看 `describe` 输出中的 `Conditions` 和 `Events` 部分。如果你的 Deployment 存在问题，这些部分通常会提供线索。例如，如果 Deployment 未变为 `Available`，`Events` 可能会显示与镜像拉取、Pod 创建失败等相关的错误。

要检查 Service 的状态，你可以使用类似的命令。首先，列出所有 Service：

```bash
kubectl get services
```

示例输出：

```
NAME          TYPE        CLUSTER-IP      EXTERNAL-IP   PORT(S)   AGE
kubernetes    ClusterIP   10.96.0.1       <none>        443/TCP   10m
web-service   ClusterIP   10.106.220.33   <none>        80/TCP    2m47s
```

这会显示 `web-service` 及其 `TYPE`（在此情况下为 `ClusterIP`）、`CLUSTER-IP` 和公开的 `PORT(S)`。

要获取 Service 的更多详细信息，请使用 `kubectl describe service <service_name>`：

```bash
kubectl describe service web-service
```

`describe service` 输出包括：

- **Name, Namespace, Labels, Annotations**: 基本元数据。
- **Selector**: 用于标识目标 Pod 的标签选择器。
- **Type**: Service 类型（在此情况下为 `ClusterIP`）。
- **IP, IPs**: 分配给 Service 的集群 IP 地址。
- **Port, TargetPort**: 为 Service 定义的端口映射。
- **Endpoints**: 显示当前支持此 Service 的 Pod 的 IP 地址和端口。**这非常重要**。如果你没有看到任何端点，则意味着 Service 未正确连接到任何 Pod，可能是由于选择器不匹配。
- **Session Affinity, Events**: 其他 Service 配置和事件。

在验证 Deployment 状态时，关键要查找的是：

- **Deployment `READY` 状态**: 确保它显示了期望的副本数量（例如，`web-app` 为 `2/2`）。
- **Pod `STATUS`**: 所有 Pod 都应处于 `Running` 状态。
- **Service `Endpoints`**: 检查 Service 是否有端点，并且它们是否对应于你的运行 Pod 的 IP 地址。如果没有端点，请排查 Service 的选择器和 Pod 标签。
- **检查警告或错误**: 查看 `kubectl describe deployment <deployment_name>` 和 `kubectl describe service <service_name>` 的输出，查找 `Events` 部分中的任何异常情况或错误。

通过使用这些 `kubectl` 命令，你可以有效地监控和验证 Kubernetes 中 Deployment 和 Service 的状态，确保你的应用程序按预期运行。
