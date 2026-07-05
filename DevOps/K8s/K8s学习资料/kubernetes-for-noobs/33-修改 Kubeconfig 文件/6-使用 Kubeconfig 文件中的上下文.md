# 使用 Kubeconfig 文件中的上下文

要使用 kubeconfig 文件中的上下文，请使用 `kubectl config use-context` 命令。此命令需要指定要使用的上下文名称。以下是一个示例：

```shell
kubectl config use-context my-context
```

此命令将当前上下文设置为 `my-context`，因此所有后续的 `kubectl` 命令都将使用指定的集群和用户。
