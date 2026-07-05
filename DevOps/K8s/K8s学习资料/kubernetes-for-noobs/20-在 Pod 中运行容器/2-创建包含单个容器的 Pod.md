# 创建包含单个容器的 Pod

第一步是创建一个包含单个容器的 Pod。为此，你需要创建一个 YAML 文件来定义 Pod 及其容器。

```yml
apiVersion: v1
kind: Pod
metadata:
  name: my-pod-1
spec:
  containers:
    - name: my-container
      image: nginx
```

将上述代码保存到名为 `/home/labex/project/pod-single-container.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f /home/labex/project/pod-single-container.yaml
```

此命令将创建一个名为 `my-pod-1` 的 Pod，其中包含一个名为 `my-container` 的容器，该容器运行 Nginx 镜像。
