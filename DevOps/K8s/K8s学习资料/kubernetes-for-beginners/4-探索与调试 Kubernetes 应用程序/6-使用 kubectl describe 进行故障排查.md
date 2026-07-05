# 使用 kubectl describe 进行故障排查

在这一步中，你将学习如何使用 `kubectl describe` 来诊断和排查 Kubernetes 资源问题，提供有关 Pod、Deployment 和集群组件状态的详细信息。

首先，让我们创建一个有问题的 Deployment 来演示调试过程：

```bash
cd ~/project/k8s-manifests
nano problematic-deployment.yaml
```

添加以下内容：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: debug-deployment
spec:
  replicas: 2
  selector:
    matchLabels:
      app: debug
  template:
    metadata:
      labels:
        app: debug
    spec:
      containers:
        - name: debug-container
          image: non-existent-image:latest
          ports:
            - containerPort: 80
```

应用该 Deployment：

```bash
kubectl apply -f problematic-deployment.yaml
```

现在，使用 `kubectl describe` 来调查该 Deployment：

```bash
kubectl describe deployment debug-deployment
```

示例输出：

```
Name:                   debug-deployment
Namespace:              default
CreationTimestamp:      [timestamp]
Labels:                 <none>
Annotations:            deployment.kubernetes.io/revision: 1
Selector:               app=debug
Replicas:               2 desired | 0 available | 2 total | 2 unavailable
StrategyType:           RollingUpdate
MinReadySeconds:        0
RollingUpdateStrategy:  25% max unavailable, 25% max surge
Conditions:
  Type           Status   Reason
  ----           ------   ------
  Available      False    MinimumReplicasUnavailable
  Progressing    False    ProgressDeadlineExceeded
OldReplicaSets:  <none>
NewReplicaSet:   debug-deployment-xxx (2/2 replicas created)
Events:
  Type     Reason                    Age   From                   Message
  ----     ------                    ----  ----                   -------
  Warning  FailedCreate              1m    deployment-controller  Failed to create pod
  Normal   ScalingReplicaSet         1m    deployment-controller  Scaled up replica set
```

描述 Pod 以获取更多详细信息：

```bash
kubectl describe pods -l app=debug
```

示例输出：

```
Name:           debug-deployment-xxx-yyy
Namespace:      default
Priority:       0
Node:           minikube/172.17.0.2
Start Time:     [timestamp]
Labels:         app=debug
Annotations:    <none>
Status:         Pending
Conditions:
  Type           Status
  Initialized    True
  Ready          False
  PodScheduled   True
Events:
  Type     Reason                  Age   From               Message
  ----     ------                  ----  ----               -------
  Warning  FailedCreatePodSandBox  1m    kubelet            Failed to create pod sandbox
  Warning  Failed                  1m    kubelet            Failed to pull image
```

描述节点资源：

```bash
kubectl describe nodes minikube
```

关键的故障排查技巧：

- 识别 Deployment 和 Pod 的状态
- 查看详细的错误信息
- 理解资源未运行的原因
- 检查节点和集群条件
