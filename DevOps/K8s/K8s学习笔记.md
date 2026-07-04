---
title: "K8s学习笔记"
date: 2026-06-27
---
## 资源调度

### 标签和选择器：Label与Selector的使用

#### Label

在各类资源的 `metadata.labels` 中进行配置

```yaml
metadata:
  labels:
    app: nginx-deploy
```

kubectl 配置

```bash
# 临时修改label
kubectl label po <资源名称> app=hello

# 修改已经存在的标签
kubectl label po <资源名称> app=hello2 --overwrite

# selector 按照 label 单值查找节点  
kubectl get po -A -l app=hello  

# 查看所有节点的 labels  
kubectl get po --show-labels
```

#### Selector

在各对象的配置 `spec.selector` 或其他可以写 `selector` 的属性中编写

```yaml
spec:
  selector:
    matchLabels:
      app: nginx-deploy
```

kubectl 选择

```bash
# 匹配单个值，查找 app=hello 的 pod  
kubectl get po -A -l app=hello  

# 匹配多个值  
kubectl get po -A -l 'k8s-app in (metrics-server, kubernetes-dashboard)'  
或   

# 查找 version!=1 and app=nginx 的 pod 信息  
kubectl get po -l version!=1,app=nginx  

# 不等值 + 语句  
kubectl get po -A -l version!=1,'app in (busybox, nginx)'
```

### Deployment

配置文件

```yaml
apiVersion: apps/v1 # deployment api 版本
kind: Deployment # 资源类型为 deployment
metadata: # 元信息
  labels: # 标签
    app: nginx-deploy # 具体的 key: value 配置形式
  name: nginx-deploy # deployment 的名字
  namespace: default # 所在的命名空间
spec:
  replicas: 1 # 期望副本数
  revisionHistoryLimit: 10 # 进行滚动更新后，保留的历史版本数
  selector: # 选择器，用于找到匹配的 RS
    matchLabels: # 按照标签匹配
      app: nginx-deploy # 匹配的标签key/value
  strategy: # 更新策略
    rollingUpdate: # 滚动更新配置
      maxSurge: 25% # 进行滚动更新时，更新的个数最多可以超过期望副本数的个数/比例
      maxUnavailable: 25% # 进行滚动更新时，最大不可用比例更新比例，表示在所有副本数中，最多可以有多少个不更新成功
    type: RollingUpdate # 更新类型，采用滚动更新
  template: # pod 模板
    metadata: # pod 的元信息
      labels: # pod 的标签
        app: nginx-deploy
    spec: # pod 期望信息
      containers: # pod 的容器
      - image: nginx:1.7.9 # 镜像
        imagePullPolicy: IfNotPresent # 拉取策略
        name: nginx # 容器名称
      restartPolicy: Always # 重启策略
      terminationGracePeriodSeconds: 30 # 删除操作最多宽限多长时间
```

#### 创建

```bash
# 创建一个 deployment  
kubectl create deploy nginx-deploy --image=nginx:1.7.9  

# 或执行  
kubectl create -f xxx.yaml --record  
# --record 会在 annotation 中记录当前命令创建或升级了资源，后续可以查看做过哪些变动操作。  

# 查看部署信息  
kubectl get deployments  

# 查看 rs  
kubectl get rs  
  
# 查看 pod 以及展示标签，可以看到是关联的那个 rs  
kubectl get pods --show-labels
```

#### 扩容缩容

实现扩容/缩容

```bash
kubectl scale deploy nginx-deploy --replicas=6

# 编辑 replcas
kube edit deploy nginx-deploy
```

扩容与缩容只是直接创建副本数，没有更新 pod template 因此不会创建新的 rs

#### 滚动更新

只有修改了 deployment 配置文件中的 template 中的属性后，才会触发更新操作  

```bash
# 修改 nginx 版本号
kubectl set image deployment/nginx-deployment nginx=nginx:1.9.1

# 或者通过 edit 进行修改
kubectl edit deployment/nginx-deployment

# 查看滚动更新的过程
kubectl rollout status deploy <deployment_name>  

# 查看部署描述，最后展示发生的事件列表也可以看到滚动更新过程  
kubectl describe deploy <deployment_name>  

# 获取部署信息，UP-TO-DATE 表示已经有多少副本达到了配置中要求的数目
kubectl get deployments

# 可以看到增加了一个新的 rs
kubectl get rs

# 可以看到所有 pod 关联的 rs 更新了
kubectl get pods
```

#### 回滚

默认情况下，kubernetes会在系统中保存前两次的Deployment的rollout历史记录

修改 `revision history limit` 来更改保存的 `revision` 数

案例：
更新 deployment 时参数不小心写错，如 nginx:1.9.1 写成了 nginx:1.91

```bash
kubectl set image deployment/nginx-deploy nginx=nginx:1.91  
```

监控滚动升级状态，由于镜像名称错误，下载镜像失败，因此更新过程会卡住

```bash
kubectl rollout status deployments nginx-deploy
```

结束监听后，获取 rs 信息，我们可以看到新增的 rs 副本数是 2 个

```bash
kubectl get rs
```

获取 pods 信息，我们可以看到关联到新的 rs 的 pod，状态处于 `ImagePullBackOff` 状态

```bash
kubectl get pods
```
  
为了修复这个问题，我们需要找到需要回退的 revision 进行回退

获取 revison 的列表
```bash
kubectl rollout history deployment/nginx-deploy
```

查看详细信息

```bash
kubectl rollout history deployment/nginx-deploy --revision=2
```
  
确认要回退的版本后，可以通过  可以回退到上一个版本  

```bash
# 回退到上一个版本
kubectl rollout undo deployment/nginx-deploy

# 回退到指定的 revision
kubectl rollout undo deployment/nginx-deploy --to-revision=2
```

查看信息

```bash
kubectl get deployment
kubectl describe deployment
```

通过设置 `.spec.revisonHistoryLimit` 来指定 deployment 保留多少 revison
如果设置为 0，则不允许 deployment 回退

#### 暂停与恢复

```bash
# 暂停更新
kubectl rollout pause deployment <name>

# edit操作，期间不会应用变化

# 恢复更新
kubectl rollout resume deployment <name>
```

### StatefulSet
