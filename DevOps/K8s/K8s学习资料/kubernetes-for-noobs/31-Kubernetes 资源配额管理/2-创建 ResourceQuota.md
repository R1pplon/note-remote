# 创建 ResourceQuota

在本步骤中，你将创建一个简单的 ResourceQuota，用于限制命名空间中可以使用的 CPU 和内存量。以下是具体操作步骤：

1. 创建一个名为 `resourcequota.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: ResourceQuota
metadata:
  name: example-resourcequota
spec:
  hard:
    cpu: "1"
    memory: "1Gi"
```

此 ResourceQuota 设置了以下硬性限制：

- CPU：1 核
- 内存：1 GiB

2. 使用 `kubectl apply` 命令将 `resourcequota.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f resourcequota.yaml
```

3. 运行以下命令以验证 ResourceQuota 是否已成功创建：

```sh
kubectl describe resourcequota example-resourcequota
```

你应该在输出中看到 ResourceQuota 的详细信息。
