# 创建 HorizontalPodAutoscaler

现在我们已经有了一个 Deployment，接下来可以创建一个 HorizontalPodAutoscaler 来自动扩展该 Deployment。

1. 创建一个名为 `hpa.yaml` 的 HorizontalPodAutoscaler 文件，内容如下：

```yml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: hpa-demo
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: hpa-demo
  minReplicas: 1
  maxReplicas: 10
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          averageUtilization: 1
          type: Utilization
```

此 HorizontalPodAutoscaler 指定我们希望将 `hpa-demo` Deployment 的副本数扩展为 1 到 10 个，并且目标 CPU 利用率为 50%。

2. 创建 HorizontalPodAutoscaler：

```bash
kubectl apply -f hpa.yaml
```
