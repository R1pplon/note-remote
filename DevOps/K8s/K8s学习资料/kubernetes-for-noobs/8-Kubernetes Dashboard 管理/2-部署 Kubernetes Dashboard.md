---
title: "部署 Kubernetes Dashboard"
date: 2026-06-20
---

# 部署 Kubernetes Dashboard

Kubernetes Dashboard 默认不包含在集群中。使用官方的部署 YAML 文件来部署它。

1. 运行以下命令以部署 Dashboard：

   ```bash
   kubectl apply -f https://raw.githubusercontent.com/kubernetes/dashboard/v2.3.1/aio/deploy/recommended.yaml
   ```

   此命令会为 Dashboard 创建所有必要的资源，包括 Deployment、Service 和基于角色的访问控制（RBAC）设置。

2. 验证 Dashboard 是否正在运行：

   ```bash
   kubectl get pods -n kubernetes-dashboard
   ```

   查找名称为 `kubernetes-dashboard` 的 Pod，并确保其状态为 `Running`。

3. 验证 Dashboard 的命名空间：

   ```bash
   kubectl get ns | grep kubernetes-dashboard
   ```

   命名空间 `kubernetes-dashboard` 应该存在。
