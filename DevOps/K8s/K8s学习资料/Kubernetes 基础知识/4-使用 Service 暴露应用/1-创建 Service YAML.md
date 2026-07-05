---
title: "创建 Service YAML"
date: 2026-06-20
---

# 创建 Service YAML

在这一步中，你将创建一个 **Service** 的配置文件。我们将使用一种称为 **NodePort** 的特定类型。

NodePort Service 会在集群中的每个节点（服务器）上打开一个特定的端口。这就像是在你的房子上开了一扇特定的门，以便外部世界可以进入。

NodePort 很容易理解，因为它直接映射到每个节点上的端口，但它通常不是面向公共流量的最终生产环境设计。在大型环境中，NodePort 通常位于云负载均衡器或 Ingress 层之后。在这里使用它，是因为它能让初学者直观地看到网络路径。

在你的项目目录中创建一个名为 `service.yaml` 的文件：

```bash
nano service.yaml
```

粘贴以下内容。注意 `selector` 部分——我们暂时将其设为 `app: unknown`，以便向你展示它是如何工作的。

```yaml
apiVersion: v1
kind: Service
metadata:
  name: my-service
spec:
  type: NodePort
  ports:
    - port: 80 # Service 内部暴露的端口
      targetPort: 80 # 容器正在监听的端口
      nodePort: 30008 # 我们向外部打开的静态门
  selector:
    app: unknown # 这需要与你的 Pod 标签匹配
```

保存并退出（`Ctrl+X`，`Y`，`Enter`）。

该文件定义了一个名为 `my-service` 的 Service。它监听 30008 端口（即 `nodePort`）。任何访问此端口的流量都将被转发到标签为 `app: unknown` 的 Pod（目前这些 Pod 并不存在！）。
