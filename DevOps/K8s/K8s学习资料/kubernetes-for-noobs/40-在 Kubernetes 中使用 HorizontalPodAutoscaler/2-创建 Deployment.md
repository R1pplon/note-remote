# 创建 Deployment

首先，我们需要创建一个 Deployment，以便对其应用 HorizontalPodAutoscaler。

1. 创建一个名为 `deployment.yaml` 的部署文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: hpa-demo
spec:
  replicas: 1
  selector:
    matchLabels:
      app: hpa-demo
  template:
    metadata:
      labels:
        app: hpa-demo
    spec:
      containers:
        - name: hpa-demo
          image: nginx
          resources:
            limits:
              cpu: "1"
              memory: 512Mi
            requests:
              cpu: "0.5"
              memory: 256Mi
---
apiVersion: v1
kind: Service
metadata:
  name: hpa-demo
spec:
  selector:
    app: hpa-demo
  ports:
    - name: http
      port: 80
      targetPort: 80
```

此 Deployment 指定了一个 Nginx 容器的单个副本，并为 CPU 和内存设置了资源限制和请求。

2. 创建 Deployment：

```bash
kubectl apply -f deployment.yaml
```
