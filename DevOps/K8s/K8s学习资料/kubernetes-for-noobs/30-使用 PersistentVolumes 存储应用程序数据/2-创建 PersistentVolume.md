# 创建 PersistentVolume

在此步骤中，你将创建一个可用于存储数据的 PersistentVolume。创建一个名为 `pv.yaml` 的 YAML 文件，内容如下：

```yml
apiVersion: v1
kind: PersistentVolume
metadata:
  name: my-pv
spec:
  capacity:
    storage: 1Gi
  accessModes:
    - ReadWriteOnce
  persistentVolumeReclaimPolicy: Retain
  hostPath:
    path: /mnt/data
```

该文件创建了一个容量为 1Gi、访问模式为 ReadWriteOnce 的 PersistentVolume。`hostPath` 字段指定数据将存储在宿主机的 `/mnt/data` 路径下。`persistentVolumeReclaimPolicy` 字段被设置为 Retain，这意味着即使删除了 PersistentVolume，数据也会被保留。

使用以下命令将 PersistentVolume 应用到你的集群：

```shell
kubectl apply -f pv.yaml
```
