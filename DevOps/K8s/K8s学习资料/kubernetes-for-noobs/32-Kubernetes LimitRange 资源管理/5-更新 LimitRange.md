# 更新 LimitRange

在这一步骤中，你将更新在 `步骤：创建一个简单的 LimitRange` 中创建的 LimitRange，以修改资源限制。以下是具体操作步骤：

1. 修改 `limitrange.yaml` 文件，根据你的需求更新资源限制。例如：

```yml
apiVersion: v1
kind: LimitRange
metadata:
  name: example-limitrange
spec:
  limits:
    - type: Container
      max:
        cpu: "2"
        memory: "2Gi"
      min:
        cpu: "200m"
        memory: "200Mi"
      default:
        cpu: "1"
        memory: "1Gi"
```

此更新后的 LimitRange 设置了以下限制：

- 最大 CPU：2 核
- 最大内存：2 GiB
- 最小 CPU：200 毫核（200m）
- 最小内存：200 MiB
- 默认 CPU：1 核
- 默认内存：1 GiB

2. 使用 `kubectl apply` 命令将更新后的 `limitrange.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f limitrange.yaml
```

3. 运行以下命令，验证 LimitRange 是否已成功更新：

```sh
kubectl describe limitranges example-limitrange
```

你应该会在输出中看到更新后的资源限制。
