# 修改 PVC 存储资源

在此步骤中，你将修改 PVC 以从 PersistentVolume 请求特定的存储资源。你将修改 `pvc.yaml` 文件，将存储请求从 500Mi 改为 1Gi。

```yml
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: my-pvc
spec:
  accessModes:
    - ReadWriteOnce
  resources:
    requests:
      storage: 1Gi
```

在最后一行，为 storageclass 添加 `allowVolumeExpansion: true` 字段。

```shell
kubectl edit storageclass standard
```

![PVC 存储修改示例](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-storing-application-data-with-persistentvolumes/zh/../assets/lab-storing-application-data-with-persistentvolumes-5.png)

使用以下命令将更新后的 PersistentVolumeClaim 应用到你的集群：

```shell
kubectl delete deployment web-app
kubectl delete pvc my-pvc
kubectl apply -f web-app.yaml
kubectl apply -f pvc.yaml
```
