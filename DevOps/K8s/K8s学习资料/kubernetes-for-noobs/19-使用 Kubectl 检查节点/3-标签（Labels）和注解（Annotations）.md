# 标签（Labels）和注解（Annotations）

标签和注解可用于为集群中的节点添加元数据。这些元数据可用于选择特定任务的节点或根据某些条件过滤节点。

1. 要查看特定节点的标签和注解，请运行以下命令：

   ```bash
   kubectl get node minikube --show-labels=true
   ```

   这将显示指定节点的标签和注解。

2. 要为节点添加标签，请运行以下命令：

   ```bash
   kubectl label node minikube org=labex
   ```

3. 要为节点添加注解，请运行以下命令：

   ```bash
   kubectl annotate node minikube environment=production
   ```

4. 使用以下命令检查节点上的标签：

   ```bash
   kubectl get nodes --show-labels
   ```

   这将输出集群中所有节点的列表及其标签。可以为节点添加标签以帮助识别其用途或特性。
