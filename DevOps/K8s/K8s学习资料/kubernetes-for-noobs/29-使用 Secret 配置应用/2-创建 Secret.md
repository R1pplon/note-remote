# 创建 Secret

在这一步，你将创建一个 Kubernetes Secret，其中包含一个数据库密码。

在 `/home/labex/project` 目录中创建一个名为 `my-secret.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Secret
metadata:
  name: my-secret
type: Opaque
data:
  password: dXNlcm5hbWU6cGFzc3dvcmQ=
```

在这个文件中，我们指定了 Secret 的名称 (`my-secret`)，它包含的数据类型 (`Opaque`)，以及 Base64 编码格式的实际数据。

通过运行以下命令将 Secret 应用到你的集群：

```bash
kubectl apply -f my-secret.yaml
```

通过运行以下命令验证 Secret 是否已创建：

```bash
kubectl get secrets
```

你应该看到列出了 `my-secret` Secret。
