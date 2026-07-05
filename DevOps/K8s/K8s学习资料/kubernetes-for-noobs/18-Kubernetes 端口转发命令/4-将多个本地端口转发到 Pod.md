# 将多个本地端口转发到 Pod

在开始此步骤之前，你需要：

1. 停止步骤 1 中的端口转发，返回该终端并按 `Ctrl+C`

在本步骤中，你将学习如何将多个本地端口转发到 Pod。我们将两个不同的本地端口转发到同一个容器端口，这在你希望为同一服务提供不同访问点时非常有用。

1. 使用以下命令设置端口转发：

   首先，获取你的 Pod 名称（如果尚未获取）：

   ```bash
   export POD_NAME=$(kubectl get pods -l app=nginx -o jsonpath='{.items[0].metadata.name}')
   echo $POD_NAME
   ```

   你应该会看到类似以下的输出：

   ```
   nginx-748c667d99-pdhzs
   ```

   现在设置端口转发，将两个本地端口（19080 和 19081）映射到容器的端口 80：

   ```bash
   # 正确的格式是：kubectl port-forward POD_NAME LOCAL_PORT:CONTAINER_PORT [LOCAL_PORT:CONTAINER_PORT ...]
   kubectl port-forward pod/$POD_NAME 19080:80 19081:80
   ```

   你应该会看到类似以下的输出：

   ```
   Forwarding from 127.0.0.1:19080 -> 80
   Forwarding from [::1]:19080 -> 80
   Forwarding from 127.0.0.1:19081 -> 80
   Forwarding from [::1]:19081 -> 80
   ```

   此命令将：

   - 本地端口 19080 转发到容器端口 80
   - 本地端口 19081 转发到容器端口 80

   两个本地端口都映射到同一个 Nginx 容器端口 80，允许你通过不同的本地端口访问相同的 Web 服务器。

2. 通过检查监听端口来验证端口转发：

   ```bash
   ss -tulnp | grep 1908
   ```

   你应该会看到类似以下的输出：

   ```
   tcp   LISTEN  0       4096         0.0.0.0:19080     0.0.0.0:*
   tcp   LISTEN  0       4096         0.0.0.0:19081     0.0.0.0:*
   ```

3. 现在你可以通过任一端口访问 Nginx 欢迎页面：

   - <http://localhost:19080>
   - <http://localhost:19081>

   ```bash
   curl http://localhost:19080
   ```

   ```bash
   curl http://localhost:19081
   ```

由于两个 URL 都转发到同一个容器端口，因此它们都会显示相同的 Nginx 欢迎页面。
