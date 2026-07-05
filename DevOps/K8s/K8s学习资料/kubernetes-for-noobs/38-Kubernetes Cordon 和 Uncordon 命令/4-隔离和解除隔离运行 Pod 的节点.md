# 隔离和解除隔离运行 Pod 的节点

在这一步骤中，我们将模拟一个场景：当一个节点上运行着 Pod 时，需要对其进行隔离和解除隔离操作。以下是具体步骤：

1. 执行以下命令进入目录 `/home/labex/project/`：

```bash
cd /home/labex/project/
```

2. 在目录 `/home/labex/project/` 中使用以下 YAML 文件创建一个名为 "deploy.yaml" 的 Deployment，并设置多个副本：

```yml
# deploy.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx-deployment
spec:
  selector:
    matchLabels:
      app: nginx
  replicas: 1
  template:
    metadata:
      labels:
        app: nginx
    spec:
      containers:
        - name: nginx
          image: nginx:1.16
          ports:
            - containerPort: 80
```

3. 使用以下命令隔离节点：

```bash
kubectl cordon minikube
```

4. 使用以下命令应用 YAML 文件：

```bash
kubectl apply -f deploy.yaml
```

5. 使用以下命令列出节点上运行的 Pod：

```bash
kubectl get pods -o wide
```

检查 Pod 是否被正确调度并启动。

6. 使用以下命令解除节点的隔离：

```bash
kubectl uncordon minikube
```

7. 使用以下命令检查节点上运行的 Pod 状态，确保它们已在解除隔离的节点上重新调度：

```bash
kubectl get pods -o wide
```
