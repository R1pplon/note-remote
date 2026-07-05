---
title: "创建一个 Pod"
date: 2026-06-20
---

# 创建一个 Pod

创建一个简单的 Pod，它将作为副本的模板。创建一个名为 `/home/labex/project/myapp-pod.yaml` 的文件，内容如下：

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
kubectl apply -f /home/labex/project/myapp-pod.yaml
```
