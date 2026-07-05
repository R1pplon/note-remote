---
title: "创建 Configmap"
date: 2026-06-20
---

# 创建 Configmap

在本步骤中，你将创建一个包含应用程序配置数据的 ConfigMap。

在 `/home/labex/project/` 目录下创建一个名为 `configmap.yaml` 的文件，内容如下：

```yml
apiVersion: v1
kind: ConfigMap
metadata:
  name: my-config
data:
  DATABASE_URL: postgres://user:password@host:port/dbname
```

此 ConfigMap 包含一个键值对，其中键为 `DATABASE_URL`，值为 PostgreSQL 数据库连接字符串。

要创建 ConfigMap，请运行以下命令：

```bash
kubectl apply -f configmap.yaml
```
