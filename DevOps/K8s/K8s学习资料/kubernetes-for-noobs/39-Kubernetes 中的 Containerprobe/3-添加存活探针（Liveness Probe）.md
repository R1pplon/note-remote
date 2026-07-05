# 添加存活探针（Liveness Probe）

下一步是为 nginx 容器添加一个存活探针（liveness probe）。存活探针用于确定容器是否存活。如果探针失败，Kubernetes 将重启容器。

1. 使用以下内容更新 `/home/labex/project` 目录中的 `deployment.yaml` 文件：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: containerprobe-deployment
spec:
  replicas: 1
  selector:
    matchLabels:
      app: containerprobe
  template:
    metadata:
      labels:
        app: containerprobe
    spec:
      containers:
        - name: containerprobe
          image: nginx
          ports:
            - containerPort: 80
          livenessProbe:
            httpGet:
              path: /
              port: 80
```

此代码指定存活探针应向端口 80 的根路径发送 HTTP GET 请求。

2. 更新 Deployment：

```bash
kubectl apply -f deployment.yaml
```
