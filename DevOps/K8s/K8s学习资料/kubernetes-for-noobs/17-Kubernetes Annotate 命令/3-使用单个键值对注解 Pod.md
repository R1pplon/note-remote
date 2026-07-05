# 使用单个键值对注解 Pod

在这一步骤中，我们将从一个简单的示例开始，使用 `kubectl annotate` 命令为 Pod 添加一个键值对的注解。

1. 在 `/home/labex/project` 目录下创建一个名为 `pod.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: my-pod
spec:
  containers:
    - name: nginx
      image: nginx
```

使用以下命令创建 Pod：

```bash
kubectl apply -f pod.yaml
```

2. 使用 `kubectl annotate` 命令为 Pod 添加注解：

```bash
kubectl annotate pod my-pod my-annotation-key=my-annotation-value
```

3. 验证注解是否已添加到 Pod：

```bash
kubectl describe pod my-pod | grep Annotations
```

你应该会在输出中看到注解 `my-annotation-key`，其值为 `my-annotation-value`。
