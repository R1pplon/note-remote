# 使用多个键值对注解 Pod

在这一步骤中，我们将探索如何使用 `kubectl annotate` 命令为 Pod 添加多个注解。

1. 使用 `kubectl annotate` 命令为 Pod 添加多个注解：

```bash
kubectl annotate pod my-pod my-annotation-key-1=my-annotation-value-1 my-annotation-key-2=my-annotation-value-2
```

2. 验证注解是否已添加到 Pod：

```bash
kubectl describe pod my-pod | grep my-annotation-key
```

你应该会在输出中看到注解 `my-annotation-key-1` 和 `my-annotation-key-2` 及其对应的值。
