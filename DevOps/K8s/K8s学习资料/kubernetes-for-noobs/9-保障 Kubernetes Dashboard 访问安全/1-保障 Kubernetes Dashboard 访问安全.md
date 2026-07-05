# 保障 Kubernetes Dashboard 访问安全

作为一名初级 DevOps 工程师，你将通过为 Kubernetes Dashboard 创建一个只读服务账户来增强 Kubernetes 集群的安全性，从而证明你对基于角色的访问控制（RBAC）的掌握。

## 任务

- 使用提供的 YAML 文件，在 `kubernetes-dashboard` 命名空间中创建一个名为 `read-only-user` 的新服务账户。
- 创建一个对默认命名空间具有只读权限的 `ClusterRole`，允许对 `pods`、`services`、`nodes`、`namespaces` 和 `deployments` 执行 `get`、`list` 和 `watch` 操作。
- 将该 `ClusterRole` 绑定到名为 `read-only-user` 的新服务账户上。
- 为该服务账户生成一个用于登录 Dashboard 的令牌。

## 要求

- 在 `~/project` 目录下进行操作。
- 服务账户需使用 `kubernetes-dashboard` 命名空间。
- 创建一个名为 `read-only-dashboard-access.yaml` 的 YAML 文件。
- 该服务账户应仅具备只读权限。
- 将访问范围限制在 `default` 命名空间。

提供 YAML 文件内容如下：

```yml
---
apiVersion: v1
kind: ServiceAccount
metadata:
  name: read-only-user
  namespace: kubernetes-dashboard

---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRole
metadata:
  name: read-only-dashboard-role
rules:
  - apiGroups: [""]
    resources: ["pods", "services", "nodes", "namespaces", "deployments"]
    verbs: ["get", "list", "watch"]

---
apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: read-only-dashboard-access
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: ClusterRole
  name: read-only-dashboard-role
subjects:
  - kind: ServiceAccount
    name: read-only-user
    namespace: kubernetes-dashboard
```

## 示例

服务账户令牌输出示例：

```
eyJhbGciOiJSUzI1NiIsImtpZCI6IiJ9...
```

登录后的 Dashboard 视图示例：

- 可见内容：命名空间资源、部署（Deployments）、容器组（Pods）。
- 不可见内容：创建、编辑、删除等操作。

## 提示

- 启动 Minikube 并应用 Kubernetes Dashboard 官方仓库中的 `recommended.yaml` 文件来部署 Dashboard。
- 使用 `kubectl create` 和 `kubectl apply` 命令。
- 检查 `ClusterRole` 和 `ClusterRoleBinding` 的配置。
- 使用 `kubectl -n kubernetes-dashboard create token read-only-user` 来生成令牌。
