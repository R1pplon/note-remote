# 部署 Nginx Pod

作为一名初入职场的云工程师，你的任务是展示在本地 Kubernetes 环境中部署基础 Web 服务的能力。这个挑战将测试你使用 Minikube 创建和管理简单 Pod 的技能。

## 任务

- 使用 `nginx:latest` 镜像创建一个名为 `web-server` 的 Pod
- 验证该 Pod 是否在默认命名空间（default namespace）中运行
- 确保 Pod 已成功部署并准备好提供 Web 内容

## 要求

- 使用 `kubectl` 创建 Pod
- Pod 的名称必须准确命名为 `web-server`
- Pod 必须使用 `nginx:latest` 镜像
- 将 Pod 部署在默认命名空间中
- 确保 Pod 处于 `Running` 状态
- 在 `~/project` 目录下进行操作

## 示例

成功部署 Pod 的示例如下：

```
NAME        READY   STATUS    RESTARTS   AGE
web-server  1/1     Running   0          30s
```

## 提示

- 使用 `minikube start` 启动 Kubernetes 集群
- 你可以通过两种方式创建 Pod：
  1. 使用 `kubectl run` 命令式指令（研究所需的参数）
  2. 使用 YAML 文件：
     - 研究 Pod YAML 定义的基础结构
     - 记得包含 apiVersion、kind、metadata 和 spec 部分
     - 确保 Pod 的名称与要求完全一致
     - 考虑需要包含哪些容器设置
- 使用 `kubectl get pods` 检查 Pod 状态
- 如果遇到问题，使用 `kubectl describe pod web-server` 获取更多信息
- 在创建 Pod 之前，请确保 Minikube 正在运行
