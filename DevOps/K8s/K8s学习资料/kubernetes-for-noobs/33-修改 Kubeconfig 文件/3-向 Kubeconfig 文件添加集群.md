---
title: "向 Kubeconfig 文件添加集群"
date: 2026-06-20
---

# 向 Kubeconfig 文件添加集群

要向 kubeconfig 文件添加集群，请使用 `kubectl config set-cluster` 命令。此命令需要集群名称、服务器 URL 和证书颁发机构（CA）数据。以下是一个示例：

```shell
kubectl config set-cluster my-cluster \
  --server=https://kubernetes.default.svc \
  --certificate-authority=/home/labex/.minikube/ca.crt
```

此命令将名为 `my-cluster` 的集群添加到 kubeconfig 文件中，并指定了服务器 URL 和 CA 数据。
