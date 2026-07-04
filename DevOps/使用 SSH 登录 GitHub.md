---
title: "使用 SSH 登录 GitHub"
date: 2026-06-22
---
在日常使用 Git 的过程中，SSH 提供了一种安全、便捷的方式来连接 GitHub，无需每次操作都输入用户名和密码。本文将手把手教你如何生成 SSH 密钥、配置 GitHub，并完成首次连接。

---

## 前提条件

- 已安装 Git（可通过 `git --version` 验证）
- 拥有一个 GitHub 账号
- 能访问终端或命令行工具（如 macOS Terminal、Git Bash、Windows Terminal 等）

---

## ✅ 第一步：检查是否已有 SSH 密钥

在终端中运行：

```bash
ls -al ~/.ssh
```

若目录中已有 `id_rsa` 和 `id_rsa.pub`（或 `id_ed25519` 和 `id_ed25519.pub`），说明你已经生成过 SSH 密钥。否则，请继续下一步。

---

## 第二步：生成新的 SSH 密钥

```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
```

> 如果你使用的是较老的系统不支持 ed25519，可以使用：  
> `bash ssh-keygen -t rsa -b 4096 -C "your_email@example.com"`

根据提示操作：

- 当系统提示 `Enter a file in which to save the key` 时，直接按下 Enter，使用默认路径 `~/.ssh/id_ed25519`
- 设置一个密码（可选）

---

## 第三步：将 SSH 公钥添加到 GitHub

1. 查看公钥内容：  
    `bash cat ~/.ssh/id_ed25519.pub`  
    
2. 复制输出内容  
    
3. 登录 GitHub，点击右上角头像 → **Settings** → **SSH and GPG keys**  
    
4. 点击 **New SSH key**，粘贴密钥并命名  
    

---

## 第四步：测试 SSH 连接

```bash
ssh -T git@github.com
```

首次连接会提示：

```text
The authenticity of host 'github.com (IP 地址)' can't be established.
```

输入 `yes` 继续，如果一切正常，你会看到如下信息：

```text
Hi <username>! You've successfully authenticated, but GitHub does not provide shell access.
```

---

## 第五步：配置 Git 默认使用 SSH

确认你的远程地址使用的是 SSH 格式，而非 HTTPS：

```bash
git remote -v
```

如若不是 SSH 格式，可执行：

```bash
git remote set-url origin git@github.com:username/repo.git
```

---

## 附：多账户或多密钥的配置（可选）

编辑 SSH 配置文件：

```bash
nano ~/.ssh/config
```

添加如下内容：

```text
Host github.com
  HostName github.com
  User git
  IdentityFile ~/.ssh/id_ed25519
```

若有多个 GitHub 账户，可使用别名区分：

```text
Host github-work
  HostName github.com
  User git
  IdentityFile ~/.ssh/id_ed25519_work
```

---

## ✅ 总结

现在，你已经成功配置了 SSH 登录 GitHub。接下来你可以愉快地使用 Git 拉取、推送代码而无需每次输入用户名密码了！
