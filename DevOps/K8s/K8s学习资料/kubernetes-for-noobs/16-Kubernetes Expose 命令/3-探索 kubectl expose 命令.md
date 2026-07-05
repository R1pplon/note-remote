---
title: "探索 kubectl expose 命令"
date: 2026-06-20
---

# 探索 kubectl expose 命令

`kubectl expose` 命令用于创建一个新的 Kubernetes 服务，以暴露现有的资源，例如 Pod、Deployment 或 Replication Controller。它通过基于提供的资源自动创建服务，简化了网络配置。

运行以下命令以查看 `kubectl expose` 的可用选项：

```bash
kubectl expose -h
```

你将看到以下输出：

```plaintext
将资源暴露为新的 Kubernetes 服务。

通过名称查找 Deployment、Service、Replica Set、Replication Controller 或 Pod，并使用该资源的选择器作为新服务的选择器，指定端口。只有当 Deployment 或 Replica Set 的选择器可以转换为服务支持的选择器时（即选择器仅包含 matchLabels 组件），才会将其暴露为服务。请注意，如果未通过 --port 指定端口且暴露的资源具有多个端口，则所有端口都将被新服务重用。此外，如果未指定标签，新服务将重用其暴露资源的标签。

可能的资源包括（不区分大小写）：
  pod (po), service (svc), replicationcontroller (rc), deployment (deploy), replicaset (rs)

示例：
  # 为复制的 nginx 创建一个服务，该服务在端口 80 上提供服务并连接到容器上的端口 8000
  kubectl expose rc nginx --port=80 --target-port=8000

  # 为通过 "nginx-controller.yaml" 中指定的类型和名称标识的 Replication Controller 创建一个服务，该服务在端口 80 上提供服务并连接到容器上的端口 8000
  kubectl expose -f nginx-controller.yaml --port=80 --target-port=8000

  # 为 Pod valid-pod 创建一个服务，该服务在端口 444 上提供服务，名称为 "frontend"
  kubectl expose pod valid-pod --port=444 --name=frontend

  # 基于上述服务创建第二个服务，将容器端口 8443 暴露为端口 443，名称为 "nginx-https"
  kubectl expose service nginx --port=443 --target-port=8443 --name=nginx-https

  # 为复制的流媒体应用程序创建一个服务，在端口 4100 上平衡 UDP 流量，名称为 'video-stream'
  kubectl expose rc streamer --port=4100 --protocol=UDP --name=video-stream

  # 为使用 Replica Set 复制的 nginx 创建一个服务，该服务在端口 80 上提供服务并连接到容器上的端口 8000
  kubectl expose rs nginx --port=80 --target-port=8000

  # 为 nginx Deployment 创建一个服务，该服务在端口 80 上提供服务并连接到容器上的端口 8000
  kubectl expose deployment nginx --port=80 --target-port=8000
```
