---
title: "部署一个简单的 Web 应用程序"
date: 2026-06-20
---

# 部署一个简单的 Web 应用程序

在此步骤中，你将部署一个简单的 Web 应用程序，它会将数据存储在你步骤 1 中创建的 PersistentVolume 上。创建一个名为 `web-app.yaml` 的 YAML 文件，内容如下：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app
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
        - name: web-app
          image: nginx
          volumeMounts:
            - name: data
              mountPath: /usr/share/nginx/html/data
      volumes:
        - name: data
          persistentVolumeClaim:
            claimName: my-pvc
```

该文件创建了一个包含一个副本的 Deployment，以及一个运行 nginx 镜像的容器。`volumeMounts` 字段指定容器应将 PersistentVolume 挂载到 `/usr/share/nginx/html/data` 路径。`volumes` 字段指定容器应使用名为 `my-pvc` 的 PersistentVolumeClaim。

使用以下命令将 Deployment 应用到你的集群：

```shell
kubectl apply -f web-app.yaml
```
