---
title: "修复 Pod"
date: 2026-06-20
---

# 修复 Pod

既然我们找到了根本原因，就可以进行修复了。

最后这一步也展示了一个更广泛的模式：观察、根据证据形成假设、进行一次有针对性的修改，然后验证恢复情况。这种严谨的循环比同时进行多次推测性修改要可靠得多。

再次打开 `broken-pod.yaml` 文件：

```bash
nano broken-pod.yaml
```

纠正这个「拼写错误」。将 `nginx:wrongtag123` 改为 `nginx`（默认指向 `nginx:latest`，这是一个有效的镜像）。

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: broken-pod
spec:
  containers:
    - name: nginx
      image: nginx
```

保存并退出。

应用修复。Kubernetes 足够智能，能够使用新信息更新现有的 Pod。

```bash
kubectl apply -f broken-pod.yaml
```

现在，观察恢复过程：

```bash
kubectl get pods -w
```

你应该会看到状态从 `ImagePullBackOff` 变为 `Running`。

按 `Ctrl+C` 停止观察。

干得好，侦探！你识别出了错误并成功恢复了服务。
