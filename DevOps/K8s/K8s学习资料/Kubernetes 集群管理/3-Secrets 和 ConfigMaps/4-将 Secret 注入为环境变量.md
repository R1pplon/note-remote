---
title: "将 Secret 注入为环境变量"
date: 2026-06-20
---

# 将 Secret 注入为环境变量

现在处理 Secret。传递密码的一种常见方法是通过**环境变量**。

这种方法很方便，但也存在权衡。环境变量易于应用程序读取，但可能会通过诊断命令或进程检查泄露。Kubernetes 同时支持基于文件和基于环境变量的注入，因为不同的应用程序和风险配置需要不同的方法。

创建一个名为 `pod-secret.yaml` 的文件：

```bash
nano pod-secret.yaml
```

粘贴以下内容。此 Pod 从 `app-secret` 中获取 `password`，并将其分配给名为 `APP_PASSWORD` 的环境变量。

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: secret-pod
spec:
  containers:
    - name: busybox
      image: busybox
      command: ["sleep", "3600"]
      env:
        - name: APP_PASSWORD
          valueFrom:
            secretKeyRef:
              name: app-secret
              key: password
```

保存并退出（`Ctrl+X`，`Y`，`Enter`）。

应用它：

```bash
kubectl apply -f pod-secret.yaml
```

运行后，让我们检查容器内的环境变量：

```bash
kubectl exec secret-pod -- env | grep APP_PASSWORD
```

你应该能看到 `APP_PASSWORD=SuperSecretPass123`。

这里的神奇之处在于，Pod 定义中并不包含密码。密码存储在「保险箱」（Secret）中，而 Pod 只是拥有一个在运行时打开它的密钥。
