---
title: "查看 Pod 中特定容器的日志"
date: 2026-06-20
---

# 查看 Pod 中特定容器的日志

在本步骤中，你将学习如何查看运行在 Pod 中的特定容器的日志。

1. 创建一个包含两个容器（Nginx 和 BusyBox）的 Pod：

   ```bash
   cat << EOF | kubectl apply -f -
   apiVersion: v1
   kind: Pod
   metadata:
     name: nginx-busybox
   spec:
     containers:
     - name: nginx
       image: nginx
     - name: busybox
       image: busybox
       command:
         - sleep
         - "3600"
   EOF
   ```

2. 等待 Pod 变为就绪状态：

   ```bash
   kubectl wait --for=condition=Ready pod nginx-busybox
   ```

3. 使用 `kubectl logs` 命令查看 BusyBox 容器的日志：

   ```bash
   kubectl logs nginx-busybox -c busybox
   ```
