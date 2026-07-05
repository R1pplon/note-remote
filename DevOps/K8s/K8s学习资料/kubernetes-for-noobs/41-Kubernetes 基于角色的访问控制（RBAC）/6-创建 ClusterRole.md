# 创建 ClusterRole

创建一个名为 `myapp-admin` 的 ClusterRole，允许用户在所有命名空间中创建、删除和更新 Pod 和 Service。使用以下名为 `myapp-admin-clusterrole.yaml` 的 YAML 文件：

```yml
kind: ClusterRole
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: myapp-admin
rules:
  - apiGroups: [""]
    resources: ["pods", "services"]
    verbs: ["get", "list", "watch", "create", "update", "delete"]
```

此 ClusterRole 允许用户对所有命名空间中的 Pod 和 Service 执行所有操作（get、list、watch、create、update 和 delete）。

使用以下命令创建 ClusterRole：

```shell
kubectl apply -f myapp-admin-clusterrole.yaml
```
