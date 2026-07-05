---
title: "将 NGINX 部署扩展到六个副本"
date: 2026-06-20
---

# 将 NGINX 部署扩展到六个副本

你的电商初创公司需要一个健壮的 Web 基础设施，能够快速适应不断变化的流量需求。作为一名初级 DevOps 工程师，你将通过配置具有精确副本管理的 NGINX 部署来展示你的 Kubernetes 扩展技能。

## 前提条件

在开始之前，请确保你具备以下条件：

```bash
# 启动 Minikube 集群
minikube start
# 应用初始部署文件
kubectl apply -f ~/project/k8s-manifests/nginx-deployment.yaml
```

检查部署状态：

```bash
kubectl get deployments
```

## 任务

- 将现有的 NGINX 部署扩展到正好 6 个副本
- 验证部署确实有 6 个正在运行的 Pod

## 要求

- 使用 `kubectl scale` 命令来修改部署
- 确保部署名称保持为 `nginx-deployment`
- 部署必须正好有 6 个副本
- 在 `~/project/k8s-manifests` 目录下工作
- 使用 Minikube Kubernetes 集群进行扩展

## 示例

成功的扩展输出示例：

```
NAME               READY   UP-TO-DATE   AVAILABLE   AGE
nginx-deployment   6/6     6            6           5m
```

## 提示

- 记住 `kubectl scale deployment` 命令的语法
- 扩展后检查部署状态
- 扩展后验证 Pod 的数量
