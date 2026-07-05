---
title: "显示容器的 CPU 和内存使用情况"
date: 2026-06-20
---

# 显示容器的 CPU 和内存使用情况

要显示 Pod 中运行的容器的 CPU 和内存使用情况，我们将再次使用 `kubectl top` 命令。

创建一个简单的 Pod 作为副本的模板。在 `/home/labex/project/` 目录下创建一个名为 `myapp-pod.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: myapp-pod
spec:
  containers:
    - name: myapp-container
      image: nginx
      ports:
        - containerPort: 80
```

使用以下命令创建 Pod：

```shell
kubectl apply -f myapp-pod.yaml
```

然后，使用以下命令显示 Pod 中特定容器的 CPU 和内存使用情况：

```bash
kubectl top pod myapp-pod --namespace=default --containers=true
```

此命令将显示指定 Pod 中指定容器的当前 CPU 和内存使用统计信息。
