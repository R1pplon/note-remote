# 将 ResourceQuota 应用到命名空间

在本步骤中，你将把在「步骤：创建 ResourceQuota」中创建的 ResourceQuota 应用到一个命名空间。以下是具体操作步骤：

1. 创建一个名为 `namespace.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Namespace
metadata:
  name: example-namespace
```

此命名空间定义创建了一个名为 `example-namespace` 的命名空间。

2. 使用 `kubectl apply` 命令将 `namespace.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f namespace.yaml
```

3. 使用 `kubectl apply` 命令将 ResourceQuota 应用到 `example-namespace` 命名空间：

```sh
kubectl apply -f resourcequota.yaml -n example-namespace
```

4. 运行以下命令以验证 ResourceQuota 是否已成功应用到命名空间：

```sh
kubectl describe namespace example-namespace
```

你应该在输出中看到应用到命名空间的 ResourceQuota 的详细信息。
