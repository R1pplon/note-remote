# 创建一个 Pod

**Pod** 是 Kubernetes 中最小的可部署单元，代表一个或多个一起运行的容器。在此步骤中，我们将创建一个运行 Nginx Web 服务器的 Pod。

1. **创建 Pod**：

   运行以下命令以创建一个名为 `nginx-pod` 的 Pod：

   ```bash
   kubectl run nginx-pod --image=nginx
   ```

   - `--image` 选项指定要使用的容器镜像。这里我们使用官方的 Nginx 镜像。

2. **验证 Pod**：

   检查 Pod 是否正在运行：

   ```bash
   kubectl get pods
   ```

   - 在输出中查找 `nginx-pod`。
   - 当 Pod 准备就绪时，`STATUS` 列应显示为 `Running`。

如果 Pod 状态显示为 `Pending`，Kubernetes 可能仍在拉取容器镜像。请稍等片刻，然后重新运行 `kubectl get pods`。
