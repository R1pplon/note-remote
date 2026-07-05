# 向 Kubeconfig 文件添加用户

要向 kubeconfig 文件添加用户，请使用 `kubectl config set-credentials` 命令。此命令需要用户名称、用户的客户端证书和用户的客户端密钥。以下是一个示例：

```shell
kubectl config set-credentials my-user \
  --client-certificate=/home/labex/.minikube/profiles/minikube/client.crt \
  --client-key=/home/labex/.minikube/profiles/minikube/client.key
```

此命令将名为 `my-user` 的用户添加到 kubeconfig 文件中，并指定了客户端证书和密钥。
