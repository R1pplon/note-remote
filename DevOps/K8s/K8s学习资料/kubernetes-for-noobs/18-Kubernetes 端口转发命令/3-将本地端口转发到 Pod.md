---
title: "将本地端口转发到 Pod"
date: 2026-06-20
---

# 将本地端口转发到 Pod

在这一步骤中，你将学习如何将本地端口转发到 Pod 上的端口。这对于调试应用程序或访问未在集群外部暴露的服务非常有用。

关于终端管理的注意事项：

- `kubectl port-forward` 命令将在你的终端中持续运行，并阻止其他操作
- 你需要打开一个新的终端窗口以在端口转发活动时运行其他命令
- 要随时停止端口转发，可以在运行该命令的终端中按 `Ctrl+C`

1. 首先创建一个包含一个副本和 Nginx 容器的 Deployment：

   ```bash
   kubectl create deployment nginx --image=nginx --replicas=1
   ```

   此命令创建了一个运行官方 Nginx 容器镜像的 Deployment。

2. 等待 Pod 变为就绪状态：

   ```bash
   kubectl wait --for=condition=Ready pod -l app=nginx
   ```

   获取我们将用于端口转发的 Pod 名称：

   ```bash
   kubectl get pod -l app=nginx
   ```

   你应该会看到类似以下的输出：

   ```
   NAME                     READY   STATUS    RESTARTS   AGE
   nginx-66b6c48dd5-abcd1   1/1     Running   0          30s
   ```

3. 使用 `kubectl port-forward` 命令将本地端口转发到 Pod：

   首先，获取你的 Pod 名称：

   ```bash
   export POD_NAME=$(kubectl get pods -l app=nginx -o jsonpath='{.items[0].metadata.name}')
   echo $POD_NAME
   ```

   你应该会看到类似以下的输出：

   ```
   nginx-748c667d99-pdhzs
   ```

   现在使用 Pod 名称设置端口转发：

   ```bash
   kubectl port-forward $POD_NAME 19000:80
   ```

   你应该会看到类似以下的输出：

   ```
   Forwarding from 127.0.0.1:19000 -> 80
   Forwarding from [::1]:19000 -> 80
   ```

4. 打开一个新的终端窗口（因为端口转发会在当前终端中持续运行）并验证端口转发：

   ```bash
   curl http://localhost:19000
   ```

   你应该会看到 Nginx 欢迎页面的 HTML 内容。

   你还可以打开浏览器并访问 `http://localhost:19000` 以查看渲染后的页面。
