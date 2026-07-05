# 创建没有容忍配置的 Pod

在这一步骤中，我们将创建一个没有容忍配置的 Pod，并验证它无法被调度到带有污点的节点上。

1. 创建一个名为 `pod-without-toleration.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: pod-without-toleration
spec:
  containers:
    - name: nginx
      image: nginx:latest
```

2. 应用更改：

```bash
kubectl apply -f pod-without-toleration.yaml
```

3. 验证 Pod 未被调度到带有污点的节点上：

```bash
kubectl describe pod pod-without-toleration | grep -i taint
```

输出应显示 Pod 未被调度到带有污点的节点上。
