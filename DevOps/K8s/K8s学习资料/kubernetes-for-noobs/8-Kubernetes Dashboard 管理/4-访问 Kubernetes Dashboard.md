# 访问 Kubernetes Dashboard

现在 Dashboard 已运行且权限已配置，可以通过浏览器访问它。

1. 生成登录令牌：

   ```bash
   kubectl -n kubernetes-dashboard create token admin-user
   ```

   复制生成的令牌。这将用于登录。

2. 编辑 Dashboard Service 以将其暴露在 NodePort 上：

   ```bash
   kubectl edit service -n kubernetes-dashboard kubernetes-dashboard
   ```

   找到 `spec` 下的 `type` 字段，并将其值更改为 `NodePort`。保存并退出。

   ![编辑 Dashboard Service NodePort](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-kubernetes-dashboard/zh/../assets/screenshot-20241205-7r3e3Wre@2x.png)

3. 查找分配给 Dashboard 的 NodePort：

   ```bash
   kubectl get service -n kubernetes-dashboard
   ```

   ```plaintext
   NAME                        TYPE        CLUSTER-IP       EXTERNAL-IP   PORT(S)         AGE
   dashboard-metrics-scraper   ClusterIP   10.104.164.172   <none>        8000/TCP        20m
   kubernetes-dashboard        NodePort    10.108.222.153   <none>        443:30587/TCP   20m
   ```

   注意 `PORT(S)` 列下列出的端口号（例如 `30587`）。这是你将用于访问 Dashboard 的端口。

4. 获取节点的 IP 地址：

   ```bash
   kubectl get node -o wide
   ```

   ```plaintext
   NAME       STATUS   ROLES           AGE   VERSION   INTERNAL-IP    EXTERNAL-IP   OS-IMAGE             KERNEL-VERSION      CONTAINER-RUNTIME
   minikube   Ready    control-plane   35m   v1.26.1   192.168.58.2   <none>        Ubuntu 20.04.5 LTS   5.15.0-56-generic   docker://20.10.23
   ```

   `INTERNAL-IP` 列提供了节点的 IP 地址。

5. 在桌面上打开 Firefox 浏览器并导航到以下 URL：

   ```
   https://<node-ip>:<node-port>
   ```

   将 `<node-ip>` 替换为节点的 IP 地址，将 `<node-port>` 替换为步骤 3 中的端口。

   ![Firefox 浏览器 Kubernetes Dashboard](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-kubernetes-dashboard/zh/../assets/screenshot-20241205-THSizOSB@2x.png)

6. 如果出现安全警告提示，请选择“高级”并点击“接受风险并继续”。

![安全警告继续页面](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-kubernetes-dashboard/zh/../assets/screenshot-20241205-J96uOzK1@2x.png)

7. 在登录页面上，选择 **Token** 选项，粘贴（右键点击）步骤 1 中的令牌，然后点击“登录”。

![Kubernetes Dashboard 登录页面](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-kubernetes-dashboard/zh/../assets/screenshot-20241205-geHMkgrW@2x.png)

你现在应该已登录到 Kubernetes Dashboard，可以浏览集群资源并管理工作负载。

![Kubernetes Dashboard 界面](https://file.labex.io/namespace/33fa8aba-d546-42e9-9692-64968aeaf0cc/kubernetes/lab-kubernetes-dashboard/zh/../assets/screenshot-20241205-WK9OYtbT@2x.png)
