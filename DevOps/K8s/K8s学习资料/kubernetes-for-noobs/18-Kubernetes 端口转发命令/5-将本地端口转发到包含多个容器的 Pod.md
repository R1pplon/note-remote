---
title: "将本地端口转发到包含多个容器的 Pod"
date: 2026-06-20
---

# 将本地端口转发到包含多个容器的 Pod

在开始此步骤之前：

1. 如果你在之前的步骤中有任何正在运行的端口转发命令，请前往这些终端并按 `Ctrl+C` 停止它们
2. 我们将从一个新的多容器 Pod 设置重新开始

在本步骤中，你将学习如何将本地端口转发到包含多个容器的 Pod 中的特定容器。这是在微服务架构中使用 sidecar（边车）时的常见场景。

1. 首先，清理之前的资源：

   ```bash
   kubectl delete deployment nginx
   ```

2. 创建一个包含两个容器（Nginx 和 BusyBox）的 Pod：

   ```bash
   cat << EOF | kubectl apply -f -
   apiVersion: v1
   kind: Pod
   metadata:
     name: nginx-busybox
     labels:
       app: nginx-multi
   spec:
     containers:
     - name: nginx
       image: nginx
       ports:
       - containerPort: 80
     - name: busybox
       image: busybox
       command: ['sh', '-c', 'while true; do sleep 3600; done']
   EOF
   ```

3. 等待 Pod 变为就绪状态：

   ```bash
   kubectl wait --for=condition=Ready pod/nginx-busybox
   ```

4. 验证两个容器是否都在运行：

   ```bash
   kubectl get pod nginx-busybox -o wide
   ```

   你应该在 `READY` 列下看到 `2/2`。

5. 使用 `kubectl port-forward` 命令将本地端口转发到 Nginx 容器：

   ```bash
   kubectl port-forward pod/nginx-busybox 19001:80
   ```

6. 在新的终端中验证连接：

   ```bash
   curl http://localhost:19001
   ```

   你应该会看到 Nginx 欢迎页面的 HTML 内容。
