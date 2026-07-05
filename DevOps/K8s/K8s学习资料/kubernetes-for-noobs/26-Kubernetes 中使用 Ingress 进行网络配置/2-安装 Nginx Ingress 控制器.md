# 安装 Nginx Ingress 控制器

首先，我们需要在集群中安装 `nginx-ingress` 控制器。我们可以通过创建一个 Deployment 和一个 Service 来实现，它们将负责运行 Ingress 控制器。

为 Ingress 控制器创建一个命名空间：

```
kubectl create namespace ingress-nginx
```

使用 `kubectl` 安装 `ingress-nginx` 的 Helm 图表：

```
kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/controller-v1.7.0/deploy/static/provider/cloud/deploy.yaml
```

验证 `ingress-nginx` 控制器 Pod 是否正在运行：

```
kubectl get pods -n ingress-nginx
```

```plaintext
NAME                                        READY   STATUS              RESTARTS   AGE
ingress-nginx-admission-create-zjfqx        0/1     ContainerCreating   0          2s
ingress-nginx-admission-patch-8rvzw         0/1     ContainerCreating   0          2s
ingress-nginx-controller-6bdb654777-qz8fb   0/1     ContainerCreating   0          2s
```
