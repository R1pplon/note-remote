---
title: "Git"
date: 2026-06-20
---
## 基础操作

```bash
# 初始化仓库
git init

# 查看状态
git status

# 暂存
git add

# 提交
git commit -m "message"

# 查看日志
git log
git log --oneline
```

## config

```bash
# 查看设置信息
git config --list

# 查看指定键名
git config user.name
```

config 层级：
1. 系统级（System level）：此电脑系统设置
2. 全局级（Global level）：针对个人的设置
3. 本地级（Local level）：针对项目的设置

```bash
# 全局设置姓名
git config --global user.name "Your Name"

# 全局设置邮箱
git config --global user.email "Your Email"

# 全局设置默认编辑器
git config --global core.editor nano

# 查看设置
git config --global user.name
git config --global user.email
git config --global core.editor
```

