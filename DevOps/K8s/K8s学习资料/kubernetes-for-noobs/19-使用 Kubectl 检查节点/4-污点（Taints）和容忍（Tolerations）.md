# 污点（Taints）和容忍（Tolerations）

污点和容忍可用于控制哪些 Pod 可以调度到集群中的哪些节点上。污点是一种特殊的标签，用于标记节点不适合某些类型的 Pod，而容忍是一种设置，允许 Pod 调度到具有匹配污点的节点上。

1. 要查看特定节点的污点，请运行以下命令：

   ```bash
   kubectl describe node minikube | grep Taints
   ```

   这将显示指定节点的污点。

2. 要为节点添加污点，请运行以下命令：

   ```bash
   kubectl taint node minikube app=backend:NoSchedule
   ```

3. 为 Pod 创建容忍，请运行以下命令：

   ```bash
   cat << EOF | kubectl apply -f -
   apiVersion: v1
   kind: Pod
   metadata:
     name: my-pod
   spec:
     containers:
       - name: my-container
         image: nginx
     tolerations:
       - key: app
         operator: Exists
         effect: NoSchedule
   EOF
   ```

   此 Pod 使用 `app` 作为污点的名称，并使用 `NoSchedule` 作为污点的效果。
