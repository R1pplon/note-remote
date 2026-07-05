---
title: "创建 Pod"
date: 2026-06-20
---

# 创建 Pod

现在你已经有了蓝图（`nginx-pod.yaml`），是时候将其交给施工团队——Kubernetes 了。

我们使用 `kubectl apply` 命令来完成此操作。该命令告诉 Kubernetes 配置：“这是我想要的期望状态。请将其实现。”

**期望状态**（desired state）是 Kubernetes 的核心理念之一。你不需要告诉系统执行每一个底层的步骤，而是声明你想要的结果，Kubernetes 控制器会不断工作，直到现实与该声明相符。

执行以下命令来创建你的 Pod：

```bash
kubectl apply -f nginx-pod.yaml
```

你应该会看到一条确认消息：

```plaintext
pod/nginx-pod created
```

当你运行此命令时，你已将蓝图发送到了 Kubernetes API 服务器（控制平面）。随后，调度器（Scheduler）为你的 Pod 找到了一个合适的节点，该节点上的 Kubelet 开始下载 Nginx 镜像并运行它。
