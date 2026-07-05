---
title: "探索 kubectl create 命令"
date: 2026-06-20
---

# 探索 kubectl create 命令

`kubectl create` 命令提供了多个子命令来创建 Kubernetes 资源。它帮助管理诸如命名空间（namespaces）、部署（deployments）、服务（services）、密钥（secrets）和配置映射（ConfigMaps）等资源的创建。

运行以下命令以查看可用的 `kubectl create` 子命令：

```bash
kubectl create -h
```

你将看到以下输出：

```plaintext
从文件或标准输入创建资源。

接受 JSON 和 YAML 格式。

示例：
  # 使用 pod.json 中的数据创建一个 pod
  kubectl create -f ./pod.json

  # 基于传递到标准输入的 JSON 创建一个 pod
  cat pod.json | kubectl create -f -

  # 编辑 registry.yaml 中的数据，然后使用编辑后的数据创建资源
  kubectl create -f registry.yaml --edit -o json

可用命令：
  clusterrole           创建一个集群角色
  clusterrolebinding    为特定集群角色创建集群角色绑定
  configmap             从本地文件、目录或字面值创建配置映射
  cronjob               创建具有指定名称的定时任务（cron job）
  deployment            创建具有指定名称的部署
  ingress               创建具有指定名称的入口（ingress）
  job                   创建具有指定名称的任务（job）
  namespace             创建具有指定名称的命名空间
  poddisruptionbudget   创建具有指定名称的 Pod 中断预算
  priorityclass         创建具有指定名称的优先级类
  quota                 创建具有指定名称的资源配额
  role                  创建具有单一规则的角色
  rolebinding           为特定角色或集群角色创建角色绑定
  secret                使用指定的子命令创建密钥
  service               使用指定的子命令创建服务
  serviceaccount        创建具有指定名称的服务账户
  token                 请求服务账户令牌
```

查看可用的子命令及其描述，以了解如何使用 `kubectl create`。
