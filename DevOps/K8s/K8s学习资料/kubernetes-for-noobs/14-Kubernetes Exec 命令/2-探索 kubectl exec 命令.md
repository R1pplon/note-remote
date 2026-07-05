---
title: "探索 kubectl exec 命令"
date: 2026-06-20
---

# 探索 kubectl exec 命令

`kubectl exec` 命令用于直接在 pod 中的容器内执行命令。它在调试和检查容器环境时特别有用。

运行以下命令以查看 `kubectl exec` 的可用选项：

```bash
kubectl exec -h
```

你将看到以下输出：

```plaintext
在容器中执行命令。

示例：
  # 从 pod mypod 中运行 'date' 命令并获取输出，默认使用第一个容器
  kubectl exec mypod -- date

  # 从 pod mypod 的 ruby-container 容器中运行 'date' 命令并获取输出
  kubectl exec mypod -c ruby-container -- date

  # 切换到原始终端模式；将 stdin 发送到 pod mypod 的 ruby-container 容器中的 'bash'
  # 并将 'bash' 的 stdout/stderr 发送回客户端
  kubectl exec mypod -c ruby-container -i -t -- bash -il

  # 列出 pod mypod 的第一个容器中 /usr 目录的内容并按修改时间排序
  # 如果你想在 pod 中执行的命令有任何共同的标志（例如 -i），
  # 你必须使用两个破折号（--）来分隔命令的标志/参数
  # 另外请注意，不要用引号包围命令及其标志/参数，
  # 除非这是你通常执行它的方式（即，执行 ls -t /usr，而不是 "ls -t /usr"）
  kubectl exec mypod -i -t -- ls -t /usr

  # 从部署 mydeployment 的第一个 pod 中运行 'date' 命令并获取输出，默认使用第一个容器
  kubectl exec deploy/mydeployment -- date

  # 从服务 myservice 的第一个 pod 中运行 'date' 命令并获取输出，默认使用第一个容器
  kubectl exec svc/myservice -- date
```
