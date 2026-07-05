---
title: "创建同时使用节点亲和性和节点选择器的 Pod"
date: 2026-06-20
---

# 创建同时使用节点亲和性和节点选择器的 Pod

在此步骤中，我们将创建一个同时包含节点亲和性规则和节点选择器（node selector）的 Pod，以确保它被调度到具有特定标签的节点上。

1. 在 `/home/labex/project` 目录下创建一个名为 `pod-with-node-affinity-and-selector.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: pod-with-node-affinity-and-selector
spec:
  containers:
    - name: nginx
      image: nginx:latest
  nodeSelector:
    type: web
  affinity:
    nodeAffinity:
      requiredDuringSchedulingIgnoredDuringExecution:
        nodeSelectorTerms:
          - matchExpressions:
              - key: type
                operator: In
                values:
                  - db
```

2. 应用更改：

```bash
kubectl apply -f pod-with-node-affinity-and-selector.yaml
```

3. 验证 Pod 是否未被调度到带有 `type=web` 标签的节点上（因为亲和性规则要求 `type=db`）：

```bash
kubectl get pod pod-with-node-affinity-and-selector -o wide
```
