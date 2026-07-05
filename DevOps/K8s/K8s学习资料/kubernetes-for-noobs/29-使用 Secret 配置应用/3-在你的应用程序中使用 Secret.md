# 在你的应用程序中使用 Secret

在这一步，你将修改你的应用程序，以使用 `my-secret` Secret 来检索数据库密码。

在 `/home/labex/project` 目录中创建一个名为 `my-app.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: my-app
spec:
  replicas: 1
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
          env:
            - name: DATABASE_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: my-secret
                  key: password
```

在这个文件中，我们指定了 Deployment 的名称 (`my-app`)，要使用的镜像 (`my-image`)，以及要设置的环境变量 (`DATABASE_PASSWORD`)。我们还使用 `secretKeyRef` 从 `my-secret` Secret 中检索 `password` 键。

通过运行以下命令将 Deployment 应用到你的集群：

```bash
kubectl apply -f my-app.yaml
```

通过运行以下命令验证 Deployment 是否已创建：

```bash
kubectl get deployments
```

你应该看到列出了 `my-app` Deployment。
