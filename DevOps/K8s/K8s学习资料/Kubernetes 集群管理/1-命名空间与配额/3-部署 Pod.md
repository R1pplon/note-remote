---
title: "部署 Pod"
date: 2026-06-20
---

# 部署 Pod

规则设定好后，让我们尝试搬进去。

**关键规则**：当公寓有配额限制时，你带入的每一件家具都**必须**声明其大小。用 Kubernetes 的术语来说：如果一个命名空间有配额，那么每个 Pod 都必须指定明确的 `resources`（请求和限制）。如果你不这样做，楼宇管理员（Kubernetes）会将你挡在门外。

这里涉及两个相关概念。**请求（request）** 告诉调度器 Pod 在放置时应保证获得多少资源。**限制（limit）** 是容器被允许使用的最大资源量。配额会将命名空间中所有 Pod 的请求和限制累加起来，以确保总量符合策略。

创建一个名为 `pod.yaml` 的文件：

```bash
nano pod.yaml
```

粘贴以下内容。注意我们明确声明了这个 Pod 的大小：

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: dev-pod
  namespace: dev # <-- 将其放入 'dev' 公寓
spec:
  containers:
    - name: nginx
      image: nginx
      resources:
        requests:
          memory: "128Mi"
          cpu: "250m"
        limits:
          memory: "256Mi"
          cpu: "500m"
```

保存并退出（`Ctrl+X`，`Y`，`Enter`）。

部署 Pod：

```bash
kubectl apply -f pod.yaml
```

检查它是否在 `dev` 命名空间中运行：

```bash
kubectl get pods --namespace=dev
```

等待直到看到 `Running` 状态。

现在，再次检查配额使用情况。你会发现「已使用」列的数据已经增加了！

```bash
kubectl describe resourcequota dev-quota --namespace=dev
```

你已经成功地根据预算跟踪了资源使用情况。
