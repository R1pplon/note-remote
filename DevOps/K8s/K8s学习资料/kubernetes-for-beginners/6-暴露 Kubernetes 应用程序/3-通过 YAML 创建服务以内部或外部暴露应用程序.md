# 通过 YAML 创建服务以内部或外部暴露应用程序

在这一步中，你将学习如何创建 Kubernetes 服务以内部和外部暴露你的 NGINX 部署。我们将演示两种常见的服务类型：ClusterIP 和 NodePort。

首先，导航到你的项目目录：

```bash
cd ~/project/k8s-manifests
```

创建一个 ClusterIP 服务的 YAML 文件：

```bash
nano nginx-clusterip-service.yaml
```

添加以下服务清单内容：

```yml
apiVersion: v1
kind: Service
metadata:
  name: nginx-clusterip-service
spec:
  selector:
    app: nginx
  type: ClusterIP
  ports:
    - port: 80
      targetPort: 80
```

现在，创建一个 NodePort 服务的 YAML 文件：

```bash
nano nginx-nodeport-service.yaml
```

添加以下服务清单内容：

```yml
apiVersion: v1
kind: Service
metadata:
  name: nginx-nodeport-service
spec:
  selector:
    app: nginx
  type: NodePort
  ports:
    - port: 80
      targetPort: 80
      nodePort: 30080
```

应用这两个服务配置：

```bash
kubectl apply -f nginx-clusterip-service.yaml
kubectl apply -f nginx-nodeport-service.yaml
```

示例输出：

```
service/nginx-clusterip-service created
service/nginx-nodeport-service created
```

验证服务：

```bash
kubectl get services
```

示例输出：

```
NAME                        TYPE        CLUSTER-IP       EXTERNAL-IP   PORT(S)        AGE
kubernetes                  ClusterIP   10.96.0.1        <none>        443/TCP        30m
nginx-clusterip-service     ClusterIP   10.104.xxx.xxx   <none>        80/TCP         1m
nginx-nodeport-service      NodePort    10.108.yyy.yyy   <none>        80:30080/TCP   1m
```

要访问 NodePort 服务，获取 Minikube 的 IP 地址：

```bash
minikube ip
```

示例输出：

```
192.168.49.2
```

服务类型的关键区别：

- ClusterIP：仅限集群内部访问
- NodePort：在每个节点的 IP 上通过静态端口暴露服务
- NodePort 范围：30000-32767
