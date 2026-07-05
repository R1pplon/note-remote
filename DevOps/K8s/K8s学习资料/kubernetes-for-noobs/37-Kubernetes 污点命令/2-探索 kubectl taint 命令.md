# 探索 kubectl taint 命令

`kubectl taint` 命令用于在 Kubernetes 节点上添加、修改或删除污点（taint）。污点是带有影响效果的键值对，通过限制哪些 Pod 可以调度到特定节点上来影响 Pod 的调度。

运行以下命令以查看 `kubectl taint` 的可用选项：

```bash
kubectl taint -h
```

你将看到以下输出：

```plaintext
更新一个或多个节点上的污点。

  * 污点由键、值和效果组成。在此处作为参数时，表示为 key=value:effect。
  * 键必须以字母或数字开头，可以包含字母、数字、连字符、点和下划线，最多 253 个字符。
  * 可选地，键可以以 DNS 子域前缀和单个 '/' 开头，例如 example.com/my-app。
  * 值是可选的。如果提供，则必须以字母或数字开头，可以包含字母、数字、连字符、点和下划线，最多 63 个字符。
  * 效果必须是 NoSchedule、PreferNoSchedule 或 NoExecute。
  * 目前污点只能应用于节点。

示例：
  # 使用键 'dedicated'、值 'special-user' 和效果 'NoSchedule' 更新节点 'foo' 的污点
  # 如果具有该键和效果的污点已存在，则其值将按指定替换
  kubectl taint nodes foo dedicated=special-user:NoSchedule

  # 从节点 'foo' 中删除具有键 'dedicated' 和效果 'NoSchedule' 的污点（如果存在）
  kubectl taint nodes foo dedicated:NoSchedule-

  # 从节点 'foo' 中删除所有具有键 'dedicated' 的污点
  kubectl taint nodes foo dedicated-

  # 在具有标签 mylabel=X 的节点上添加键为 'dedicated' 的污点
  kubectl taint node -l myLabel=X dedicated=foo:PreferNoSchedule

  # 向节点 'foo' 添加一个键为 'bar' 且无值的污点
  kubectl taint nodes foo bar:NoSchedule
```
