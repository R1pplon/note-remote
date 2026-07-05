---
title: "测试 LimitRange 的强制执行"
date: 2026-06-20
---

# 测试 LimitRange 的强制执行

在这一步骤中，你将通过尝试创建一个超出 LimitRange 中定义的资源限制的 Pod 来测试 LimitRange 的强制执行。以下是具体操作步骤：

1. 创建一个名为 `pod-exceeding-limits.yaml` 的新 YAML 文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: example-pod-exceeding-limits
spec:
  containers:
    - name: nginx
      image: nginx
      resources:
        limits:
          cpu: "2"
          memory: "2Gi"
```

此 Pod 定义创建了一个容器，其请求的资源超出了 LimitRange 中设置的限制（`CPU: 2 核，内存: 2 GiB`）。

2. 使用 `kubectl apply` 命令将 `pod-exceeding-limits.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f pod-exceeding-limits.yaml
```

你可以看到创建 Pod 的操作被拒绝，错误信息为 `Error from server (Forbidden): error when creating "pod-exceeding-limits. yaml": pod "example-pod-exceeding-limits " Forbidden: [Maximum cpu usage per container is 1, but limited to 2, maximum memory usage per container is 1Gi, but limited to 2Gi]`。
