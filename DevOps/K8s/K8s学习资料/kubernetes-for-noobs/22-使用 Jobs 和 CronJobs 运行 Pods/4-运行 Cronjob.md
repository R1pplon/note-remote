---
title: "运行 Cronjob"
date: 2026-06-20
---

# 运行 Cronjob

除了一次性任务（Job），Kubernetes 还支持 CronJob 以定期运行任务。在本示例中，我们将创建一个每分钟运行一次命令的 CronJob。

在 `/home/labex/project/` 目录下创建一个名为 `cronjob.yaml` 的文件，内容如下：

```yml
apiVersion: batch/v1
kind: CronJob
metadata:
  name: hello-cronjob
spec:
  schedule: "*/1 * * * *"
  jobTemplate:
    spec:
      template:
        spec:
          containers:
            - name: hello
              image: busybox
              command: ["sh", "-c", 'echo "Hello, World!"']
          restartPolicy: Never
  successfulJobsHistoryLimit: 3
  failedJobsHistoryLimit: 3
```

在此文件中，我们定义了一个名为 `hello-cronjob` 的 CronJob，它每分钟运行一次命令。该命令与我们在第一个示例中使用的命令相同，用于在控制台打印 "Hello, World!"。

要创建 CronJob，请运行以下命令：

```shell
kubectl apply -f cronjob.yaml
```

你可以通过运行以下命令检查 CronJob 的状态：

```shell
kubectl get cronjobs
```

CronJob 运行后，你可以通过运行以下命令查看 Pod 的日志：

```shell
kubectl logs -f <POD_NAME>
```

将 `<POD_NAME>` 替换为由 CronJob 创建的任意 Pod 的名称，你可以通过 `kubectl get pod |grep hello-cronjob` 命令获取 `<POD_NAME>`。

恭喜，你已成功在 Kubernetes 中运行了一个 CronJob！
