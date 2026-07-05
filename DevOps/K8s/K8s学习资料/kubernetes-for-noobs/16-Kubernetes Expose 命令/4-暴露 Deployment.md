# 暴露 Deployment

为了使 Deployment 可以从 Kubernetes 集群外部访问，你需要将其暴露为一个服务。Kubernetes 中的服务充当稳定的网络端点，即使底层 Pod 发生变化。

1. 运行以下命令以创建一个名为 `hello-service` 的 NodePort 服务：

   ```bash
   kubectl expose deployment hello-world --name=hello-service --port=80 --target-port=80 --type=NodePort
   ```

   - `--port` 标志指定服务将向外部客户端暴露的端口。
   - `--target-port` 标志定义服务将流量路由到的容器端口。
   - `--type=NodePort` 标志使服务可以在集群中每个节点的特定端口上访问。

2. 验证服务是否成功创建：

   ```bash
   kubectl get services hello-service
   ```

   此命令显示服务的详细信息，包括其类型和分配的 NodePort。

像 NodePort 这样的服务允许外部客户端通过将请求转发到正确的容器端口与 Kubernetes 集群中的 Pod 进行交互。
