# 创建一个损坏的 Pod

我们首先通过部署一个带有故意拼写错误的 Pod 来开始。这模拟了一个常见错误：指定了一个不存在的镜像。

这提醒我们，Kubernetes 会接受许多配置清单，但其中的问题往往在稍后才会被发现。一个 YAML 文件在语法上可能是有效的，但在操作层面却是错误的。因此，排查工作必须将「对象是否被接受」与「工作负载是否真正正确启动」区分开来。

创建一个名为 `broken-pod.yaml` 的文件：

```bash
nano broken-pod.yaml
```

粘贴以下内容。注意那个可疑的镜像标签 `wrongtag123`。

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: broken-pod
spec:
  containers:
    - name: nginx
      image: nginx:wrongtag123
```

保存并退出 (`Ctrl+X`, `Y`, `Enter`)。

部署该 Pod：

```bash
kubectl apply -f broken-pod.yaml
```

Kubernetes 接受了该文件，因为 YAML 格式是正确的。此时，「犯罪现场」尚未被发现。
