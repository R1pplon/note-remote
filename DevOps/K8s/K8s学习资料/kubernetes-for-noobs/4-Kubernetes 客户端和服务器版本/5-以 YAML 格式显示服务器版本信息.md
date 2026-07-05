# 以 YAML 格式显示服务器版本信息

Kubernetes 通常使用 YAML 作为配置文件和清单的格式，因此它也是人类可读输出的自然选择。要以 YAML 格式显示服务器版本信息，请运行以下命令：

```bash
kubectl version --output=yaml
```

此命令将检索版本信息并将其格式化为 YAML 文档。YAML 对人类来说更易读，并且常用于 Kubernetes 工作流中。

例如，YAML 输出可能如下所示：

```yml
clientVersion:
  major: "1"
  minor: "26"
  gitVersion: v1.26.0
  ...
serverVersion:
  major: "1"
  minor: "26"
  gitVersion: v1.26.0
  ...
```
