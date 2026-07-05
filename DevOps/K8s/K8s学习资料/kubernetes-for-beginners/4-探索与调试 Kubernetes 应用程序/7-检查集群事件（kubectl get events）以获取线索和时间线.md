---
title: "检查集群事件（kubectl get events）以获取线索和时间线"
date: 2026-06-20
---

# 检查集群事件（kubectl get events）以获取线索和时间线

在这一步中，你将学习如何使用 `kubectl get events` 来调查集群范围内的事件，了解系统活动，并诊断 Kubernetes 环境中的问题。

首先，查看所有集群事件：

```bash
kubectl get events
```

示例输出：

```
LAST SEEN   TYPE      REASON                 OBJECT                           MESSAGE
10m         Warning   FailedCreate           deployment/debug-deployment     Failed to create pod
5m          Normal    Scheduled              pod/nginx-pod                   Successfully assigned default/nginx-pod to minikube
3m          Normal    Pulled                 pod/nginx-deployment-xxx-yyy    Container image "nginx:latest" already present on machine
```

按命名空间过滤事件：

```bash
kubectl get events -n default
```

使用更详细的事件查看选项：

```bash
# 实时监控事件
kubectl get events -w

# 按时间戳排序获取事件
kubectl get events --sort-by='.metadata.creationTimestamp'
```

创建一个自定义的事件生成场景：

```bash
cd ~/project/k8s-manifests
nano event-test-deployment.yaml
```

添加以下内容：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: event-test
spec:
  replicas: 3
  selector:
    matchLabels:
      app: event-test
  template:
    metadata:
      labels:
        app: event-test
    spec:
      containers:
        - name: nginx
          image: nginx:latest
          resources:
            limits:
              cpu: "100m"
              memory: "50Mi"
```

应用 Deployment 并检查事件：

```bash
kubectl apply -f event-test-deployment.yaml
kubectl get events
```

高级事件过滤：

```bash
# 按事件类型过滤
kubectl get events --field-selector type=Warning

# 按特定资源过滤
kubectl get events --field-selector involvedObject.kind=Deployment
```

关键的事件检查技巧：

- 查看集群范围内的事件
- 按命名空间过滤事件
- 实时监控事件
- 识别警告和错误事件
