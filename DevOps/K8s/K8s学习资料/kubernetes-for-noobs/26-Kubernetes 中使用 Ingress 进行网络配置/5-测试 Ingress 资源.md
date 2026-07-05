# 测试 Ingress 资源

最后，我们可以测试 Ingress 资源，以确保一切正常运行。

首先，确定节点的 IP 地址：

```bash
kubectl get node -o wide
```

```plaintext
NAME       STATUS   ROLES           AGE   VERSION   INTERNAL-IP    EXTERNAL-IP   OS-IMAGE             KERNEL-VERSION      CONTAINER-RUNTIME
minikube   Ready    control-plane   93s   v1.26.1   192.168.49.2   <none>        Ubuntu 20.04.5 LTS   5.15.0-56-generic   docker://20.10.23
```

此命令将获取 Kubernetes 节点的地址，标记为 `INTERNAL-IP` 的 IP 地址。

接下来，在 `/etc/hosts` 文件中添加一个条目，将 `test.local` 域名映射到节点的 IP 地址：

```
echo "<IP_ADDRESS> test.local" | sudo tee -a /etc/hosts
```

将 `<IP_ADDRESS>` 替换为节点的内部 IP 地址。例如：

```bash
echo "192.168.49.2 test.local" | sudo tee -a /etc/hosts
```

然后，获取 `ingress-nginx` 的 Service NodePort。

```bash
kubectl get services -n ingress-nginx
```

此命令将显示 `ingress-nginx` 命名空间中的服务列表。找到 `nginx-ingress-controller` 服务并记下其 `NodePort`。

```plaintext
NAME                                 TYPE           CLUSTER-IP      EXTERNAL-IP   PORT(S)                      AGE
ingress-nginx-controller             LoadBalancer   10.104.99.158   <pending>     80:32518/TCP,443:31620/TCP   2m45s
ingress-nginx-controller-admission   ClusterIP      10.100.46.109   <none>        443/TCP                      2m45s
```

最后，使用 `curl` 向 Ingress 端点发送 HTTP 请求：

```bash
curl test.local:NodePort
```

例如：

```bash
curl test.local:32518
```

将 `<NodePort>` 替换为 `nginx-ingress-controller` 服务的 `NodePort`。

如果一切设置正确，你应该会看到 Nginx 的欢迎页面。

你还可以通过使用 Web 浏览器访问 `test.local:<NodePort>/nginx` 来测试 Ingress。

恭喜，你已成功在 Kubernetes 中设置了 Ingress 资源并进行了测试，确保其正常运行。
