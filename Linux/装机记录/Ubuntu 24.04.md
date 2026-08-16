VMware workstation

```bash
# 安装并启动 ssh 服务
sudo apt update && sudo apt install openssh-server -y
sudo systemctl enable --now ssh

# 查看 IP 地址
ip a

# ssh 远程登录
ssh user@<IP>
```

ssh 远程登录是为了复制粘贴功能

换源

```bash
sudo mv /etc/apt/sources.list.d/ubuntu.sources /etc/apt/sources.list.d/ubuntu.sources.bak
sudo nano /etc/apt/sources.list.d/ubuntu.sources
```

在 `/etc/apt/sources.list.d/ubuntu.sources`
写入 DEB822 格式的清华软件源

```
Types: deb
URIs: https://mirrors.tuna.tsinghua.edu.cn/ubuntu
Suites: resolute resolute-updates resolute-backports
Components: main restricted universe multiverse
Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg

# 默认注释了源码镜像以提高 apt update 速度，如有需要可自行取消注释
# Types: deb-src
# URIs: https://mirrors.tuna.tsinghua.edu.cn/ubuntu
# Suites: resolute resolute-updates resolute-backports
# Components: main restricted universe multiverse
# Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg

# 以下安全更新软件源为官方源配置
Types: deb
URIs: http://security.ubuntu.com/ubuntu/
Suites: resolute-security
Components: main restricted universe multiverse
Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg

# Types: deb-src
# URIs: http://security.ubuntu.com/ubuntu/
# Suites: resolute-security
# Components: main restricted universe multiverse
# Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg

# 预发布软件源，不建议启用

# Types: deb
# URIs: https://mirrors.tuna.tsinghua.edu.cn/ubuntu
# Suites: resolute-proposed
# Components: main restricted universe multiverse
# Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg

# # Types: deb-src
# # URIs: https://mirrors.tuna.tsinghua.edu.cn/ubuntu
# # Suites: resolute-proposed
# # Components: main restricted universe multiverse
# # Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg
```

apt 更新软件

```bash
sudo apt update && sudo apt upgrade -y

# 安装 vm-tools
sudo apt install open-vm-tools open-vm-tools-desktop -y

# 重启以应用
sudo reboot
```


## 环境配置

## nvm

```bash
# 安装 NVM
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash

# 刷新环境变量
source ~/.bashrc

# 验证版本
nvm --version

# 查看可用的 Node.js 版本
nvm ls-remote

# 安装最新稳定版
nvm install --lts

# 切换 Node.js 版本
nvm use <版本号>

# - 查看当前使用的版本
nvm current
```

安装pnpm
`npm install -g pnpm`

## 开发套件

```bash
sudo apt install -y build-essential

# 检验版本
gcc --version
g++ --version
make --version
```


## UV

```bash
curl -LsSf https://astral.sh/uv/install.sh | sh


```