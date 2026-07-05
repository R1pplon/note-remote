---
title: "创建带有节点亲和性的 Pod"
date: 2026-06-20
---

# 创建带有节点亲和性的 Pod

在此步骤中，我们将创建一个带有节点亲和性规则的 Pod，以确保它被调度到具有特定标签的节点上。

1. 在 `/home/labex/project` 目录下创建一个名为 `pod-with-node-affinity.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: pod-with-node-affinity
spec:
  containers:
    - name: nginx
      image: nginx:latest
  affinity:
    nodeAffinity:
      requiredDuringSchedulingIgnoredDuringExecution:
        nodeSelectorTerms:
          - matchExpressions:
              - key: type
                operator: In
                values:
                  - web
```

2. 应用更改：

```bash
kubectl apply -f pod-with-node-affinity.yaml
```

3. 验证 Pod 是否被调度到带有 `type=web` 标签的节点上：

```bash
kubectl get pod pod-with-node-affinity -o wide
```
