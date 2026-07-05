# 创建 Deployment 并扩展副本

**Deployment** 管理一组 Pod 并确保它们按预期运行。它对于扩展和更新应用程序非常有用。

1. **创建 Deployment**：

   运行以下命令以创建一个名为 `nginx-deployment` 的 Deployment：

   ```bash
   kubectl create deployment nginx-deployment --image=nginx
   ```

   - `--image` 选项指定要使用的容器镜像。

2. **将 Deployment 扩展为 3 个副本**：

   由于 `--replicas` 标志已被弃用，我们将改用 `kubectl scale` 来扩展 Deployment。

   使用 `kubectl scale` 命令调整副本数量：

   ```bash
   kubectl scale deployment nginx-deployment --replicas=3
   ```

   - 这将确保三个 Pod 作为 Deployment 的一部分运行。

3. **验证 Deployment 及其副本**：

   检查 Deployment 和 Pod 的状态：

   ```bash
   kubectl get deployments
   kubectl get pods
   ```

   - 确保 Deployment 在 `READY` 列下显示 3 个副本。
   - 验证 `kubectl get pods` 的输出中列出了三个 Pod。

如果某个 Pod 未处于 `Running` 状态，可能是由于集群资源不足。可以使用以下命令检查 Pod 事件：

```bash
kubectl describe pod <pod-name>
```
