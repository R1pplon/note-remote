---
title: "暴露 Web 应用程序"
date: 2026-06-20
---

# 暴露 Web 应用程序

在这一步骤中，你将使用 Kubernetes Service 将 Web 应用程序暴露给外部。

创建一个名为 `web-app-service.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Service
metadata:
  name: web-app
  namespace: webapp
spec:
  selector:
    app: web-app
  ports:
    - name: http
      port: 80
      targetPort: 80
  type: ClusterIP
```

该文件创建了一个 Service，使用 ClusterIP 将 Web 应用程序暴露给集群。

使用以下命令将 Service 应用到你的集群中：

```shell
kubectl apply -f web-app-service.yaml
```

使用以下命令验证 Service 是否在 `webapp` 命名空间中运行：

```shell
kubectl get services -n webapp
```

你应该能在 `webapp` 命名空间的 Service 列表中看到 `web-app` Service。
