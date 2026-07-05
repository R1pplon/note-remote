# 探索 Kubernetes 集群详情

作为 TechCorp 的新入职云计算基础设施实习生，你需要对 Kubernetes 集群进行全面的健康检查，以确保其能够正常承载即将开始的部署工作。

## 任务

- 使用适当的 `kubectl` 命令获取完整的集群信息
- 列出 Kubernetes 集群中的所有节点
- 验证集群中每个节点的状态

## 要求

- 所有操作必须使用 `kubectl` 命令完成
- 在 `~/project` 目录下执行所有任务
- 在执行任何命令前，确保 Minikube 集群已启动并运行
- 使用标准的 `kubectl` 子命令来收集集群信息

## 示例

列出节点的输出示例：

```
NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   10m   v1.23.3
```

集群信息的输出示例：

```
Kubernetes control plane is running at https://192.168.49.2:8443
CoreDNS is running at https://192.168.49.2:8443/api/v1/namespaces/kube-system/services/kube-dns:dns/proxy
```

## 提示

- 记得使用 `kubectl` 命令与 Kubernetes 集群进行交互
- 在运行详细命令之前，先检查集群的整体状态
- 可以为 `kubectl` 命令添加 `--help` 参数来获取更多帮助信息
- 执行任务前，请务必确认 Minikube 集群正在运行
