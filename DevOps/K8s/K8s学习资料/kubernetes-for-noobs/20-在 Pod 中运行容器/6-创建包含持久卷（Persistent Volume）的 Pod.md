# 创建包含持久卷（Persistent Volume）的 Pod

第五步是创建一个包含持久卷（Persistent Volume, PV）和持久卷声明（Persistent Volume Claim, PVC）的 Pod。PV 和 PVC 用于在 Pod 重启时持久存储和访问数据。

为此，你首先需要创建一个 PV。

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

将上述代码保存到名为 `/home/labex/project/pv.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f /home/labex/project/pv.yaml
```

此命令将创建一个名为 `my-pv` 的 PV，容量为 1Gi，主机路径为 `/mnt/data`。

接下来，你需要创建一个 PVC，从 PV 中请求 1Gi 的存储空间。

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

将上述代码保存到名为 `/home/labex/project/pvc.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f /home/labex/project/pvc.yaml
```

此命令将创建一个名为 `my-pvc` 的 PVC，请求 1Gi 的存储空间。

最后，你需要修改 YAML 文件，向 Nginx 容器添加一个卷和卷挂载。

```yml
apiVersion: v1
kind: Pod
metadata:
  name: my-pod-5
spec:
  containers:
    - name: my-container
      image: nginx
      volumeMounts:
        - name: my-volume
          mountPath: /mnt/data
  volumes:
    - name: my-volume
      persistentVolumeClaim:
        claimName: my-pvc
```

将上述代码保存到名为 `/home/labex/project/pod-pv.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f /home/labex/project/pod-pv.yaml
```

此命令将创建一个名为 `my-pod-5` 的 Pod，其中包含一个名为 `my-container` 的容器，该容器运行 Nginx 镜像，并在 `/mnt/data` 处挂载了一个由 PVC `my-pvc` 支持的卷。
