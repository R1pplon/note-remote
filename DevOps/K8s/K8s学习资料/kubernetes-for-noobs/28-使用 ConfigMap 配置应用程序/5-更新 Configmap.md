# 更新 Configmap

在本步骤中，你将更新 ConfigMap 并观察其对应用程序的影响。

更新 `configmap.yaml` 文件中的 `DATABASE_URL` 键值为新值：

```yml
apiVersion: v1
kind: ConfigMap
metadata:
  name: my-config
data:
  DATABASE_URL: postgres://newuser:newpassword@newhost:newport/newdbname
```

这将 `DATABASE_URL` 键更新为一个新值。

要更新 ConfigMap，请运行以下命令：

```bash
kubectl apply -f configmap.yaml
```
