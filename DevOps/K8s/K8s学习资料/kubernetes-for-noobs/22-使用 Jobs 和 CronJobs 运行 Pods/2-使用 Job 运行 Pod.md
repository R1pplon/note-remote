---
title: "使用 Job 运行 Pod"
date: 2026-06-20
---

# 使用 Job 运行 Pod

第一步是创建一个运行 Job 的 Pod。在本示例中，我们将创建一个 Pod，运行一个命令以在控制台打印 "Hello, World!"。

在 `/home/labex/project/` 目录下创建一个名为 `job.yaml` 的文件，内容如下：

```yml
apiVersion: batch/v1
kind: Job
metadata:
  name: hello-job
spec:
  template:
    spec:
      containers:
        - name: hello
          image: busybox
          command: ["sh", "-c", 'echo "Hello, World!"']
      restartPolicy: Never
  backoffLimit: 4
```

在此文件中，我们定义了一个名为 `hello-job` 的 Job，它运行一个名为 `hello` 的容器。该容器使用 `busybox` 镜像，并执行一个命令以在控制台打印 "Hello, World!"。

要创建 Job，请运行以下命令：

```shell
kubectl apply -f job.yaml
```

你可以通过运行以下命令检查 Job 的状态：

```shell
kubectl get jobs
```

Job 完成后，你可以通过运行以下命令查看 Pod 的日志：

```shell
kubectl logs <POD_NAME>
```

将 `<POD_NAME>` 替换为运行 Job 的 Pod 的名称，你可以通过 `kubectl get pods |grep hello-job` 命令获取 `<POD_NAME>`。

恭喜，你已成功在 Kubernetes 中使用 Job 运行了一个 Pod！
