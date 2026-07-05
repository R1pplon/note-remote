# 将 ConfigMap 挂载为文件

现在，让我们教导 Pod 如何读取公告板。

使用 ConfigMap 最常见的方法是将其**挂载**为文件。它会像磁盘上的普通文件一样出现在容器内部。

当应用程序期望在特定路径下找到配置文件时，这种基于文件的方法非常有用。Kubernetes 将 ConfigMap 数据映射到容器文件系统中，以便应用程序可以继续使用其常规的文件读取方式。

创建一个名为 `pod-config.yaml` 的文件：

```bash
nano pod-config.yaml
```

粘贴以下内容。此 Pod 将 `nginx-config` 映射挂载到 `/etc/nginx/nginx.conf`。

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: config-pod
spec:
  containers:
    - name: nginx
      image: nginx:alpine
      volumeMounts:
        - name: config-volume
          mountPath: /etc/nginx/nginx.conf
          subPath: nginx.conf
  volumes:
    - name: config-volume
      configMap:
        name: nginx-config
```

保存并退出（`Ctrl+X`，`Y`，`Enter`）。

应用它：

```bash
kubectl apply -f pod-config.yaml
```

等待几秒钟让其启动。现在，让我们查看容器内部，看看文件是否在那里：

```bash
kubectl exec config-pod -- cat /etc/nginx/nginx.conf
```

你应该能看到你的自定义配置！Pod 本质上是「读取了公告板」并使用了该文件。
