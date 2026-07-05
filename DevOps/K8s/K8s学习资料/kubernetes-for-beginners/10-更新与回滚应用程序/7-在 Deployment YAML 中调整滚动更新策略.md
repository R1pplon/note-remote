# 在 Deployment YAML 中调整滚动更新策略

在这一步中，你将学习如何自定义 Kubernetes Deployment 中的滚动更新策略，以控制应用程序的更新和扩展方式。

首先，导航到项目目录：

```bash
cd ~/project/k8s-manifests
```

创建一个包含自定义滚动更新策略的部署清单文件：

```bash
nano custom-rollout-deployment.yaml
```

添加以下内容：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app-custom-rollout
  labels:
    app: web
spec:
  replicas: 5
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxUnavailable: 2
      maxSurge: 3
  selector:
    matchLabels:
      app: web
  template:
    metadata:
      labels:
        app: web
    spec:
      containers:
        - name: nginx
          image: nginx:1.24.0-alpine
          ports:
            - containerPort: 80
```

应用部署：

```bash
kubectl apply -f custom-rollout-deployment.yaml
```

示例输出：

```
deployment.apps/web-app-custom-rollout created
```

验证部署状态：

```bash
kubectl rollout status deployment web-app-custom-rollout
```

示例输出：

```
Waiting for deployment "web-app-custom-rollout" to roll out...
deployment "web-app-custom-rollout" successfully rolled out
```

描述部署以确认策略：

```bash
kubectl describe deployment web-app-custom-rollout
```

示例输出将包含：

```
StrategyType:           RollingUpdate
RollingUpdateStrategy:  2 max unavailable, 3 max surge
```

更新镜像以触发滚动更新：

```bash
kubectl set image deployment/web-app-custom-rollout nginx=nginx:1.25.0-alpine
```

监控更新过程：

```bash
kubectl rollout status deployment web-app-custom-rollout
```

滚动更新策略的关键点：

1. `maxUnavailable`：更新期间允许不可用的 Pod 的最大数量
2. `maxSurge`：允许超出期望数量的 Pod 的最大数量
3. 帮助控制更新速度和应用程序的可用性
4. 允许微调部署行为
