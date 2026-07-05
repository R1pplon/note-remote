# 标记节点为不可调度（Cordon）和恢复调度（Uncordon）

在某些情况下，你可能需要将节点从调度中移除以进行维护或其他原因。Kubernetes 提供了一种将节点标记为不可调度的方式，以便不会在其上调度新的 Pod。这称为“cordon”。

要将节点标记为不可调度，请使用以下命令：

```bash
kubectl cordon minikube
```

将 `minikube` 替换为你要标记为不可调度的节点名称。

然后使用以下命令检查节点状态：

```bash
kubectl get node
```

要恢复节点的调度能力并允许在其上调度新的 Pod，请使用以下命令：

```bash
kubectl uncordon minikube
```

将 `minikube` 替换为你要恢复调度的节点名称。

请注意，标记节点为不可调度不会自动将任何现有 Pod 从节点上移除。在标记节点为不可调度之前，你应手动删除或迁移 Pod，以避免任何中断。

恭喜，你已经学会了如何在 Kubernetes 中标记节点为不可调度和恢复调度。
