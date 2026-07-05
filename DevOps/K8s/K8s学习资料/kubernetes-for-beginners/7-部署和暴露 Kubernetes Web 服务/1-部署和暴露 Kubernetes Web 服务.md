# 部署和暴露 Kubernetes Web 服务

你是一名 DevOps 工程师，任务是使用 Kubernetes 为一个简单的 Web 应用程序搭建微服务架构。你的目标是展示你在部署和配置容器化服务方面的技能。

## 任务

- 为 NGINX 前端服务创建一个具有 2 个副本的 Kubernetes 部署
- 为 Apache 后端服务创建一个具有 2 个副本的 Kubernetes 部署
- 使用 ClusterIP 服务类型暴露这两个服务
- 为两个部署添加自定义标签，以区分前端和后端服务

## 要求

- 使用 `~/project/k8s-challenge` 作为你的工作目录
- 将 NGINX 部署命名为 `frontend-deployment`
- 将 Apache 部署命名为 `backend-deployment`
- 前端使用 `nginx:alpine` 镜像，后端使用 `httpd:alpine` 镜像
- 为两个部署设置副本数为 2
- 为 NGINX 部署添加标签 `tier=frontend`
- 为 Apache 部署添加标签 `tier=backend`
- 使用名称 `frontend-service` 和 `backend-service` 暴露服务

## 示例

前端部署标签示例：

```yaml
metadata:
  labels:
    tier: frontend
```

后端服务示例：

```yaml
apiVersion: v1
kind: Service
metadata:
  name: backend-service
spec:
  type: ClusterIP
  selector:
    tier: backend
```

通过运行 `kubectl get deployments`、`kubectl get pods` 和 `kubectl get services` 命令来验证你的工作。

```bash
kubectl get deployments
```

```plaintext
NAME                  READY   UP-TO-DATE   AVAILABLE   AGE
backend-deployment    2/2     2            2           16s
frontend-deployment   2/2     2            2           21s
```

```bash
kubectl get pods
```

```plaintext
NAME                                   READY   STATUS    RESTARTS   AGE
backend-deployment-6978876d76-nr2z8    1/1     Running   0          24s
backend-deployment-6978876d76-zdj9f    1/1     Running   0          24s
frontend-deployment-6c5445f89b-2h5dv   1/1     Running   0          29s
frontend-deployment-6c5445f89b-vbm6f   1/1     Running   0          29s
```

```bash
kubectl get services
```

```plaintext
NAME               TYPE        CLUSTER-IP       EXTERNAL-IP   PORT(S)   AGE
backend-service    ClusterIP   10.96.124.139    <none>        80/TCP    18s
frontend-service   ClusterIP   10.101.190.201   <none>        80/TCP    23s
kubernetes         ClusterIP   10.96.0.1        <none>        443/TCP   92s
```

## 提示

- 使用 `minikube start` 启动 Kubernetes 集群
- 复习关于 Kubernetes 部署和服务的上一个实验
- 使用 `kubectl create deployment` 和 `kubectl expose` 命令
- 查阅 Kubernetes 文档了解标签语法
- 在暴露服务时使用 `--port` 标志
