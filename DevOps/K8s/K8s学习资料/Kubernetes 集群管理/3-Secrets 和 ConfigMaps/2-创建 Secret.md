# 创建 Secret

现在，让我们存储一些敏感信息。**Secret** 的工作方式与 ConfigMap 类似，但 Kubernetes 会对其进行特殊处理（在存储时会对数据进行混淆）。

这里需要明确一点：Secret 比将密码硬编码到清单或镜像中更安全，但 base64 编码并非真正的加密。在生产环境中，Secret 的处理通常会结合静态加密（encryption at rest）、更严格的 RBAC 以及外部密钥管理系统。

创建一个名为 `app-secret` 的 Secret 来存储一个虚拟密码：

```bash
kubectl create secret generic app-secret --from-literal=password=SuperSecretPass123
```

验证它是否存在：

```bash
kubectl get secret app-secret
```

如果你查看详细信息，将无法直接看到密码。它会被隐藏（编码）。

```bash
kubectl get secret app-secret -o yaml
```

注意 `password` 字段是一串乱码。这是 base64 编码，旨在防止「肩窥」或在日志中意外显示。
