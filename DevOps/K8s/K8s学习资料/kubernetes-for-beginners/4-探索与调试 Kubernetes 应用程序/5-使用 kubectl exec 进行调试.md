---
title: "使用 kubectl exec 进行调试"
date: 2026-06-20
---

# 使用 kubectl exec 进行调试

在这一步中，你将学习如何使用 `kubectl exec` 在 Kubernetes Pod 内运行命令，这对于调试和调查容器环境至关重要。

首先，验证可用的 Pod：

```bash
kubectl get pods
```

示例输出：

```
NAME                                READY   STATUS    RESTARTS   AGE
nginx-pod                           1/1     Running   0          30m
nginx-deployment-xxx-yyy            1/1     Running   0          30m
nginx-deployment-xxx-zzz            1/1     Running   0          30m
nginx-deployment-xxx-www            1/1     Running   0          30m
```

在 `nginx-pod` 中运行一个交互式 shell：

```bash
kubectl exec -it nginx-pod -- /bin/bash
```

在 Pod 内的示例交互：

```bash
# 检查 nginx 配置
cat /etc/nginx/nginx.conf

# 验证已安装的包
apt update && apt list --installed

# 退出 Pod 的 shell
exit
```

确保使用 `exit` 退出交互式 shell 以返回到 shell 提示符。

无需进入交互式 shell 即可运行特定命令：

```bash
# 检查 nginx 版本
kubectl exec nginx-pod -- nginx -v

# 列出 web 根目录中的文件
kubectl exec nginx-pod -- ls /usr/share/nginx/html
```

对于 Deployment 中的 Pod，选择一个特定的 Pod：

```bash
# 从 Deployment 中获取一个 Pod 名称
POD_NAME=$(kubectl get pods -l app=nginx | grep nginx-deployment | head -n 1 | awk '{print $1}')

# 在 Deployment 的 Pod 中运行命令
kubectl exec -it $POD_NAME -- /bin/bash
```

关键的 `kubectl exec` 技巧：

- 运行交互式 shell
- 执行特定命令
- 调查 Pod 内部
- 调试容器配置
