# 创建一个 Pod

第一步是创建一个简单的 Pod。创建一个名为 `/home/labex/project/myapp-pod.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: my-pod-1
  labels:
    app: nginx
spec:
  containers:
    - name: my-container
      image: nginx
```

保存文件后，运行以下命令来创建 Pod：

```bash
kubectl apply -f /home/labex/project/myapp-pod.yaml
```

这将创建一个名为 `my-pod-1` 的 Pod，其中包含一个运行 Nginx 镜像的容器。
