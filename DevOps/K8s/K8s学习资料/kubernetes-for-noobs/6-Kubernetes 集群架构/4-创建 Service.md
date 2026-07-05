---
title: "创建 Service"
date: 2026-06-20
---

# 创建 Service

现在我们已经有一个正在运行的 Pod，接下来让我们创建一个 Service 来暴露它。在 Kubernetes 中，Service 是一种抽象，定义了一组逻辑上的 Pod 以及访问它们的策略。可以将其视为将应用程序暴露到网络的一种方式，无论是在集群内部还是外部。

在项目目录中创建一个名为 `nginx-service.yaml` 的文件：

```bash
nano ~/project/nginx-service.yaml
```

向文件中添加以下内容：

```yml
apiVersion: v1
kind: Service
metadata:
  name: nginx-service
spec:
  selector:
    app: nginx
  ports:
    - protocol: TCP
      port: 80
      targetPort: 80
  type: NodePort
```

让我们分解这个 YAML 文件：

- `selector`：这决定了 Service 将流量发送到哪些 Pod。在本例中，它将选择带有标签 `app: nginx` 的任何 Pod。
- `ports`：这指定了 Service 应使用的端口。
- `type: NodePort`：这意味着 Service 将在集群中每个节点的端口上可访问。

保存文件并退出编辑器。

现在，通过运行以下命令创建 Service：

```bash
kubectl apply -f nginx-service.yaml
```

要检查 Service 的状态，请使用：

```bash
kubectl get services
```

你应该会看到类似以下的输出：

```
NAME            TYPE        CLUSTER-IP      EXTERNAL-IP   PORT(S)        AGE
kubernetes      ClusterIP   10.96.0.1       <none>        443/TCP        1h
nginx-service   NodePort    10.110.126.65   <none>        80:30080/TCP   30s
```

`nginx-service` 行显示你的 Service 已创建。PORT(S) 下的 `80:30080/TCP` 表示集群内部的端口 80 映射到节点上的端口 30080。

要获取有关 Service 的更多详细信息，请使用：

```bash
kubectl describe service nginx-service
```

此命令提供了有关 Service 的类型、IP 地址、端口和端点的信息。端点是 Service 将流量发送到的 Pod 的 IP 地址。
