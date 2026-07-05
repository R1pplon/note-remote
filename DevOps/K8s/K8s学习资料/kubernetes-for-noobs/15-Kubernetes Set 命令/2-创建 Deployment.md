---
title: "创建 Deployment"
date: 2026-06-20
---

# 创建 Deployment

在集群运行后，使用 `nginx` 镜像创建一个名为 `hello-world` 的简单 Kubernetes 部署（Deployment）。

1. 运行以下命令以创建部署：

   ```bash
   kubectl create deployment hello-world --image=nginx
   ```

   此命令将创建一个名为 `hello-world` 的部署，其中包含一个 `nginx` 容器的副本。

2. 验证部署是否成功创建：

   ```bash
   kubectl get deployments
   ```

   检查输出，确保 `hello-world` 出现在部署列表中。
