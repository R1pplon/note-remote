# 部署 Nginx Pod

作为一名新入职的 DevOps 工程师，你被指派部署一个运行最新版本 Nginx Web 服务器的 Kubernetes Pod。你的目标是确保该 Pod 处于运行状态，并能通过服务正常访问。

## 任务

- 创建一个 Kubernetes YAML 文件，用于部署运行最新 Nginx 容器的 Pod。
- 创建一个类型为 NodePort 的服务来暴露该 Nginx Pod。

## 要求

- 在 `~/project` 目录下创建 Kubernetes YAML 文件。
- YAML 文件命名为 `nginx-pod.yaml`。
- Nginx 容器镜像版本必须为最新版（latest）。
- 服务类型必须为 `NodePort`。

## 示例

YAML 文件内容示例：

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: nginx-pod
  labels:
    app: nginx
spec:
  containers:
    - name: nginx
      image: nginx:latest
      ports:
        - containerPort: 80
---
apiVersion: v1
kind: Service
metadata:
  name: nginx-service
spec:
  selector:
    app: nginx
  type: placeholder
  ports:
    - port: 80
      targetPort: 80
```

服务访问 URL 示例：

```
http://<node-ip>:<node-port>
```

## 提示

- 使用 `kubectl` 命令与 Kubernetes 集群进行交互。
- 确保 Pod 正在运行，且服务已正确暴露了该 Pod。
