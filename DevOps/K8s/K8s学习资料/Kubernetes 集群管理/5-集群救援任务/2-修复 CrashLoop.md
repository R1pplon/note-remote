# 修复 CrashLoop

监控系统报告 `rescue-mission` 命名空间中的 `backend` Pod 处于 `CrashLoopBackOff` 状态。这通常意味着容器内的应用程序无法启动或立即退出。

你的任务是调查日志，找到配置错误，并修复 `deployment.yaml` 文件，以便 Pod 能够成功启动。

## 任务

1. 检查 `rescue-mission` 命名空间中 Pod 的状态。
2. 查看崩溃 Pod 的日志以确定失败原因。
3. 编辑 `deployment.yaml` 文件以修复问题。该应用程序是一个简单的 Nginx 服务器，不需要当前导致失败的自定义 `command`。请完全删除错误的 `command` 覆盖。
4. 将更新后的配置应用到集群。
5. 确保 Pod 进入 `Running` 状态。

## 要求

- 在 `rescue-mission` 命名空间中执行所有操作。
- 修改本地 `deployment.yaml` 文件并应用更改。
- Pod 的最终状态必须为 `Running`。

## 示例

验证 Pod 是否正在运行：

```bash
kubectl get pods -n rescue-mission
```

预期输出：

```plaintext
NAME                      READY   STATUS    RESTARTS   AGE
backend-7f8b9c4d5-abcde   1/1     Running   2          2m
```

## 提示

<details>
<summary>如何查看 Pod 日志</summary>

使用 `kubectl logs -n rescue-mission -l app=backend` 查看日志。你会看到关于 `missing.conf` 的错误。

</details>

<details>
<summary>如何修复部署</summary>

打开 `deployment.yaml` 并删除定义 `command: [...]` 的行。然后重新应用该文件。

</details>
