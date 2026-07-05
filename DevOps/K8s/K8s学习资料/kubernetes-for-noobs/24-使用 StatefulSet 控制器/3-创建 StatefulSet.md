# 创建 StatefulSet

创建一个名为 `statefulset.yaml` 的文件，内容如下：

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
          image: nginx:1.19.7
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

在此文件中，我们定义了一个名为 `web` 的 StatefulSet，它会创建三个 NGINX Pod 的副本。我们还定义了一个名为 `web` 的服务，使用 `app: nginx` 标签选择 NGINX Pod。最后，我们为 NGINX Pod 的数据定义了一个持久卷声明模板。

要创建 StatefulSet，请运行以下命令：

```shell
kubectl apply -f statefulset.yaml
```

你可以通过运行以下命令检查 StatefulSet 的状态：

```shell
kubectl get statefulsets
```

一旦 StatefulSet 运行起来，你可以通过运行以下命令访问 NGINX Pod：

```shell
kubectl get pods
kubectl exec -it web-0 -- /bin/bash
```

将 `web-0` 替换为 StatefulSet 创建的任何 NGINX Pod 的名称。

恭喜，你已成功在 Kubernetes 中创建了一个 StatefulSet！
