---
title: "运行多 Pod 的 Job"
date: 2026-06-20
---

# 运行多 Pod 的 Job

在某些情况下，你可能需要运行一个包含多个 Pod 的 Job 以提高性能。在本示例中，我们将创建一个 Job，运行多个 Pod 以从远程服务器下载文件。

在 `/home/labex/project/` 目录下创建一个名为 `multi-pod-job.yaml` 的文件，内容如下：

```yml
apiVersion: batch/v1
kind: Job
metadata:
  name: download-job
spec:
  completions: 3
  parallelism: 2
  template:
    spec:
      containers:
        - name: downloader
          image: curlimages/curl
          command: ["curl", "-o", "/data/file", "http://example.com/file"]
          volumeMounts:
            - name: data-volume
              mountPath: /data
      restartPolicy: Never
      volumes:
        - name: data-volume
          emptyDir: {}
  backoffLimit: 4
```

在此文件中，我们定义了一个名为 `download-job` 的 Job，它使用 `curlimages/curl` 镜像运行多个 Pod。每个 Pod 从 `http://example.com/file` 下载文件并将其保存到名为 `data-volume` 的共享卷中。

要创建 Job，请运行以下命令：

```shell
kubectl apply -f multi-pod-job.yaml
```

你可以通过运行以下命令检查 Job 的状态：

```shell
kubectl get jobs
```

Job 完成后，你可以通过运行以下命令查看 Pod 的日志：

```shell
kubectl logs <POD_NAME>
```

将 `<POD_NAME>` 替换为运行 Job 的任意 Pod 的名称。你可以查看文件的下载日志，并通过 `kubectl get pod |grep download-job` 命令获取 `<POD_NAME>`。

恭喜，你已成功在 Kubernetes 中运行了一个包含多个 Pod 的 Job！
