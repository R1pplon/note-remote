# 探索 kubectl port-forward 命令

`kubectl port-forward` 命令允许你将一个或多个本地端口转发到 Kubernetes 集群中的 Pod、Deployment 或 Service。它通常用于在不对外暴露服务的情况下进行测试和调试。

运行以下命令以查看 `kubectl port-forward` 的可用选项：

```bash
kubectl port-forward -h
```

你将看到以下输出：

```plaintext
将一个或多个本地端口转发到 Pod。

使用资源类型/名称（如 deployment/mydeployment）来选择 Pod。如果省略资源类型，则默认为 'pod'。

如果有多个 Pod 符合条件，将自动选择一个 Pod。当所选 Pod 终止时，转发会话结束，需要重新运行命令以恢复转发。

示例：
  # 在本地监听端口 5000 和 6000，将数据转发到 Pod 中的端口 5000 和 6000
  kubectl port-forward pod/mypod 5000 6000

  # 在本地监听端口 5000 和 6000，将数据转发到由 Deployment 选择的 Pod 中的端口 5000 和 6000
  kubectl port-forward deployment/mydeployment 5000 6000

  # 在本地监听端口 8443，将数据转发到由 Service 选择的 Pod 中名为 "https" 的端口的目标端口
  kubectl port-forward service/myservice 8443:https

  # 在本地监听端口 8888，将数据转发到 Pod 中的端口 5000
  kubectl port-forward pod/mypod 8888:5000

  # 在所有地址上监听端口 8888，将数据转发到 Pod 中的端口 5000
  kubectl port-forward --address 0.0.0.0 pod/mypod 8888:5000

  # 在 localhost 和指定 IP 上监听端口 8888，将数据转发到 Pod 中的端口 5000
  kubectl port-forward --address localhost,10.19.21.23 pod/mypod 8888:5000

  # 在本地监听一个随机端口，将数据转发到 Pod 中的端口 5000
  kubectl port-forward pod/mypod :5000
```
