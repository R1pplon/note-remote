# 描述一个 Service

在本步骤中，你将学习如何使用 `describe` 命令查看 Kubernetes Service 的信息。

1. 创建一个名为 `myapp-service.yaml` 的文件，内容如下：

   ```yml
   apiVersion: v1
   kind: Service
   metadata:
     name: myapp-service
   spec:
     selector:
       app: myapp-deployment
     ports:
       - protocol: TCP
         port: 80
         targetPort: 80
   ```

   使用以下命令创建 Service：

   ```bash
   kubectl apply -f myapp-service.yaml
   ```

2. 使用以下命令描述该 Service：

   ```bash
   kubectl describe service myapp-service
   ```

此命令将检索指定 Service 的详细信息，包括状态、标签、注解、事件等。
