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

