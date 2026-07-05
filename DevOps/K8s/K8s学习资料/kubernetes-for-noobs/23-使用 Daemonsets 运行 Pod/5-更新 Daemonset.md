---
title: "更新 Daemonset"
date: 2026-06-20
---

# 更新 Daemonset

更新 DaemonSet 以更改 `myapp-container` 使用的镜像。创建一个名为 `/home/labex/project/myapp-daemonset-update.yaml` 的文件，内容如下：

```yml
apiVersion: apps/v1
kind: DaemonSet
metadata:
  name: myapp-daemonset
spec:
  selector:
    matchLabels:
      app: myapp
  template:
    metadata:
      labels:
        app: myapp
    spec:
      containers:
        - name: myapp-container
          image: busybox
          command: ["sleep", "3600"]
```

此更新后的 DaemonSet 将 `myapp-container` 使用的镜像更改为 `busybox`，并将命令设置为 `sleep 3600`。

使用以下命令更新 DaemonSet：

```shell
kubectl apply -f /home/labex/project/myapp-daemonset-update.yaml
```

验证 DaemonSet 是否已更新，并确保 `myapp-pod` 的副本正在使用新镜像运行。使用以下命令列出由 DaemonSet 创建的 Pod：

```shell
kubectl get pods -l app=myapp
```

你应该会看到使用更新后的镜像创建的新 Pod。
