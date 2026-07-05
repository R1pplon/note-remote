# 在 Kubernetes 服务中使用端口转发

在开始此步骤之前：

1. 如果你在步骤 3 中有任何正在运行的端口转发命令，请前往该终端并按 `Ctrl+C` 停止它
2. 请注意，我们将在本步骤中创建一个新的 Deployment 和 Service，但你无需删除之前的 Pod，因为它不会干扰我们的新资源

在本步骤中，你将学习如何将 `kubectl port-forward` 命令与 Kubernetes 服务结合使用。将端口转发到服务与转发到 Pod 不同，因为它允许你访问服务指向的任何 Pod。

1. 首先，创建一个包含多个副本的新 Deployment：

   ```bash
   kubectl create deployment nginx-service --image=nginx --replicas=3
   ```

2. 等待所有 Pod 变为就绪状态：

   ```bash
   kubectl wait --for=condition=Ready pod -l app=nginx-service
   ```

3. 为 Deployment 创建一个服务：

   ```bash
   kubectl expose deployment nginx-service --port=80 --type=ClusterIP --name=nginx-service
   ```

4. 验证服务是否已创建：

   ```bash
   kubectl get service nginx-service
   ```

   你应该会看到类似以下的输出：

   ```
   NAME           TYPE        CLUSTER-IP       EXTERNAL-IP   PORT(S)   AGE
   nginx-service  ClusterIP   10.96.123.456   <none>        80/TCP    10s
   ```

5. 使用 `kubectl port-forward` 命令将本地端口转发到服务：

   ```bash
   kubectl port-forward service/nginx-service 20000:80
   ```

6. 在新的终端中测试连接：

   ```bash
   curl http://localhost:20000
   ```

   你应该会看到 Nginx 欢迎页面的 HTML 内容。尝试多次运行此命令——你可能会注意到响应来自服务背后的不同 Pod。
