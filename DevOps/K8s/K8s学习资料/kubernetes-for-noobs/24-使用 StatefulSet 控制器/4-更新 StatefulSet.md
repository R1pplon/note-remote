---
title: "更新 StatefulSet"
date: 2026-06-20
---

# 更新 StatefulSet

在 Kubernetes 中，你可以通过更新 StatefulSet 的模板来更新其 Pod。让我们更新 `statefulset.yaml` 文件，以使用 NGINX 版本 1.20.0。

将 `statefulset.yaml` 文件更新为以下内容：

```yml
apiVersion: apps/v1
kind: StatefulSet
metadata:
  name: web
spec:
  serviceName: "web"
  replicas: 3
  selector:
    matchLabels:
      app: nginx
  template:
    metadata:
      labels:
        app: nginx
    spec:
      containers:
        - name: nginx
          image: nginx:1.20.0
          ports:
            - containerPort: 80
          volumeMounts:
            - name: www
              mountPath: /usr/share/nginx/html
  volumeClaimTemplates:
    - metadata:
        name: www
      spec:
        accessModes: ["ReadWriteOnce"]
        resources:
          requests:
            storage: 1Gi
```

要更新 StatefulSet，请运行以下命令：

```shell
kubectl apply -f statefulset.yaml
```

你可以通过运行以下命令检查 StatefulSet 的状态：

```shell
kubectl get statefulsets
```

恭喜，你已成功在 Kubernetes 中更新了一个 StatefulSet！
