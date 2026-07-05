# 修改 ResourceQuota

在本步骤中，你将学习如何修改现有的 ResourceQuota 以更新资源限制。以下是具体操作步骤：

1. 编辑 `resourcequota.yaml` 文件，将 CPU 和内存限制更新为更高的值：

```yml
apiVersion: v1
kind: ResourceQuota
metadata:
  name: example-resourcequota
spec:
  hard:
    cpu: "2"
    memory: "2Gi"
```

此操作将 ResourceQuota 更新为允许更高的 CPU 和内存限制（分别为 `2 核和 2 GiB`）。

2. 使用 `kubectl apply` 命令将更新后的 `resourcequota.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f resourcequota.yaml
```

3. 运行以下命令以验证 ResourceQuota 是否已更新：

```sh
kubectl describe resourcequotas example-resourcequota
```

你应该在输出中看到更新后的 CPU 和内存限制。
