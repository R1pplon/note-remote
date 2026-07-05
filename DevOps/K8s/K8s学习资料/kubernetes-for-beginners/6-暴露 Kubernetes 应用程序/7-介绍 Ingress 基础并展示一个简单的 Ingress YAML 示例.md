# 介绍 Ingress 基础并展示一个简单的 Ingress YAML 示例

在这一步中，你将了解 Kubernetes Ingress，它是管理对 Kubernetes 集群中服务的外部访问的强大方式。

## 什么是 Ingress？

Ingress 是一个 API 对象，用于管理对 Kubernetes 集群中服务的外部访问，通常是 HTTP 访问。Ingress 提供以下功能：

- **负载均衡**：将流量分发到多个后端服务
- **SSL/TLS 终止**：处理安全连接
- **基于名称的虚拟主机**：根据主机名将请求路由到不同的服务
- **基于路径的路由**：根据 URL 路径将请求路由到不同的服务

Ingress 由两个组件组成：

1. **Ingress 资源**：定义路由规则的 Kubernetes API 对象
2. **Ingress 控制器**：强制执行 Ingress 资源中定义的规则的实现

> **注意**：本实验仅对 Ingress 进行了基本介绍。在生产环境中，Ingress 配置可能会复杂得多，包括高级路由、身份验证、速率限制等。

让我们在 Minikube 中启用 Ingress 插件：

```bash
minikube addons enable ingress
```

示例输出：

```
💡  ingress 是由 Kubernetes 维护的插件。如有任何问题，请在 GitHub 上联系 Minikube。
🔉  ingress 已成功启用
```

为两个示例应用程序创建部署：

```bash
kubectl create deployment web1 --image=nginx:alpine
kubectl create deployment web2 --image=httpd:alpine
```

将这些部署作为服务暴露：

```bash
kubectl expose deployment web1 --port=80 --type=ClusterIP --name=web1-service
kubectl expose deployment web2 --port=80 --type=ClusterIP --name=web2-service
```

创建一个 Ingress YAML 文件：

```bash
nano ingress-example.yaml
```

添加以下 Ingress 配置：

```yml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: example-ingress
  annotations:
    nginx.ingress.kubernetes.io/rewrite-target: /
spec:
  rules:
    - http:
        paths:
          - path: /web1
            pathType: Prefix
            backend:
              service:
                name: web1-service
                port:
                  number: 80
          - path: /web2
            pathType: Prefix
            backend:
              service:
                name: web2-service
                port:
                  number: 80
```

此 Ingress 配置的关键组件：

- **metadata.annotations**：Ingress 控制器的特定配置
- **spec.rules**：定义如何将流量路由到服务
- **path**：将匹配的 URL 路径
- **pathType**：路径应如何匹配（Prefix、Exact 或 ImplementationSpecific）
- **backend.service**：要将流量路由到的服务和端口

应用 Ingress 配置：

```bash
kubectl apply -f ingress-example.yaml
```

验证 Ingress 资源：

```bash
kubectl get ingress
```

示例输出：

```
NAME              CLASS   HOSTS   ADDRESS        PORTS   AGE
example-ingress   nginx   *       192.168.49.2   80      1m
```

查看 Ingress 详细信息：

```bash
kubectl describe ingress example-ingress
```

示例输出将显示路由规则和后端服务。

测试 Ingress：

```sh
# 获取 Minikube IP
minikube ip

# 测试通过 Ingress 访问服务
curl $(minikube ip)/web1
curl $(minikube ip)/web2
```

每个命令应返回相应 Web 服务器的默认页面。

在生产环境中，Ingress 可以配置以下内容：

- 多个基于主机名的规则
- 用于 HTTPS 的 TLS 证书
- 身份验证机制
- 速率限制
- 自定义超时配置
- 会话亲和性
- 以及更多高级功能

要更全面地了解 Ingress，请参考 Kubernetes 文档，并考虑探索专门的 Ingress 控制器文档，如 NGINX Ingress 或 Traefik。
