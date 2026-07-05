# 描述一个 Pod

在本步骤中，你将学习如何使用 `describe` 命令查看 Kubernetes Pod 的信息。

1. 创建一个简单的 Pod，作为副本的模板。创建一个名为 `myapp-pod.yaml` 的文件，内容如下：

   ```yml
   apiVersion: v1
   kind: Pod
   metadata:
     name: myapp-pod
   spec:
     containers:
       - name: myapp-container
         image: nginx
         ports:
           - containerPort: 80
   ```

   使用以下命令创建 Pod：

   ```shell
   kubectl apply -f myapp-pod.yaml
   ```

2. 然后描述该 Pod：

   ```bash
   kubectl describe pod myapp-pod
   ```

此命令将检索指定 Pod 的详细信息，包括状态、标签、注解、事件等。
