# 部署 Web 应用程序

在这一步骤中，你将在 `webapp` 命名空间中部署一个简单的 Web 应用程序。

创建一个名为 `web-app.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app
  namespace: webapp
spec:
  replicas: 1
  selector:
    matchLabels:
      app: web-app
  template:
    metadata:
      labels:
        app: web-app
    spec:
      containers:
        - name: nginx
          image: nginx:latest
          ports:
            - containerPort: 80
```

该文件创建了一个 Deployment，其中包含一个运行最新版本 Nginx Web 服务器的容器副本。

使用以下命令将 Deployment 应用到你的集群中：

```shell
kubectl apply -f web-app.yaml
```

使用以下命令验证 Web 应用程序是否在 `webapp` 命名空间中运行：

```shell
kubectl get pods -n webapp
```

你应该能在 `webapp` 命名空间的 Pod 列表中看到 `web-app` Pod。
