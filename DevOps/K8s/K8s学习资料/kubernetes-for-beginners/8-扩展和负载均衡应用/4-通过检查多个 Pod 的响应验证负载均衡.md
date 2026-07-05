---
title: "通过检查多个 Pod 的响应验证负载均衡"
date: 2026-06-20
---

# 通过检查多个 Pod 的响应验证负载均衡

在这一步中，你将学习如何通过创建 Service 并检查来自多个 Pod 的响应来验证 Kubernetes 中的负载均衡。负载均衡对于将流量分配到多个副本至关重要，确保没有单个 Pod 过载。Kubernetes Service 会自动处理这一过程。

创建一个 Service 以暴露部署：

```bash
nano ~/project/k8s-manifests/nginx-service.yaml
```

添加以下 Service 配置：

```yml
apiVersion: v1
kind: Service
metadata:
  name: nginx-service
spec:
  selector:
    app: nginx
  type: ClusterIP
  ports:
    - port: 80
      targetPort: 80
```

保存文件（按 Ctrl+X，然后按 Y，最后按 Enter）。

**YAML 配置说明：**

- **`apiVersion: v1`**：指定 Service 的 API 版本。
- **`kind: Service`**：表示这是一个 Service 对象。
- **`metadata`**：包含 Service 的元数据。
  - **`name: nginx-service`**：Service 的名称。
- **`spec`**：包含 Service 的规范。
  - **`selector`**：定义该 Service 将流量路由到哪些 Pod。
    - **`app: nginx`**：选择带有标签 `app: nginx` 的 Pod，这与上一步中创建的 Pod 匹配。
  - **`type: ClusterIP`**：创建一个具有集群 IP 地址的内部 Service，用于内部通信。此 Service 类型仅在 Kubernetes 集群内可访问。
  - **`ports`**：定义 Service 如何映射流量。
    - **`port: 80`**：Service 暴露的端口。
    - **`targetPort: 80`**：容器内应用程序监听的端口。

应用 Service：

```bash
kubectl apply -f ~/project/k8s-manifests/nginx-service.yaml
```

示例输出：

```
service/nginx-service created
```

验证 Service：

```bash
kubectl get services
```

示例输出：

```
NAME            TYPE        CLUSTER-IP      EXTERNAL-IP   PORT(S)   AGE
kubernetes      ClusterIP   10.96.0.1       <none>        443/TCP   30m
nginx-service   ClusterIP   10.96.xxx.xxx   <none>        80/TCP    30s
```

现在，为了真正验证负载均衡，你将创建一个临时 Pod 并向 Service 发送多个请求。这使你可以看到请求被分配到不同的 NGINX Pod。

创建一个临时 Pod 以测试负载均衡：

```bash
kubectl run curl-test --image=curlimages/curl --rm -it -- sh
```

此命令执行以下操作：

- `kubectl run curl-test`：创建一个名为 `curl-test` 的新 Pod。
- `--image=curlimages/curl`：使用安装了 `curl` 的 Docker 镜像。
- `--rm`：完成后自动删除 Pod。
- `-it`：分配一个伪终端并保持标准输入打开。
- `-- sh`：在 Pod 中启动一个 shell 会话。

在临时 Pod 中，运行多个请求：

```bash
for i in $(seq 1 10); do curl -s nginx-service | grep -q "Welcome to nginx!" && echo "Welcome to nginx - Request $i"; done
```

此循环将向 `nginx-service` 发送 10 个请求。每个请求应被路由到可用的 NGINX Pod 之一。输出将为每个成功的请求打印 `Welcome to nginx - Request $i`。

示例输出：

```
Welcome to nginx - Request 1
Welcome to nginx - Request 2
Welcome to nginx - Request 3
...
```

退出临时 Pod：

```bash
exit
```

关于负载均衡的关键点：

1. Service 将流量分配到所有匹配的 Pod。
2. 每个请求可能会命中不同的 Pod。
3. Kubernetes 默认使用轮询（round-robin）方式。
4. `ClusterIP` Service 类型提供内部负载均衡。
5. curl 测试显示负载被分配到多个 NGINX 实例。
