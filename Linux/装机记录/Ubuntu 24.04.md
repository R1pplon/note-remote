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

换源

```bash
sudo mv /etc/apt/sources.list.d/ubuntu.sources /etc/apt/sources.list.d/ubuntu.sources.bak
sudo nano /etc/apt/sources.list.d/ubuntu.sources
```

写入 DEB822 格式的中科大软件源

```
Types: deb
URIs: https://mirrors.ustc.edu.cn/ubuntu
Suites: noble noble-updates noble-backports
Components: main restricted universe multiverse
Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg

Types: deb
URIs: https://mirrors.ustc.edu.cn/ubuntu
Suites: noble-security
Components: main restricted universe multiverse
Signed-By: /usr/share/keyrings/ubuntu-archive-keyring.gpg
```

apt 更新软件

```bash
sudo apt update && sudo apt upgrade -y
```

