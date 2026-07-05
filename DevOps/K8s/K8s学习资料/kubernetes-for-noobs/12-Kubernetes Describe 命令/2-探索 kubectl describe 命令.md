---
title: "探索 kubectl describe 命令"
date: 2026-06-20
---

# 探索 kubectl describe 命令

`kubectl describe` 命令用于显示特定资源或资源组的详细信息。它提供了资源的配置、状态和相关事件的深入信息。

运行以下命令以查看 `kubectl describe` 的可用选项：

```bash
kubectl describe -h
```

你将看到以下输出：

```plaintext
显示特定资源或资源组的详细信息。

打印所选资源的详细描述，包括相关资源（如事件或控制器）。你可以通过名称选择一个对象、该类型的所有对象、提供名称前缀或标签选择器。例如：

  $ kubectl describe TYPE NAME_PREFIX

将首先检查 TYPE 和 NAME_PREFIX 的完全匹配。如果不存在此类资源，它将输出名称以 NAME_PREFIX 开头的每个资源的详细信息。

使用 "kubectl api-resources" 获取支持的资源的完整列表。

示例：
  # 描述一个节点
  kubectl describe nodes kubernetes-node-emt8.c.myproject.internal

  # 描述一个 Pod
  kubectl describe pods/nginx

  # 描述通过 "pod.json" 中的类型和名称标识的 Pod
  kubectl describe -f pod.json

  # 描述所有 Pod
  kubectl describe pods

  # 通过标签 name=myLabel 描述 Pod
  kubectl describe po -l name=myLabel

  # 描述由 'frontend' 副本控制器管理的所有 Pod
  # （由 rc 创建的 Pod 的名称以 rc 的名称为前缀）
  kubectl describe pods frontend
```
