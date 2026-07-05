# 创建一个 Service

第二步是创建一个 Service，该 Service 将指向你在上一步中创建的 Pod。创建一个名为 `/home/labex/project/service.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: Service
metadata:
  name: my-service
spec:
  selector:
    app: nginx
  ports:
    - name: http
      port: 80
      targetPort: 80
```

保存文件后，运行以下命令来创建 Service：

```bash
kubectl apply -f /home/labex/project/service.yaml
```

这将创建一个名为 `my-service` 的 Service，该 Service 会指向带有标签 `app=nginx` 的 Pod，并暴露端口 80。
