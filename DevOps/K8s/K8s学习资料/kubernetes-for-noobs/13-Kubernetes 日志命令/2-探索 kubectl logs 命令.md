# 探索 kubectl logs 命令

`kubectl logs` 命令用于打印 Pod 中容器或指定资源的日志。它支持快照日志、流式传输、过滤以及各种其他选项，以实现高效的日志管理。

运行以下命令以查看 `kubectl logs` 的可用选项：

```bash
kubectl logs -h
```

你将看到以下输出：

```plaintext
打印 Pod 中容器或指定资源的日志。如果 Pod 只有一个容器，则容器名称是可选的。

示例：
  # 从只有一个容器的 Pod nginx 返回快照日志
  kubectl logs nginx

  # 从有多个容器的 Pod nginx 返回快照日志
  kubectl logs nginx --all-containers=true

  # 从标签为 app=nginx 的 Pod 中的所有容器返回快照日志
  kubectl logs -l app=nginx --all-containers=true

  # 从 Pod web-1 中返回之前终止的 ruby 容器的快照日志
  kubectl logs -p -c ruby web-1

  # 开始流式传输 Pod web-1 中 ruby 容器的日志
  kubectl logs -f -c ruby web-1

  # 开始流式传输标签为 app=nginx 的 Pod 中所有容器的日志
  kubectl logs -f -l app=nginx --all-containers=true

  # 仅显示 Pod nginx 中最近的 20 行日志输出
  kubectl logs --tail=20 nginx

  # 显示 Pod nginx 在过去一小时内写入的所有日志
  kubectl logs --since=1h nginx

  # 显示来自具有过期服务证书的 kubelet 的日志
  kubectl logs --insecure-skip-tls-verify-backend nginx

  # 从名为 hello 的 Job 的第一个容器返回快照日志
  kubectl logs job/hello

  # 从名为 nginx 的 Deployment 的容器 nginx-1 返回快照日志
  kubectl logs deployment/nginx -c nginx-1
```
