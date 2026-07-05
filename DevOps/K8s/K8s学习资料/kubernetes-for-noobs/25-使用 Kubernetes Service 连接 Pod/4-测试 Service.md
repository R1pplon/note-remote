# 测试 Service

第三步是通过从另一个 Pod 访问 Service 来测试其功能。创建一个名为 `/home/labex/project/test-pod-1.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: test-pod-1
spec:
  containers:
    - name: my-container
      image: nginx
      command:
        - sleep
        - "3600"
```

保存文件后，运行以下命令来创建测试 Pod：

```bash
kubectl apply -f /home/labex/project/test-pod-1.yaml
```

这将创建一个名为 `test-pod-1` 的 Pod，其中包含一个运行 Busybox 镜像的容器。

接下来，你需要进入容器并使用 `curl` 访问 Service。运行以下命令以进入容器：

```bash
kubectl exec -it test-pod-1 -- sh
```

这将打开容器内的 shell。在 shell 中，运行以下命令以访问 Service：

```bash
curl http://my-service
```

这将返回默认的 Nginx 页面，表明 Service 工作正常。
