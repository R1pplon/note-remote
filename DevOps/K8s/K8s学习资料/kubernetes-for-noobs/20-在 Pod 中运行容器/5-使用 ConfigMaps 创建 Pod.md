# 使用 ConfigMaps 创建 Pod

第四步是使用 ConfigMaps 创建一个 Pod。ConfigMap 是一种 Kubernetes 资源，它允许你将配置数据（如环境变量、配置文件）与应用程序代码分开存储。这种分离方式使得在不重新构建容器的情况下更改配置变得更加容易。

让我们将这个过程分解为简单的步骤：

1. **首先，创建一个 ConfigMap**：

   ```bash
   kubectl create configmap my-config --from-literal=MY_ENV_VAR=labex
   ```

   此命令创建了一个名为 `my-config` 的 ConfigMap，它存储了一个键值对：

   - 键：MY_ENV_VAR
   - 值：labex

   你可以使用以下命令验证 ConfigMap 是否创建成功并查看其内容：

   ```bash
   kubectl get configmap my-config -o yaml
   ```

2. **接下来，创建一个使用此 ConfigMap 的 Pod**：

   ```yml
   apiVersion: v1
   kind: Pod
   metadata:
     name: my-pod-4
   spec:
     containers:
       - name: my-container
         image: nginx
         envFrom:
           - configMapRef:
               name: my-config
   ```

   将此 YAML 文件保存到 `/home/labex/project/pod-configmap.yaml` 并应用它：

   ```bash
   kubectl apply -f /home/labex/project/pod-configmap.yaml
   ```

这将创建一个 Pod，它可以访问我们存储在 ConfigMap 中的配置值。该值将作为环境变量在容器内部可用。你可以通过运行以下命令来验证：

```bash
kubectl exec -it my-pod-4 -- env | grep MY_ENV_VAR
```
