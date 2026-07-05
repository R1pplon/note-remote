# 测试 HorizontalPodAutoscaler

现在我们已经创建了 HorizontalPodAutoscaler，可以通过在 Deployment 上生成负载来测试它。

1. 启用 metrics-server：

```bash
minikube addons enable metrics-server
```

2. 创建一个负载生成 Pod：

```bash
kubectl run -i --tty load-generator --image=busybox /bin/sh
```

3. 在负载生成 Pod 中，运行以下命令以在 Deployment 上生成负载：

```bash
while true; do wget -q -O- http://hpa-demo; done
```

4. 打开另一个终端，检查 HorizontalPodAutoscaler 的状态：

```bash
kubectl get hpa
```

你可以看到 `hpa-demo` 的副本数已扩展到 `10`。你可以使用以下命令检查副本数量：

```bash
kubectl get pods -l app=hpa-demo
```

5. 在负载生成 Pod 中按 `ctrl+c` 停止负载生成。
