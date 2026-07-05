---
title: "将 LimitRange 应用到 Pod"
date: 2026-06-20
---

# 将 LimitRange 应用到 Pod

在这一步骤中，你将创建一个受 `步骤：创建一个简单的 LimitRange` 中创建的 LimitRange 约束的 Pod。以下是具体操作步骤：

1. 创建一个名为 `pod.yaml` 的新 YAML 文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: example-pod
spec:
  containers:
    - name: nginx
      image: nginx
```

此 Pod 定义创建了一个简单的 Pod，其中包含一个运行 Nginx 镜像的容器。

2. 使用 `kubectl apply` 命令将 `pod.yaml` 文件应用到你的 Kubernetes 集群中：

```sh
kubectl apply -f pod.yaml
```

3. 运行以下命令，验证 Pod 是否已成功创建：

```sh
kubectl get pods example-pod
```

你应该会看到列出的 Pod `example-pod`，其状态为 `Running`。

4. 运行以下命令，检查应用到 Pod 的资源限制：

```sh
kubectl describe pod example-pod
```

你应该会看到 Pod 的 CPU 和内存限制，与定义的一致。
