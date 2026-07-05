---
title: "创建包含环境变量的 Pod"
date: 2026-06-20
---

# 创建包含环境变量的 Pod

第三步是创建一个包含环境变量的 Pod。为此，你需要修改 YAML 文件以向 Nginx 容器添加环境变量。

```yml
apiVersion: v1
kind: Pod
metadata:
  name: my-pod-3
spec:
  containers:
    - name: my-container
      image: nginx
      env:
        - name: MY_ENV_VAR
          value: "Hello World!"
```

将上述代码保存到名为 `/home/labex/project/pod-env-vars.yaml` 的文件中，并执行以下命令：

```bash
kubectl apply -f /home/labex/project/pod-env-vars.yaml
```

此命令将创建一个名为 `my-pod-3` 的 Pod，其中包含一个名为 `my-container` 的容器，该容器运行 Nginx 镜像，并具有一个名为 `MY_ENV_VAR` 的环境变量，其值为 `Hello World!`。
