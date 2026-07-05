# 创建 Deployment

1. 在 `/home/labex/project/` 目录下创建一个名为 `my-deployment.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: my-deployment
spec:
  replicas: 3
  selector:
    matchLabels:
      app: my-app
  template:
    metadata:
      labels:
        app: my-app
    spec:
      containers:
        - name: my-app
          image: nginx:latest
          ports:
            - containerPort: 80
```

此 YAML 文件定义了一个包含 3 个副本的 Deployment，运行一个 Nginx 容器。`selector` 字段根据 `app` 标签选择由 Deployment 控制的 Pods。

2. 部署 `my-deployment` Deployment：

```bash
kubectl apply -f my-deployment.yaml
```

这将创建 `my-deployment` Deployment 及其关联的 ReplicaSets 和 Pods。

3. 验证 Deployment 是否已创建：

```bash
kubectl get deployments
```

这将显示集群中的 Deployments，包括 `my-deployment` Deployment。
