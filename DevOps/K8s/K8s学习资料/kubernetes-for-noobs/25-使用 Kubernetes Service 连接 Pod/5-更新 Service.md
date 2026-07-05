# 更新 Service

第四步是更新 Service，使其指向另一组 Pod。将 `/home/labex/project/service.yaml` 文件中的 `selector` 字段更新为以下内容：

```yml
apiVersion: v1
kind: Service
metadata:
  name: my-service
spec:
  selector:
    app: busybox
  ports:
    - name: http
      port: 80
      targetPort: 8
```

保存文件后，运行以下命令以更新 Service：

```bash
kubectl apply -f service.yaml
```

这将更新 Service，使其指向带有标签 `app=busybox` 的 Pod。
