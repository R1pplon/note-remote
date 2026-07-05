# 创建 PersistentVolumeClaim

在此步骤中，你将创建一个 PersistentVolumeClaim (PVC)，用于从你在步骤 1 中创建的 PersistentVolume 请求存储空间。创建一个名为 `pvc.yaml` 的 YAML 文件，内容如下：

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
      storage: 500Mi
```

该文件创建了一个访问模式为 ReadWriteOnce 的 PersistentVolumeClaim，并请求从 PersistentVolume 中分配 500Mi 的存储空间。

使用以下命令将 PersistentVolumeClaim 应用到你的集群：

```shell
kubectl apply -f pvc.yaml
```
