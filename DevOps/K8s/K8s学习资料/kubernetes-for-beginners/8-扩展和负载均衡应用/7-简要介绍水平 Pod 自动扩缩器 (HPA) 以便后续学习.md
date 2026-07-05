---
title: "简要介绍水平 Pod 自动扩缩器 (HPA) 以便后续学习"
date: 2026-06-20
---

# 简要介绍水平 Pod 自动扩缩器 (HPA) 以便后续学习

在这一步中，你将了解水平 Pod 自动扩缩器 (Horizontal Pod Autoscaler, HPA)，它是 Kubernetes 的一项强大功能，可根据资源利用率自动扩展应用程序。HPA 允许你根据 CPU 利用率、内存使用情况甚至自定义指标等指标来定义扩缩规则。

**理解 HPA：**

HPA 会根据观察到的 CPU 或内存使用情况，或者根据应用程序提供的自定义指标，自动调整 Deployment、ReplicaSet 或 StatefulSet 中运行的 Pod 副本数量。这确保了你的应用程序可以自动扩展以应对不断变化的流量负载，从而提高性能和可用性。

在 Minikube 中启用指标服务器插件：

```bash
minikube addons enable metrics-server
```

示例输出：

```
* The 'metrics-server' addon is enabled
```

指标服务器为 Kubernetes 提供有关资源使用情况的数据，这对 HPA 的正常运行至关重要。

创建一个带有资源请求的 Deployment：

```bash
nano ~/project/k8s-manifests/hpa-example.yaml
```

添加以下内容：

```yml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: php-apache
spec:
  selector:
    matchLabels:
      run: php-apache
  replicas: 1
  template:
    metadata:
      labels:
        run: php-apache
    spec:
      containers:
        - name: php-apache
          image: k8s.gcr.io/hpa-example
          ports:
            - containerPort: 80
          resources:
            limits:
              cpu: 500m
            requests:
              cpu: 200m
---
apiVersion: v1
kind: Service
metadata:
  name: php-apache
  labels:
    run: php-apache
spec:
  ports:
    - port: 80
  selector:
    run: php-apache
```

应用该 Deployment：

```bash
kubectl apply -f ~/project/k8s-manifests/hpa-example.yaml
```

**YAML 配置说明：**

- 此 YAML 文件定义了一个 PHP 应用程序的 Deployment 以及相应的 Service。
- Deployment 配置与 NGINX 的配置非常相似，不同之处在于：
  - **`name: php-apache`**：Deployment 和 Pod 容器的名称。
  - **`image: k8s.gcr.io/hpa-example`**：容器的 Docker 镜像。
  - **`resources`**：此部分指定了容器的资源要求。
    - **`limits.cpu: 500m`**：容器允许使用的最大 CPU。
    - **`requests.cpu: 200m`**：分配给容器的保证 CPU 数量。
- 该 Service 是一个标准的 Service 配置，用于在内部暴露 Deployment。

创建一个 HPA 配置：

```bash
nano ~/project/k8s-manifests/php-apache-hpa.yaml
```

添加以下 HPA 清单：

```yml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: php-apache
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: php-apache
  minReplicas: 1
  maxReplicas: 10
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 50
```

应用 HPA 配置：

```bash
kubectl apply -f ~/project/k8s-manifests/php-apache-hpa.yaml
```

**YAML 配置说明：**

- **`apiVersion: autoscaling/v2`**：指定 HorizontalPodAutoscaler 的 API 版本。
- **`kind: HorizontalPodAutoscaler`**：表示这是一个 HPA 对象。
- **`metadata`**：包含 HPA 的元数据。
  - **`name: php-apache`**：HPA 的名称。
- **`spec`**：包含 HPA 规范。
  - **`scaleTargetRef`**：定义将被扩缩的目标 Deployment。
    - **`apiVersion: apps/v1`**：目标资源的 API 版本。
    - **`kind: Deployment`**：目标资源类型，即 Deployment。
    - **`name: php-apache`**：要扩缩的目标 Deployment 的名称。
  - **`minReplicas: 1`**：要保持运行的最小副本数。
  - **`maxReplicas: 10`**：要扩缩到的最大副本数。
  - **`metrics`**：定义如何确定扩缩指标。
    - **`type: Resource`**：根据资源指标进行扩缩。
    - **`resource.name: cpu`**：根据 CPU 使用情况进行扩缩。
    - **`resource.target.type: Utilization`**：根据 Pod 请求的 CPU 百分比进行扩缩。
    - **`resource.target.averageUtilization: 50`**：当所有 Pod 的平均 CPU 使用量超过请求量的 50% 时进行扩缩。

验证 HPA 配置：

```bash
kubectl get hpa
```

示例输出：

```
NAME         REFERENCE              TARGETS         MINPODS   MAXPODS   REPLICAS   AGE
php-apache   Deployment/php-apache  0%/50%          1         10        1          30s
```

### 模拟负载并实时观察自动扩缩

为了模拟高负载并触发自动扩缩器，你将在一个终端中运行负载生成器，并在另一个终端中监控扩缩活动。

首先，打开一个终端运行负载生成器：

```bash
kubectl run -i --tty load-generator --rm --image=busybox --restart=Never -- /bin/sh -c "while sleep 0.01; do wget -q -O- http://php-apache; done"
```

不要关闭运行负载生成器的终端。**打开另一个终端** 来监控扩缩活动。

在第二个终端中，你可以使用几个命令来实时观察自动扩缩：

1. 监控 HPA 状态（每隔几秒更新一次）：

```bash
kubectl get hpa -w
```

2. 观察随着 HPA 扩容而创建的 Pod：

```bash
kubectl get pods -w
```

3. 跟踪与扩缩活动相关的事件：

```bash
kubectl get events --sort-by='.lastTimestamp' -w
```

你可以运行这些命令中的任何一个来观察自动扩缩过程的不同方面。例如，使用 `-w` 标志观察 Pod 可以让你实时看到系统扩容时创建的 Pod：

`kubectl get pods -w` 的示例输出：

```
NAME                         READY   STATUS    RESTARTS   AGE
php-apache-xxxxxxxxx-xxxxx   1/1     Running   0          2m
load-generator               1/1     Running   0          30s
php-apache-xxxxxxxxx-yyyyy   0/1     Pending   0          0s
php-apache-xxxxxxxxx-yyyyy   0/1     ContainerCreating   0          0s
php-apache-xxxxxxxxx-yyyyy   1/1     Running   0          3s
php-apache-xxxxxxxxx-zzzzz   0/1     Pending   0          0s
php-apache-xxxxxxxxx-zzzzz   0/1     ContainerCreating   0          0s
php-apache-xxxxxxxxx-zzzzz   1/1     Running   0          2s
```

你将看到 HPA 通过增加 Pod 数量来响应增加的负载。指标更新可能需要一分钟或更长时间才能反映出变化：

`kubectl get hpa -w` 的示例输出：

```
NAME         REFERENCE               TARGETS   MINPODS   MAXPODS   REPLICAS   AGE
php-apache   Deployment/php-apache   0%/50%    1         10        1          30s
php-apache   Deployment/php-apache   68%/50%   1         10        1          90s
php-apache   Deployment/php-apache   68%/50%   1         10        2          90s
php-apache   Deployment/php-apache   79%/50%   1         10        2          2m
php-apache   Deployment/php-apache   79%/50%   1         10        4          2m15s
```

观察完成后，按 `Ctrl+C` 停止监控命令，然后回到第一个终端按 `Ctrl+C` 停止负载生成器。

关于 HPA 的关键点：

1. 根据资源利用率自动扩缩 Pod，提高应用程序的弹性。
2. 可以根据 CPU、内存或自定义指标进行扩缩。
3. 定义最小和最大副本数，确保扩缩平衡且高效。
4. HPA 是在不同负载下保持应用程序性能和可用性的关键组件。
5. 在 `kubectl` 命令中使用 `-w`（watch）标志可以实时监控集群变化。
