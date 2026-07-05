# 在 Kubeconfig 文件中创建上下文

要在 kubeconfig 文件中创建上下文，请使用 `kubectl config set-context` 命令。此命令需要上下文的名称、要使用的集群以及用于身份验证的用户。以下是一个示例：

```shell
kubectl config set-context my-context \
  --cluster=my-cluster \
  --user=my-user
```

此命令在 kubeconfig 文件中创建了一个名为 `my-context` 的上下文，使用 `my-cluster` 集群和 `my-user` 用户。
