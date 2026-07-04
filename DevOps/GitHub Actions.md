---
title: GitHub Actions
date: 2026-07-04
---

工作流配置文件路径：`.github/workflows/*.yaml`
即在项目的 `.github/workflows/` 路径下的所有yaml文件

```yaml
name: Hello World Workflow
on: [push]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Say Hello
        run: echo "Hello, World!"
```

## on

触发器，定义触发事件

```yaml
单个事件：
on: push

多个事件（满足任意一个即触发）
on: [push, pull_request]
```

### 常见事件类型

- `push`: 有人 push 代码到仓库时。
- `pull_request`: 创建、关闭或更新 Pull Request 时。
- `issues`: 当 Issue 被创建、删除、关闭等操作时。
- `release`: 当发布或更新 Release 时。
- `fork`: 当有人 fork 你的仓库时。
- `watch`: 当有人 Star 你的仓库时。

### 带有过滤条件的事件

```yaml
on:
  push:
    branches:
      - main          # 只有推送到 main 分支时才触发
      - 'release/*'   # 支持 glob 匹配，比如 release/1.0
    paths:
      - 'src/**'      # 只有 src 目录下的文件发生变化时才触发
      - '!src/docs/**'# 取反符 !：src/docs 目录下的变化不触发
    tags:
      - 'v*'          # 只有推送以 v 开头的 tag 时才触发
  pull_request:
    branches:
      - main          # 只有向 main 分支发起 PR 时才触发

```

### 定时任务

crontab 语法设置定时触发
注意，GitHub 使用的是 **UTC 时间**

```yaml
on:
  schedule:
    # 每天的 UTC 时间 00:00（即北京时间早上 08:00）运行一次
    - cron: '0 0 * * *'
    # 每周一到周五的 UTC 时间 05:30 运行
    - cron: '30 5 * * 1-5'
```

### 手动触发

允许在 GitHub 网页上手动点击按钮运行工作流

```yaml
on:
  workflow_dispatch:
    inputs:
      logLevel:
        description: 'Log level'
        required: true
        default: 'warning'
        type: choice
        options:
          - info
          - warning
          - debug
      environment:
        description: '部署到哪个环境？'
        required: true
        type: string
```

### 外部 API 触发

允许外部系统通过调用 GitHub API 来触发工作流，通常用于与其他 CI/CD 系统集成。

```yaml
on:
  repository_dispatch:
    types: [build-event] # 外部 API 请求时必须包含 event_type: build-event
```

###  依赖于其他工作流

当一个工作流运行完毕后，再触发当前工作流。常用于构建完之后触发部署。

```yaml
on:
  workflow_run:
    workflows: ["Build Workflow"] # 被依赖的工作流名称
    types:
      - completed                 # 当 Build Workflow 成功完成时触发
```



## env

`env` 用于存放非敏感的配置，比如构建模式、端口号、文件路径等。
它可以定义在不同的层级，具有作用域和优先级。

### 层级与优先级

你可以把 `env` 写在三个不同的位置，作用范围从大到小依次为：

1. **Workflow 级别**：定义在顶层，所有 jobs 和 steps 都能读取。
2. **Job 级别**：定义在 `jobs.<job_id>.env` 下，仅当前任务及其步骤可用。
3. **Step 级别**：定义在 `steps[].env` 下，仅当前步骤可用。

**优先级原则**：就近原则。如果同名变量同时存在于多个层级，Step 级别会覆盖 Job 级别，Job 级别会覆盖 Workflow 级别。

```yaml
name: Env Demo
env:
  GLOBAL_VAR: "I am everywhere" # 1. 全局变量

jobs:
  build:
    runs-on: ubuntu-latest
    env:
      JOB_VAR: "Only in this job" # 2. 任务级变量
    steps:
      - name: Step 1
        env:
          STEP_VAR: "Only in step 1" # 3. 步骤级变量
        run: |
          echo "全局: $GLOBAL_VAR"
          echo "任务级: $JOB_VAR"
          echo "步骤级: $STEP_VAR"
      
      - name: Step 2
        run: |
          echo "全局: $GLOBAL_VAR"
          echo "任务级: $JOB_VAR"
          # echo "步骤级: $STEP_VAR"  <-- 这里会报错，因为 STEP_VAR 仅在 Step 1 中定义
```

### 引用环境变量的两种方式

- **在 Shell 脚本中 (`run`)**：直接使用 `$VAR_NAME`（Linux/Mac）或 `%VAR_NAME%`（Windows）。
- **在 YAML 配置中（如 `with` 参数或 `if` 条件）**：必须使用 `${{ env.VAR_NAME }}` 语法。

```yaml
    - name: Use in with
      uses: actions/setup-node@v4
      with:
        node-version: ${{ env.NODE_VERSION }}
```

### 动态设置环境变量 (`$GITHUB_ENV`)

前面讲的 `env` 都是在 YAML 里静态写死的。但很多时候，变量的值是**上一步脚本执行后动态生成的**（比如读取了 package.json 中的版本号），下一步该怎么用呢？

GitHub Actions 提供了一个特殊的文件路径：`$GITHUB_ENV`。你只需要向这个文件追加 `KEY=VALUE` 格式的文本，GitHub 就会自动把它注册为后续步骤的环境变量。

```yaml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: 动态生成版本号
        # 假设我们通过脚本截取了一个版本号
        run: |
          VERSION="v1.2.3"
          # 将变量写入 GITHUB_ENV 文件！
          echo "APP_VERSION=$VERSION" >>$GITHUB_ENV

      - name: 使用上一步生成的版本号
        run: |
          # 这一步可以直接使用 $APP_VERSION，就像它一开始就定义在 env: 里一样
          echo "Building version $APP_VERSION"
          docker build -t myapp:$APP_VERSION .
```

### 默认的环境变量

GitHub Actions 会自动注入很多极其有用的默认环境变量。
常用的有：

- `$GITHUB_WORKSPACE`：代码被 checkout 的目录路径。
- `$GITHUB_REPOSITORY`：仓库全名（如 `octocat/hello-world`）。
- `$GITHUB_SHA`：触发此次 workflow 的 commit hash。
- `$GITHUB_REF`：触发的分支或 tag 名（如 `refs/heads/main`）。

## secret

任何敏感信息**绝对不能**写在 YAML 文件里，必须使用 `secret`。

### 如何配置

1. 进入你的 GitHub 仓库页面。
2. 点击 `Settings` -> 左侧栏 `Secrets and variables` -> `Actions`。
3. 点击 `New repository secret`。
4. 输入 Name（必须全大写，如 `SERVER_PASSWORD`）和 Value。
5. 点击保存。

**重要安全特性**：保存后，**任何人都无法再次查看明文**（包括仓库 Owner），只能更新或删除。这可以防止内部人员窃取生产环境密码。

### 如何引用

通过 `${{ secrets.SECRET_NAME }}` 语法引用。

```yaml
steps:
  - name: Deploy
    run: |
      # 在脚本中使用
      ssh user@server -p ${{ secrets.SERVER_PORT }}
    env:
      DB_PASSWORD: ${{ secrets.DATABASE_PASSWORD }} # 推荐做法：将 secret 映射为 env，再在脚本中用$DB_PASSWORD 引用

  - name: Pass to Action
    uses: appleboy/ssh-action@v1
    with:
      password: ${{ secrets.SSH_PASSWORD }} # 在 with 参数中直接使用
```

### 日志自动脱敏（Masking）

这是 GitHub Actions 的一个非常贴心的安全机制。
当一个 `secret` 的值在终端输出时，GitHub Runner 会自动将其替换为 `***`。

```
- run: echo "My token is ${{ secrets.MY_TOKEN }}"
# 即使脚本执行了 echo，GitHub Actions 的日志里只会显示：
# My token is ***
```

### `secret` 的限制

- **不能直接用于 `if` 条件**：出于安全考虑，你不能写 `if: ${{ secrets.MY_KEY == '123' }}`，这在语法上是不被支持的。正确做法是将 secret 赋值给 env，然后用 env 比较（但这也不推荐，还是应该通过外部脚本来校验）。
- **不能用于 `name`**：步骤名或任务名中不能使用 secrets。

### Environment Secrets (环境隔离)

如果你有 `dev` 和 `prod` 两套环境，它们的数据库密码不同。你可以使用 GitHub 的 Environment 功能。

1. 在仓库 Settings -> Environments 中创建 `dev` 和 `prod` 环境。
2. 在各自的环境下添加同名 Secret（如 `DB_PASSWORD`）。
3. 在 YAML 中通过 `environment` 关键字指定：

```yaml
jobs:
  deploy-prod:
    runs-on: ubuntu-latest
    environment: production # 声明使用 production 环境
    steps:
      - run: deploy.sh ${{ secrets.DB_PASSWORD }} 
      # 这里引用的 DB_PASSWORD 会自动读取 production 环境下的机密
```

## jobs

一次工作流运行 (workflow run) 由一个或多个 `jobs` 组成

```yaml
jobs:
  job_id:           # 1. 任务 ID (自定义，如 build, test, deploy，必须是字母开头)
    name: 任务名称   # 2. 可选，显示在 Actions 界面上的友好名称
    runs-on: 环境    # 3. 指定运行环境（必填）
    steps:           # 4. 具体的执行步骤
      - ...
```

### 执行步骤 (`steps`)

`steps` 是一个数组，包含了任务中按顺序执行的具体操作。每个步骤可以是运行命令，也可以是调用别人写好的 Action。

- **`uses`**: 调用现成的 Action（最常用的是拉取代码 `actions/checkout`）。
- **`run`**: 执行 shell 命令。
- **`name`**: 步骤名称。
- **`with`**: 给调用的 Action 传递参数。
- **`id`**: 给步骤起个唯一标识，用于后续获取该步骤的输出结果。

```yaml
steps:
  - name: Checkout code
    uses: actions/checkout@v4 # 拉取你的代码到虚拟机中

  - name: Setup Node.js
    uses: actions/setup-node@v4
    with:
      node-version: '20' # 使用 with 传参，指定 Node 版本

  - name: Install dependencies
    run: npm install # 执行 shell 命令

  - name: Run multi-line script
    run: |
      echo "Hello"
      echo "World"
```

### 任务依赖与顺序 (`needs`)

默认情况下多个 job 是并行的。如果你希望 `deploy` 任务必须在 `build` 成功后才能运行，就用 `needs`。

```yaml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - run: echo "Building..."
  deploy:
    needs: build # 必须等 build 任务跑完且成功，deploy 才会开始
    runs-on: ubuntu-latest
    steps:
      - run: echo "Deploying..."
```


### 任务间的数据传递 (`outputs` 和 `env`)

一个任务怎么把结果传给下一个任务？  
需要两步：

1. 在任务级别声明 `outputs`。
2. 在 steps 中通过特殊语法写入值。

```
jobs:
  job1:
    runs-on: ubuntu-latest
    outputs:
      my_result: ${{ steps.gen_id.outputs.my_value }} # 获取步骤 id 为 gen_id 的输出
    steps:
      - id: gen_id # 给步骤设置 id
        run: echo "my_value=HelloJob2" >> "$GITHUB_OUTPUT" # 写入输出值

  job2:
    needs: job1
    runs-on: ubuntu-latest
    steps:
      - run: echo "Job1 said ${{ needs.job1.outputs.my_result }}" # 使用上游任务的输出
```

### 矩阵策略 (`strategy.matrix`)

多版本多环境运行。
比如测试代码在 Node 16, 18, 20 下，以及 Ubuntu 和 Windows 下是否都能正常运行。
用矩阵会自动生成多个并行任务。

```
jobs:
  test:
    runs-on: ${{ matrix.os }}
    strategy:
      matrix:
        os: [ubuntu-latest, windows-latest] # 2个系统
        node: [16, 18, 20]                  # 3个Node版本
        # 这将自动生成 2 x 3 = 6 个并行任务进行测试
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: ${{ matrix.node }}
      - run: npm test
```

### 条件控制 (`if`)

可以决定任务或步骤在什么条件下才执行。
常用于“只在主分支合并时才执行部署”。

```
jobs:
  deploy:
    runs-on: ubuntu-latest
    # 只有当推送的分支是 main 时，才执行这个 job
    if: github.ref == 'refs/heads/main'
    steps:
      - run: echo "Deploying to production"
```

注意：`if` 表达式中的特殊字符（如 `==`）需要用 `${{ }}` 包裹，但如果是直接写在 `if:` 后面，GitHub Actions 允许省略 `${{ }}`。

### 允许失败 (`continue-on-error`)

有些任务（比如非核心的代码检查）失败了不想阻断整个工作流，可以设置它。

```
steps:
  - name: Lint Code
    continue-on-error: true # 这一步即使失败了，脚本也会继续往下走
    run: npm run lint
```

### 超时设置 (`timeout-minutes`)

默认任务超时时间是 360 分钟（6小时）。
为了防止死循环消耗免费额度，通常会设置短一点。

```
jobs:
  build:
    runs-on: ubuntu-latest
    timeout-minutes: 30 # 如果 30 分钟没跑完，自动终止
```


## Action

### 语言环境配置

#### `actions/setup-node` (Node.js)

```yaml
steps:
  - uses: actions/checkout@v4
  - uses: actions/setup-node@v4
    with:
      node-version: '20'
      cache: 'npm' # 自动缓存 npm 依赖
  - run: npm ci
```

#### `actions/setup-python` (Python)

```yaml
- uses: actions/setup-python@v5
  with:
    python-version: '3.11'
    cache: 'pip'
```

#### `actions/setup-java` (Java)

常用于 Maven/Gradle 项目。

```yaml
- uses: actions/setup-java@v4
  with:
    distribution: 'temurin' # 推荐的 OpenJDK 发行版
    java-version: '17'
    cache: 'maven' # 缓存 maven 依赖
```

### 缓存与构建产物传递

在多个 Job 之间，或者多次运行之间，传递和复用文件是刚需。

#### `actions/cache` (通用缓存)

如果官方的 setup 没有内置你需要的缓存（比如你想缓存 Docker 镜像、或者某个特定的文件夹），可以用这个通用缓存工具。

```yaml
- uses: actions/cache@v4
  with:
    path: |
      ~/.gradle/caches
      ~/.gradle/wrapper
    key: ${{ runner.os }}-gradle-${{ hashFiles('**/*.gradle*', '**/gradle-wrapper.properties') }}
    # key 通常包含操作系统和依赖文件的 hash，文件没变就会命中缓存
```

#### `actions/upload-artifact` (上传构建产物)

**场景**：你在 Job A 中把前端代码打包成了 `dist` 文件夹，但你想在 Job B 中把它部署到服务器。Job 之间文件是隔离的，必须先上传，再下载。

```yaml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: npm run build
      - name: 上传打包结果
        uses: actions/upload-artifact@v4
        with:
          name: my-dist-files
          path: dist/ # 把 dist 目录打包传走
```

#### `actions/download-artifact` (下载构建产物)

配合上面的上传功能，在另一个 Job 中接收文件。

```yaml
jobs:
  deploy:
    needs: build
    runs-on: ubuntu-latest
    steps:
      - name: 下载打包结果
        uses: actions/download-artifact@v4
        with:
          name: my-dist-files
          path: ./deploy-folder # 下载到这个目录
```

### 部署与发布

#### `softprops/action-gh-release` (创建 GitHub Release - 社区)

非常强大的社区 Action，当你打了一个 Tag（如 `v1.0.0`）时，自动创建 GitHub Release 页面并上传附件。

```yaml
on:
  push:
    tags:
      - 'v*'
jobs:
  release:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: 构建二进制文件
        run: make build
      - name: 发布 Release
        uses: softprops/action-gh-release@v2
        with:
          files: bin/my-app.zip # 把构建好的压缩包附加到 Release 中
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

#### `appleboy/ssh-action` (SSH 远程部署 - 社区)

如果你想通过 SSH 连接到自己的阿里云/腾讯云服务器执行部署命令，这是最流行的选择。

```yaml
- name: 通过 SSH 执行部署脚本
  uses: appleboy/ssh-action@v1.0.3
  with:
    host: ${{ secrets.SERVER_IP }}
    username: ${{ secrets.SERVER_USER }}
    key: ${{ secrets.SSH_PRIVATE_KEY }} # 存放在 GitHub Secrets 中的私钥
    script: |
      cd /var/www/myapp
      git pull origin main
      npm install
      pm2 restart myapp
```

#### `appleboy/scp-action` (SCP 传输文件 - 社区)

配合上面的 SSH，把本地构建好的文件直接传到服务器。

```yaml
- name: 拷贝文件到服务器
  uses: appleboy/scp-action@v0.1.7
  with:
    host: ${{ secrets.SERVER_IP }}
    username: ${{ secrets.SERVER_USER }}
    key: ${{ secrets.SSH_PRIVATE_KEY }}
    source: "dist/*" # 本地构建产物
    target: "/var/www/myapp" # 服务器目标路径
```

### 自动化脚本与 API 操作

#### `actions/github-script` (运行 JS 脚本操作 GitHub)

如果你想在 CI 中自动给 PR 添加评论、关闭 Issue、或者修改分支保护规则，使用这个 Action 最方便。它内置了 GitHub API 的 SDK。

```yaml
- name: 给 Pull Request 添加评论
  uses: actions/github-script@v7
  with:
    script: |
      github.rest.issues.createComment({
        issue_number: context.issue.number,
        owner: context.repo.owner,
        repo: context.repo.repo,
        body: '👋 感谢提交 PR！自动化测试已经通过啦！'
      })
```

#### `docker/login-action` (登录容器镜像仓库)

如果你在 CI 里需要构建并推送 Docker 镜像，这是必备前置步骤。

```yaml
- name: 登录 Docker Hub
  uses: docker/login-action@v3
  with:
    username: ${{ secrets.DOCKERHUB_USERNAME }}
    password: ${{ secrets.DOCKERHUB_TOKEN }}
```
