# 创建 ClusterRole Binding

创建一个 ClusterRole Binding，将 `myapp-admin` ClusterRole 绑定到集群中的用户或组。例如，将 `myapp-admin` ClusterRole 绑定到 `cluster-admin` 用户，创建以下名为 `myapp-admin-binding.yaml` 的 YAML 文件：

```yml
kind: ClusterRoleBinding
apiVersion: rbac.authorization.k8s.io/v1
metadata:
  name: myapp-admin-binding
subjects:
  - kind: User
    name: cluster-admin
    apiGroup: rbac.authorization.k8s.io
roleRef:
  kind: ClusterRole
  name: myapp-admin
  apiGroup: rbac.authorization.k8s.io
```

此 ClusterRole Binding 将 `myapp-admin` ClusterRole 绑定到 `cluster-admin` 用户。

使用以下命令创建 ClusterRole Binding：

```shell
kubectl apply -f myapp-admin-binding.yaml
```
