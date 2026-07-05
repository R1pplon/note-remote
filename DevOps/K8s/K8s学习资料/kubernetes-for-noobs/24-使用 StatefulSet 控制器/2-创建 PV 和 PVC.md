# 创建 PV 和 PVC

第一步是创建一个带有持久卷（Persistent Volume, PV）和持久卷声明（Persistent Volume Claim, PVC）的 Pod。PV 和 PVC 用于在 Pod 重启时持久化存储和访问数据。

为此，你需要首先创建一个 PV。

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
  hostPath:
    path: "/mnt/data"
```

将上述代码保存到名为 `pv.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f pv.yaml
```

此命令将创建一个名为 `my-pv` 的 PV，容量为 1Gi，主机路径为 `/mnt/data`。

接下来，你将创建一个 PVC，从 PV 中请求 1Gi 的存储空间。

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

将上述代码保存到名为 `pvc.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f pvc.yaml
```

此命令将创建一个名为 `my-pvc` 的 PVC，请求 1Gi 的存储空间。
