---
title: "docker"
date: 2026-07-02
---
## 基础操作

### 镜像

```bash
# 列出镜像
docker images

# 删除镜像
docker rmi <镜像>

# 拉取镜像
docker pull <镜像>

# 搜索镜像
docker search <镜像>

# 保存镜像
docker save <镜像>  > <文件名.tar>

# 加载镜像
docker load < <文件名.tar>

# 镜像标记
# 不会创建新镜像，只是为现有镜像创建新的名称
docker tag nginx:latest my-nginx:v1
```

### 容器

```bash
# 列出运行中的容器
docker ps

# 列出所有容器
docker ps -a

# 容器操作
docker stop <容器>
docker start <容器>
docker restart <容器>

# 进入容器执行命令
docker exec <容器> <命令>

# 查看容器日志
docker logs <容器>
docker logs -f <容器> 
docker logs --tail 10 <容器>

# 查看端口映射
docker port <容器>
```

## 网络

```bash
# 列出网络配置
docker network ls

# 创建网络配置
docker network create <网络配置名称> --driver <模式，默认bridge>

# 删除网络配置
docker network rm <网络配置名称>

# 删除所有未使用的网络配置
docker network prune

# 配置已有容器
docker network connect <网络配置名称> <容器>
docker network disconnect <网络配置名称> <容器>

# 查看信息
# docker network inspect <网络配置名称>
```

创建网络配置

```

```

## volume

```bash
# 列出数据卷
docker volume ls

# 创建数据卷
docker volume create

# 删除数据卷
docker volume rm <数据卷>

# 删除未使用的匿名数据卷
docker volume prune

# 只能用于更新集群卷
docker volume update
```



## docker run

```bash
# 基本语法
docker run [OPTIONS] IMAGE [COMMAND] [ARG...]
```

### 后台运行

```bash
docker run -d --name my-nginx nginx
```

- `-d`：后台运行（detached mode）
- `--name`：自定义容器名（未指定时自动生成）

### 交互运行

```bash
docker run -it --name ubuntu-interactive ubuntu /bin/bash
```

- `-it`：以交互模式运行容器并分配一个伪终端（pseudo-TTY）

### 进入交互式shell

先创建一个能够在后台运行的ubuntu容器
```bash
docker run --name test-ubuntu -d ubuntu sleep infinity
```
- `sleep infinity` 让进程**无限期挂起**

获取一个交互式 shell
```bash
docker exec -it test-ubuntu /bin/bash
```

### 端口映射

```bash
docker run -d -p 8080:80 nginx
```

- `-p 宿主机端口:容器端口`  

### 卷挂载（数据共享/持久化）

```bash
docker run -d -v /host/path:/container/path nginx
```

- 宿主机目录必须使用绝对路径
- 示例：挂载自定义网页

  ```bash
  docker run -d -p 8081:80 -v ~/data:/usr/share/nginx/html nginx
  ```

### 在容器与宿主机之间复制文件

```bash
docker cp /host/path/file [容器]:/container/path/file

docker cp hello.html nginx-detached:/usr/share/nginx/html/hello.html
```

### 环境变量

```bash
docker run -d -e VAR1=value1 -e VAR2=value2 nginx
```

- `-e` 可多次使用
- 查看变量：`docker exec <容器> env | grep VAR`

### 资源限制

```bash
docker run -d --memory 256m --cpus 0.5 nginx
```

- `--memory`：内存限制（单位 m/g）
- `--cpus`：CPU 核心数（0.5 表示半个核）
- 查看限制：
  ```bash
  docker inspect -f '{{.HostConfig.Memory}}' <容器>
  docker inspect -f '{{.HostConfig.NanoCpus}}' <容器>
  ```

### 网络设置
```bash
docker network create my-net
docker run -d --network my-net nginx
```
- 同一网络下容器可通过容器名互访

### 重启策略
```bash
docker run -d --restart unless-stopped nginx
```
- `no`：不自动重启（默认）
- `on-failure`：非零退出时重启
- `always`：总是重启
- `unless-stopped`：除手动停止外总是重启
- 查看策略：`docker inspect -f '{{.HostConfig.RestartPolicy.Name}}' <容器>`

### 工作目录与启动命令
```bash
docker run -d -w /app nginx sh -c "mkdir -p /app && touch f.txt && nginx -g 'daemon off;'"
```
- `-w`：设置容器内工作目录
- 镜像名后跟命令覆盖默认启动指令

## Dockerfile

```
docker build
```

