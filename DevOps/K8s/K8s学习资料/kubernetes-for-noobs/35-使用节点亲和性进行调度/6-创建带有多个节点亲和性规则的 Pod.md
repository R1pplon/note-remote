# 创建带有多个节点亲和性规则的 Pod

在此步骤中，我们将创建一个带有多个节点亲和性规则的 Pod，以确保它被调度到标签同时满足所有规则的节点上。

1. 在 `/home/labex/project` 目录下创建一个名为 `pod-with-multiple-node-affinity.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: pod-with-multiple-node-affinity
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
          - matchExpressions:
              - key: disktype
                operator: In
                values:
                  - ssd
```

2. 应用更改：

```bash
kubectl apply -f pod-with-multiple-node-affinity.yaml
```

3. 验证 Pod 是否被调度到同时具有 `type=web` 和 `disktype=ssd` 标签的节点上：

```bash
kubectl get pod pod-with-multiple-node-affinity -o wide
```
