---
title: "探索 kubectl run 命令"
date: 2026-06-20
---

# 探索 kubectl run 命令

`kubectl run` 命令用于在 Pod 中创建并运行特定的镜像。它提供了多种选项来定制 Pod 的行为、环境和规格。

运行以下命令以查看 `kubectl run` 的可用选项：

```bash
kubectl run -h
```

你将看到以下输出：

```plaintext
在 Pod 中创建并运行特定的镜像。

示例：
# 启动一个 nginx Pod
kubectl run nginx --image=nginx

# 启动一个 hazelcast Pod 并让容器暴露端口 5701
kubectl run hazelcast --image=hazelcast/hazelcast --port=5701

# 启动一个 hazelcast Pod 并在容器中设置环境变量 "DNS_DOMAIN=cluster" 和 "POD_NAMESPACE=default"
kubectl run hazelcast --image=hazelcast/hazelcast --env="DNS_DOMAIN=cluster" --env="POD_NAMESPACE=default"

# 启动一个 hazelcast Pod 并在容器中设置标签 "app=hazelcast" 和 "env=prod"
kubectl run hazelcast --image=hazelcast/hazelcast --labels="app=hazelcast,env=prod"

# 试运行；打印相应的 API 对象而不创建它们
kubectl run nginx --image=nginx --dry-run=client

# 启动一个 nginx Pod，但使用从 JSON 解析的部分值覆盖 spec
kubectl run nginx --image=nginx --overrides='{ "apiVersion": "v1", "spec": { ... } }'

# 启动一个 busybox Pod 并保持在前台运行，如果退出则不重启
kubectl run -i -t busybox --image=busybox --restart=Never

# 使用默认命令启动 nginx Pod，但为该命令使用自定义参数 (arg1 .. argN)
kubectl run nginx --image=nginx -- <arg1> <arg2> ... <argN>

# 使用不同的命令和自定义参数启动 nginx Pod
kubectl run nginx --image=nginx --command -- <cmd> <arg1> ... <argN>
```
