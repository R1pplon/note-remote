# 验证 Secret 在 Pod 中作为卷

在这一步，你将验证你的应用程序是否已使用来自 `my-secret` Secret 的数据库密码正确配置。

首先，运行以下命令以在运行你的应用程序的容器中打开一个 shell 会话：

```bash
kubectl exec -it secret-pod -- sh
```

进入 shell 会话后，运行以下命令以打印该值：

```bash
cat /etc/secret-volume/password
```

输出结果应该是 Secret 的值。
