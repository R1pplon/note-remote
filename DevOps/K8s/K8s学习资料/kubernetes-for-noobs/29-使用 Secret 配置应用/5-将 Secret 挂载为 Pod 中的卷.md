---
title: "将 Secret 挂载为 Pod 中的卷"
date: 2026-06-20
---

# 将 Secret 挂载为 Pod 中的卷

现在我们已经创建了 Secret，我们可以将其挂载为 Pod 中的卷。我们将创建一个简单的 Pod，它从挂载的卷中读取 Secret 值并将其输出到控制台。

在 `/home/labex/project` 目录中创建一个名为 `pod.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Pod
metadata:
  name: secret-pod
spec:
  containers:
    - name: secret-container
      image: nginx
      volumeMounts:
        - name: secret-volume
          mountPath: /etc/secret-volume
  volumes:
    - name: secret-volume
      secret:
        secretName: my-secret
```

应用 Pod 配置：

```bash
kubectl apply -f pod.yaml
```
