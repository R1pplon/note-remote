# 排查 Nginx 部署问题

你是一名初级 DevOps 工程师，负责诊断和解决 Kubernetes 集群中一个出现问题的 Nginx 部署。你的目标是识别并修复阻止部署正常运行的配置问题。

## 准备工作

启动并向你的 Kubernetes 集群应用部署清单文件：

```bash
minikube start
kubectl apply -f ~/project/k8s-manifests/broken-nginx-deployment.yaml
```

## 任务

- 调查 Nginx 部署未能正常运行的原因
- 使用 `kubectl` 命令诊断部署存在的问题
- 修改部署清单文件，确保所有副本都处于运行状态
- 应用修正后的部署配置

## 要求

- 使用位于 `~/project/k8s-manifests/broken-nginx-deployment.yaml` 的部署清单文件
- 使用 `kubectl` 命令诊断部署
- 确保所有 3 个副本都成功运行（请耐心等待，这可能需要几分钟时间）
- 不要删除原始的部署清单文件
- 仅进行最小必要的更改以使部署运行起来

## 示例

成功的部署状态：

```
NAME            READY   UP-TO-DATE   AVAILABLE   AGE
broken-nginx    3/3     3            3           1m
```

在所有 3 个副本运行后，你可以点击 **Continue** 按钮来验证你的解决方案。

## 提示

- 使用 `kubectl describe deployment` 来了解 Pod 未运行的原因
- 检查部署的事件（events）和状态（conditions）
- 验证容器镜像是否正确且可用
- 使用 `kubectl get events` 获取有关部署问题的更多上下文信息
