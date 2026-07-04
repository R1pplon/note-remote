---
title: "Dockerfile"
date: 2026-07-02
---
## 一、基础与构建指令
### FROM
- **格式**：`FROM <image>[:<tag>]`
- **作用**：指定基础镜像，必须为第一条非注释指令。
- **实操**：后续所有指令都基于此镜像；多次使用可实现多阶段构建。
### LABEL
- **格式**：`LABEL <key>=<value> ...`
- **作用**：添加镜像元数据（如维护者信息）。
- **实操**：替代已弃用的 `MAINTAINER`，例：`LABEL maintainer="you@example.com"`。
### RUN
- **格式**：`RUN <command>`（shell格式） / `RUN ["exec", "arg1"]`（exec格式）
- **作用**：在构建时执行命令，结果形成新的镜像层。
- **实操**：**❗每条 RUN 产生一层**，务必用 `&&` 合并多条命令减少层数，并在结尾清理缓存：
  ```dockerfile
  RUN apt-get update && apt-get install -y vim && rm -rf /var/lib/apt/lists/*
  ```
### ARG
- **格式**：`ARG <name>[=<default>]`
- **作用**：定义构建时变量，仅构建阶段有效，运行时消失。
- **实操**：构建时传参：`docker build --build-arg <name>=<value> .`
### ENV
- **格式**：`ENV <key>=<value> ...`
- **作用**：设置环境变量，构建和运行时均有效。
- **实操**：后续指令可用 `$key` 引用；容器启动后也可读取。
### WORKDIR
- **格式**：`WORKDIR <path>`
- **作用**：设置工作目录（不存在会自动创建）。
- **实操**：影响后续 RUN/CMD/ENTRYPOINT/COPY/ADD 的当前目录。**不要用 `RUN cd /app`，改用 `WORKDIR /app`**。
### USER
- **格式**：`USER <user>[:<group>]`
- **作用**：指定后续命令执行的用户和组。
- **实操**：用户必须提前存在（通常由前面的RUN创建），用于降权运行保障安全。
---
## 二、文件操作指令
### COPY
- **格式**：`COPY [--chown=<user>:<group>] <src>... <dest>`
- **作用**：从构建上下文目录复制文件/目录到镜像。
- **实操**：推荐优先使用。支持通配符（如 `COPY hom* /app/`），目标路径不存在会自动创建。
### ADD
- **格式**：`ADD <src>... <dest>`
- **作用**：复制文件，额外支持自动解压 tar 包和下载远程 URL。
- **实操**：**若不需要自动解压或下载，坚决用 `COPY`**。ADD 会使构建缓存失效，拖慢构建速度。
---
## 三、容器启动指令
### CMD
- **格式**：`CMD <command>`（shell）/ `CMD ["exec", "arg1"]`（exec，推荐）/ `CMD ["arg1"]`（为ENTRYPOINT传参）
- **作用**：容器启动时默认执行的命令。
- **实操**：**可被 `docker run` 后面的命令覆盖**；多个 CMD 只有最后一条生效。
### ENTRYPOINT
- **格式**：`ENTRYPOINT ["exec", "arg1"]`
- **作用**：配置容器启动时的主命令。
- **实操**：**不会被 `docker run` 的命令覆盖**（除非用 `--entrypoint`）。常与 CMD 搭配：ENTRYPOINT 定死可执行程序，CMD 传默认参数。
### EXPOSE
- **格式**：`EXPOSE <port> [<port>...]`
- **作用**：声明容器运行时监听的端口。
- **实操**：**仅声明，不会自动发布**。运行时仍需 `docker run -P`（随机映射）或 `-p <宿主端口>:<容器端口>`（手动映射）。
### VOLUME
- **格式**：`VOLUME ["<path>"]`
- **作用**：创建匿名数据卷挂载点。
- **实操**：防止容器重启数据丢失；运行时可用 `-v` 覆盖挂载路径。
---
## 四、健康检查与触发器
### HEALTHCHECK
- **格式**：`HEALTHCHECK [选项] CMD <command>` / `HEALTHCHECK NONE`
- **作用**：定义容器健康检查命令。
- **实操**：选项包含 `--interval`（间隔）、`--timeout`（超时）、`--retries`（重试次数）。NONE 用于屏蔽基础镜像的健康检查。
### ONBUILD
- **格式**：`ONBUILD <其它指令>`
- **作用**：延迟执行：当前镜像被别人 `FROM` 时才触发执行。
- **实操**：常用于构建基础镜像，让子镜像构建时自动执行某些初始化操作。
---
## 五、核心避坑指南（速记）
1. **层数控制**：RUN/COPY/ADD 都产生新层；合并 RUN 命令，清理无用缓存。
2. **CMD vs ENTRYPOINT**：想可变用 CMD，想像程序一样固死入口用 ENTRYPOINT。
3. **COPY vs ADD**：无脑选 COPY，除非你要解压 tar 包。
4. **ARG vs ENV**：仅构建期用的变量用 ARG，运行期需要的用 ENV。
5. **构建上下文**：`docker build .` 最后的点代表上下文目录，不要把无用大文件放进去，可使用 `.dockerignore` 过滤。
---
## 六、典型 Dockerfile 模板
```dockerfile
# 基础镜像
FROM node:18-alpine
# 元数据
LABEL maintainer="you@example.com"
# 构建参数 (仅构建期有效)
ARG NODE_ENV=production
# 环境变量 (构建+运行期有效)
ENV NODE_ENV=${NODE_ENV} \
    APP_HOME=/app
# 切换工作目录
WORKDIR ${APP_HOME}
# 先拷贝依赖文件，利用缓存加速构建
COPY package*.json ./
RUN npm ci --only=production && \
    npm cache clean --force
# 拷贝应用代码
COPY . .
# 声明端口
EXPOSE 3000
# 挂载数据卷
VOLUME ["/app/data"]
# 健康检查
HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
    CMD curl -f http://localhost:3000/health || exit 1
# 启动命令
CMD ["node", "server.js"]
```
