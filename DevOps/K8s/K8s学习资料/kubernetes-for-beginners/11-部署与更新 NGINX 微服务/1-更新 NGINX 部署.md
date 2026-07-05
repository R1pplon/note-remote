# 更新 NGINX 部署

作为一名初级 DevOps 工程师，你的任务是更新现有的部署，以确保服务中断降至最低。

## 前置条件

在开始之前，请确保你已执行以下操作：

```bash
minikube start
kubectl apply -f ~/project/k8s-manifests/nginx-deployment.yaml
```

检查部署状态：

```bash
kubectl get deployments
```

## 任务

1. 更新 `web-app` 部署，使其使用镜像 `nginx:1.24.0-alpine`。
2. 验证更新是否通过滚动更新成功执行，并确保零停机。

## 要求

- 使用 `kubectl` 命令执行更新。
- 确认在更新过程中部署持续运行且无停机。
- 使用 `kubectl rollout status` 监控更新进度。
- 检查 Pod 镜像以验证更新后的版本。

## 提示

- 使用 `kubectl edit deployment web-app` 或 `kubectl set image` 来更新镜像。
- 使用 `kubectl rollout status` 监控滚动更新状态。
- 使用 `kubectl get pods -o jsonpath` 验证正在运行的 Pod 及其容器镜像。
