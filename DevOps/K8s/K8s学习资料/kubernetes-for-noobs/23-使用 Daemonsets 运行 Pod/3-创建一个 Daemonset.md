# 创建一个 Daemonset

创建一个 DaemonSet，以便在集群中的每个节点上运行 `myapp-pod` 的副本。创建一个名为 `/home/labex/project/myapp-daemonset.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: DaemonSet
metadata:
  name: myapp-daemonset
spec:
  selector:
    matchLabels:
      app: myapp
  template:
    metadata:
      labels:
        app: myapp
    spec:
      containers:
        - name: myapp-container
          image: nginx
          ports:
            - containerPort: 80
```

此 DaemonSet 使用 `myapp-pod` 作为副本的模板，并将 `matchLabels` 选择器设置为 `app: myapp`，以确保在每个节点上创建副本。

使用以下命令创建 DaemonSet：

```shell
kubectl apply -f /home/labex/project/myapp-daemonset.yaml
```
