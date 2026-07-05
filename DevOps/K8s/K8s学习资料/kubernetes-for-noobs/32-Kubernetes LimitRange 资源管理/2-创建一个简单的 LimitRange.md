---
title: "创建一个简单的 LimitRange"
date: 2026-06-20
---

# 创建一个简单的 LimitRange

在这一步骤中，你将创建一个简单的 LimitRange，用于设置命名空间中 Pod 的 CPU 和内存资源限制。以下是具体操作步骤：

1. 创建一个名为 `limitrange.yaml` 的新 YAML 文件，内容如下：

```yml
apiVersion: v1
kind: LimitRange
metadata:
  name: example-limitrange
spec:
  limits:
    - type: Container
      max:
        cpu: "1"
        memory: "1Gi"
      min:
        cpu: "100m"
        memory: "100Mi"
      default:
        cpu: "500m"
        memory: "500Mi"
```

此 LimitRange 设置了以下限制：

- 最大 CPU：1 核
- 最大内存：1 GiB
- 最小 CPU：100 毫核（100m）
- 最小内存：100 MiB
- 默认 CPU：500 毫核（500m）
- 默认内存：500 MiB

2. 使用 `kubectl apply` 命令将 `limitrange.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f limitrange.yaml
```

3. 运行以下命令，验证 LimitRange 是否已成功创建：

```sh
kubectl describe limitrange example-limitrange
```

你应该会看到列出的 LimitRange `example-limitrange`，并显示你在 `spec` 部分中指定的限制。
