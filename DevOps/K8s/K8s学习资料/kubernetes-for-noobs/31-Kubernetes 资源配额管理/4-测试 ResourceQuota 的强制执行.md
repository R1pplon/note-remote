---
title: "测试 ResourceQuota 的强制执行"
date: 2026-06-20
---

# 测试 ResourceQuota 的强制执行

在本步骤中，你将创建一个超出 ResourceQuota 中定义的资源限制的 Pod，并验证 ResourceQuota 是否强制执行这些限制。以下是具体操作步骤：

1. 创建一个名为 `pod-exceeding-limits.yaml` 的文件，内容如下：

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

此 Pod 定义创建了一个容器，其请求的资源超出了在「步骤：创建 ResourceQuota」中设置的 ResourceQuota 限制（`CPU: 2 核，内存: 2 GiB`）。

2. 使用 `kubectl apply` 命令将 `pod-exceeding-limits.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f pod-exceeding-limits.yaml
```

你可以看到创建 Pod 的操作被拒绝，错误信息为 `Error from server (Forbidden): error when creating "pod-exceeding-limits.yaml": pods "example-pod-exceeding-limits" is forbidden: exceeded quota: example-resourcequota, requested: cpu=2,memory=2Gi, used: cpu=0,memory=0, limited: cpu=1,memory=1Gi`。
