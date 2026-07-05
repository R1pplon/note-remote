# 创建 Ingress 资源

现在我们已经设置了 Ingress 控制器并运行了一个后端服务，接下来可以为 Ingress 资源创建规则。

在本示例中，我们将创建一个简单的规则，将 `test.local` 域名的流量路由到我们的后端服务：

```
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: test-ingress
  annotations:
    kubernetes.io/ingress.class: nginx
spec:
  rules:
  - host: test.local
    http:
      paths:
      - path: /
        pathType: Prefix
        backend:
          service:
            name: sample-app
            port:
              name: http
```

将上述内容保存为名为 `ingress.yaml` 的 YAML 文件。使用以下命令将 Ingress 资源应用到集群中：

```
kubectl apply -f ingress.yaml
```
