---
title: "创建带有节点反亲和性的 Pod"
date: 2026-06-20
---

# 创建带有节点反亲和性的 Pod

在此步骤中，我们将创建一个带有节点反亲和性（node anti-affinity）规则的 Pod，以确保它不会被调度到具有特定标签的节点上。

1. 在 `/home/labex/project` 目录下创建一个名为 `pod-with-node-anti-affinity.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: pod-with-node-anti-affinity
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
                operator: NotIn
                values:
                  - web
```

2. 应用更改：

```bash
kubectl apply -f pod-with-node-anti-affinity.yaml
```

3. 验证 Pod 是否未被调度到带有 `type=db` 标签的节点上（注意：由于当前集群只有一个节点且该节点标签为 `web`，此 Pod 可能会处于 Pending 状态）：

```bash
kubectl get pod pod-with-node-anti-affinity -o wide
```
