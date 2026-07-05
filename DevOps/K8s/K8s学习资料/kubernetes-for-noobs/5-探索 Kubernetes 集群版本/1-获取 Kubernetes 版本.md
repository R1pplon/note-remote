# 获取 Kubernetes 版本

作为一名初级 DevOps 工程师，你需要核实 Kubernetes 环境的版本详情，以确保系统兼容性并为关键的基础设施升级做准备。

## 任务

- 获取 Kubernetes 集群的版本信息
- 以 JSON 格式显示版本详情
- 验证客户端与服务器版本之间的兼容性

## 要求

- 使用 `kubectl version` 命令
- 以 JSON 格式输出版本信息
- 确保在 `~/project` 目录下进行操作
- 使用 Minikube 作为本地 Kubernetes 集群

## 示例

JSON 输出示例：

```json
{
  "clientVersion": {
    "major": "1",
    "minor": "26",
    "gitVersion": "v1.26.0"
  },
  "serverVersion": {
    "major": "1",
    "minor": "26",
    "gitVersion": "v1.26.0"
  }
}
```

## 提示

- 记得使用 `--output=json` 标志来进行 JSON 格式化
- 查看 Kubernetes 官方文档中关于版本命令的选项
- 在执行版本命令前，请确认集群正在运行
