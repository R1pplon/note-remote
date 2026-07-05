# 创建一个 Job

**Job** 用于运行需要成功完成的任务，例如批处理作业或数据处理任务。我们将使用 `kubectl run` 创建一个 Job 并验证其执行情况。

1. **创建 Job**

运行以下命令以创建一个名为 `busybox-job` 的 Job：

```bash
kubectl run busybox-job --image=busybox --restart=OnFailure -- echo "Hello from Kubernetes"
```

- `--restart=OnFailure` 标志指定这是一个 Job。
- `echo` 命令定义了 Job 将执行的任务。

2. **检查 Job 状态**

运行以下命令以验证 Job：

```bash
kubectl get jobs
```

预期输出：

```
NAME          COMPLETIONS   DURATION   AGE
busybox-job   1/1           5s         10s
```

- `COMPLETIONS`：显示 Job 成功运行了一次（`1/1`）。
- 如果未列出 Job，它可能已被自动清理。请继续下一步以检查其 Pod。

3. **验证 Job 的 Pod**

由于 Job 在 Pod 中运行，使用以下命令验证 Pod：

```bash
kubectl get pods
```

预期输出：

```
NAME               READY   STATUS      RESTARTS   AGE
busybox-job        0/1     Completed   0          30s
```

- `STATUS` 字段应显示 `Completed`，表示 Job 已完成。

4. **检查 Job 输出**

检查 Job 的 Pod 日志以验证输出：

```bash
kubectl logs busybox-job
```

预期输出：

```
Hello from Kubernetes
```

这确认 Job 已成功执行。
