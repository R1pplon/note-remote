# 验证 Minikube 集群

作为一名初级 DevOps 工程师，你需要对 Kubernetes 集群进行全面的健康检查，以确保所有关键组件都在正常运行，并为部署做好准备。

## 任务

- 启动 Minikube Kubernetes 集群（免费用户可以跳过此任务，因为集群已经预先启动）
- 获取并显示集群中的节点列表
- 使用 `kubectl cluster-info` 验证集群的基础信息

## 要求

- 使用 Minikube 启动 Kubernetes 集群
- 确保你在 `~/project` 目录下进行操作
- 使用 `kubectl` 命令检查集群
- 集群必须至少有一个节点处于 `Ready` 状态

## 示例

预期的节点输出示例：

```
NAME       STATUS   ROLES           AGE   VERSION
minikube   Ready    control-plane   5m    v1.20.0
```

集群信息输出示例：

```
Kubernetes control plane is running at https://192.168.49.2:8443
CoreDNS is running at https://192.168.49.2:8443/api/v1/namespaces/kube-system/services/kube-dns:dns/proxy
```

## 提示

- 在运行 `kubectl` 命令之前，请记得启动 Minikube
- 使用 `kubectl get nodes` 列出集群节点
- 使用 `kubectl cluster-info` 获取集群详情
- 检查节点状态以确保集群健康
