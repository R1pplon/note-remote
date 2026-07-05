# 创建包含多个容器的 Pod

第二步是创建一个包含多个容器的 Pod。为此，你需要修改之前的 YAML 文件以添加另一个容器。

```yml
apiVersion: v1
kind: Pod
metadata:
  name: my-pod-2
spec:
  containers:
    - name: my-container
      image: nginx
    - name: my-sidecar
      image: busybox
      command: ["sh", "-c", "echo Hello from the sidecar! && sleep 3600"]
```

将上述代码保存到名为 `/home/labex/project/pod-multiple-containers.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f /home/labex/project/pod-multiple-containers.yaml
```

此命令将创建一个名为 `my-pod-2` 的 Pod，其中包含两个容器。第一个容器运行 Nginx 镜像，第二个容器运行 BusyBox 镜像并向控制台打印一条消息。
