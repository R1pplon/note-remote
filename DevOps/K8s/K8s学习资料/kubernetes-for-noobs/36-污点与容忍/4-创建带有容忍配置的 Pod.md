---
title: "创建带有容忍配置的 Pod"
date: 2026-06-20
---

# 创建带有容忍配置的 Pod

在这一步骤中，我们将创建一个带有容忍配置的 Pod，使其能够被调度到带有污点的节点上。

1. 创建一个名为 `pod-with-toleration.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: pod-with-toleration
spec:
  containers:
    - name: nginx
      image: nginx:latest
  tolerations:
    - key: "disk-type"
      operator: "Equal"
      value: "ssd"
      effect: "NoSchedule"
```

2. 应用更改：

```bash
kubectl apply -f pod-with-toleration.yaml
```

3. 验证 Pod 是否被调度到带有污点的节点上：

```bash
kubectl get pod pod-with-toleration -o wide
```
