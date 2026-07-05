---
title: "描述一个 Deployment"
date: 2026-06-20
---

# 描述一个 Deployment

在本步骤中，你将学习如何使用 `describe` 命令查看 Kubernetes Deployment 的信息。

1. 创建一个名为 `myapp-deployment.yaml` 的文件，内容如下：

   ```yml
   apiVersion: apps/v1
   kind: Deployment
   metadata:
     name: myapp-deployment
   spec:
     replicas: 1
     selector:
       matchLabels:
         app: myapp-deployment
     template:
       metadata:
         labels:
           app: myapp-deployment
       spec:
         containers:
           - name: myapp-container
             image: nginx:latest
             ports:
               - containerPort: 80
   ```

   使用以下命令创建 Deployment：

   ```bash
   kubectl apply -f myapp-deployment.yaml
   ```

2. 描述该 Deployment：

   ```bash
   kubectl describe deployment myapp-deployment
   ```

此命令将检索指定 Deployment 的详细信息，包括状态、标签、注解、事件等。
