# 使用 kubectl proxy 访问应用程序

在此步骤中，你将学习如何使用 `kubectl proxy` 访问你的 Kubernetes 应用程序。`kubectl proxy` 创建一个到 Kubernetes API 服务器的安全代理连接，允许你从当前环境访问集群服务和 Pod。这对于开发和调试非常有用，特别是当你想要访问未向外部公开的服务时。

首先，请确保你位于项目目录中：

```bash
cd ~/project/k8s-manifests/manifests
```

在后台启动 `kubectl proxy`。此命令将在单独的进程中运行代理，允许你继续使用终端。

```bash
kubectl proxy --port=8080 &
```

命令末尾的 `&` 会将其在后台运行。你应该会看到类似以下的输出：

```
Starting to serve on 127.0.0.1:8080
```

如果运行此命令后终端似乎挂起，你可能需要按一次 `Ctrl+C` 返回到命令提示符。代理应该仍在后台运行。

现在，让我们找到属于你的 `web-app` Deployment 的 Pod 名称。你可以再次使用 `kubectl get pods -l app=web`：

```bash
# 获取 'web-app' 的 Pod 名称
kubectl get pods -l app=web
```

示例输出：

```
NAME                      READY   STATUS    RESTARTS   AGE
web-app-xxx-yyy           1/1     Running   0          20m
web-app-xxx-zzz           1/1     Running   0          20m
```

记下一个 Pod 的名称，例如 `web-app-xxx-yyy`。你将使用此 Pod 名称来构建 API 路径以访问该 Pod 中运行的 NGINX Web 服务器。

可以通过特定路径访问 Kubernetes API 资源。要通过 `kubectl proxy` 访问 Pod，你需要构建一个类似以下的 URL：

```
http://localhost:8080/api/v1/namespaces/<namespace>/pods/<pod_name>/proxy/
```

让我们分解这个 URL：

- `http://localhost:8080`: `kubectl proxy` 运行的地址。默认情况下，它在你的当前环境中监听端口 8080。
- `/api/v1`: 指定 Kubernetes API 版本（`v1`）。
- `/namespaces/<namespace>`: 你的 Pod 运行的命名空间。在我们的例子中，它是 `default`。
- `/pods/<pod_name>`: 你要访问的 Pod 的名称。将 `<pod_name>` 替换为你的 Pod 的实际名称（例如，`web-app-xxx-yyy`）。
- `/proxy/`: 指示你要代理到该 Pod 的连接。

为了方便在 URL 中使用 Pod 名称，让我们将第一个 Pod 的名称存储在一个 shell 变量中。运行此命令，它使用 `kubectl get pods` 和 `jsonpath` 来提取带有标签 `app=web` 的第一个 Pod 的名称：

```bash
# 获取带有标签 'app=web' 的第一个 Pod 的名称
POD_NAME=$(kubectl get pods -l app=web -o jsonpath='{.items[0].metadata.name}')
echo $POD_NAME # 可选：打印 Pod 名称以进行验证
```

现在，你可以在 `curl` 命令中使用 `$POD_NAME` 变量来访问你的 Pod 通过代理提供的 NGINX 默认页面。使用 `curl` 向代理 URL 发送 HTTP 请求。在 URL 中将 `${POD_NAME}` 替换为我们刚刚设置的变量：

```bash
curl http://localhost:8080/api/v1/namespaces/default/pods/${POD_NAME}/proxy/
```

如果一切正常，此命令应返回 NGINX 默认欢迎页面的 HTML 内容。示例输出将是 HTML 内容，开头为：

```html
<!doctype html>
<html>
  <head>
    <title>Welcome to nginx!</title>
    ...
  </head>
  <body>
    <h1>Welcome to nginx!</h1>
    ...
  </body>
</html>
```

此输出确认你已通过 `kubectl proxy` 成功访问了运行在你 Pod 中的 NGINX Web 服务器。

让我们探索一下使用 `kubectl proxy` 可以做的更多事情。

**通过代理列出默认命名空间中的所有 Pod**：

你可以直接通过代理访问 Kubernetes API。例如，要列出 `default` 命名空间中的所有 Pod，你可以使用此 URL：

```bash
curl http://localhost:8080/api/v1/namespaces/default/pods/
```

这将返回一个 JSON 响应，其中包含 `default` 命名空间中所有 Pod 的信息。

**通过代理获取特定 Pod 的详细信息**：

要获取特定 Pod 的详细信息（类似于 `kubectl describe pod`），你可以直接访问 Pod 的 API 端点：

```bash
curl http://localhost:8080/api/v1/namespaces/default/pods/${POD_NAME}
```

这将返回一个 JSON 响应，其中包含指定 Pod 的详细信息。

**停止 `kubectl proxy`**：

当你完成使用 `kubectl proxy` 后，你应该停止它。由于我们是在后台启动它的，你需要找到它的进程 ID (PID) 并终止它。你可以使用 `jobs` 命令列出后台进程：

```bash
jobs
```

这将显示从当前终端会话运行的后台进程。你应该会看到列出的 `kubectl proxy`。要停止它，你可以使用 `kill` 命令后跟进程 ID。例如，如果 `jobs` 显示 `[1]  Running                 kubectl proxy --port=8080 &`，则进程 ID 为 `1`。你将使用：

```bash
kill %1
```

将 `%1` 替换为 `jobs` 命令显示的作业 ID。或者，你可以使用 `ps aux | grep kubectl proxy` 找到进程 ID，然后使用 `kill <PID>`。

关于 `kubectl proxy` 需要记住的关键点：

- `kubectl proxy` 创建一个到 Kubernetes API 服务器的安全、已认证的连接。
- 它允许你从当前环境访问集群资源（Pod、Service 等），就像你在集群网络内部一样。
- 它对于调试、开发和探索 Kubernetes API 非常有用。
- 出于安全原因，`kubectl proxy` 只能在 `localhost` (127.0.0.1) 上访问。它不适用于公开暴露服务。
