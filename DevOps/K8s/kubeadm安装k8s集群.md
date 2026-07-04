---
title: "kubeadm安装k8s集群"
date: 2026-06-27
---
操作系统: Rockylinux 10
Docker: 20+
K8s: 1.23.6

k8s-master: 192.168.20.80
k8s-node1: 192.168.20.81
k8s-node2: 192.168.20.82

## 初始操作

```bash
# 关闭防火墙
systemctl stop firewalld
systemctl disable firewalld

# 关闭selinux
sed -i 's/enforcing/disabled/' /etc/selinux/config  # 永久
setenforce 0  # 临时

# 关闭swap
swapoff -a  # 临时
sed -ri 's/.*swap.*/#&/' /etc/fstab    # 永久

# 关闭完swap后，一定要重启一下虚拟机！！！
# 根据规划设置主机名
hostnamectl set-hostname <hostname>

# 在master添加hosts
cat >> /etc/hosts << EOF
192.168.20.80 k8s-master
192.168.20.81 k8s-node1
192.168.20.82 k8s-node2
EOF


# 将桥接的IPv4流量传递到iptables的链
cat > /etc/sysctl.d/k8s.conf << EOF
net.bridge.bridge-nf-call-ip6tables = 1
net.bridge.bridge-nf-call-iptables = 1
EOF

sysctl --system  # 生效


# 时间同步
# yum install ntpdate -y
# ntpdate time.windows.com
dnf install chrony -y
systemctl enable --now chronyd

echo "pool ntp.aliyun.com iburst" >> /etc/chrony.conf
echo "local stratum 10" >> /etc/chrony.conf

# k8s-master
echo "allow 192.168.20.0/24" >> /etc/chrony.conf

# k8s-node
echo "server 192.168.20.80 iburst" >> /etc/chrony.conf

systemctl restart chronyd
```

### 安装docker

skip

### 添加阿里云yum源

```bash
cat > /etc/yum.repos.d/kubernetes.repo << EOF  
[kubernetes]
name=Kubernetes
baseurl=https://mirrors.aliyun.com/kubernetes/yum/repos/kubernetes-el7-x86_64
enabled=1
gpgcheck=0
repo_gpgcheck=0

gpgkey=https://mirrors.aliyun.com/kubernetes/yum/doc/yum-key.gpg https://mirrors.aliyun.com/kubernetes/yum/doc/rpm-package-key.gpg
EOF
```

### 安装 kubeadm、kubelet、kubectl

```bash
yum install -y kubelet-1.23.6 kubeadm-1.23.6 kubectl-1.23.6
systemctl enable kubelet

# 配置关闭 Docker 的 cgroups，修改 /etc/docker/daemon.json，加入以下内容  
"exec-opts": ["native.cgroupdriver=systemd"]  

# 重启 docker  
systemctl daemon-reload
systemctl restart docker
```

## 部署Master

```bash
# 在 Master 节点下执行  

kubeadm init \
      --apiserver-advertise-address=192.168.20.80 \
      --image-repository registry.aliyuncs.com/google_containers \
      --kubernetes-version v1.23.6 \
      --service-cidr=10.96.0.0/12 \
      --pod-network-cidr=10.244.0.0/16

# 安装成功后，复制如下配置并执行
mkdir -p $HOME/.kube
sudo cp -i /etc/kubernetes/admin.conf $HOME/.kube/config
sudo chown $(id -u):$(id -g) $HOME/.kube/config
kubectl get nodes
```

## 加入 Kubernetes Node

```bash
分别在 k8s-node1 和 k8s-node2 执行  

# 下方命令可以在 k8s master 控制台初始化成功后复制 join 命令  
  
kubeadm join 192.168.20.80:6443 --token 8xs5b2.ien5f4dnga01ryk9 \
--discovery-token-ca-cert-hash sha256:e79d50446c993d86c191ed37dc727a57e7f02b99e416457eadba9ba01d9c885d



# 如果初始化的 token 不小心清空了，可以通过如下命令获取或者重新申请
kubeadm token create --print-join-command

# 如果 token 已经过期，就重新申请
kubeadm token create

# token 没有过期可以通过如下命令获取  
kubeadm token list
  
# 获取 --discovery-token-ca-cert-hash 值，得到值后需要在前面拼接上 sha256:  
openssl x509 -pubkey -in /etc/kubernetes/pki/ca.crt | openssl rsa -pubin -outform der 2>/dev/null | \  
openssl dgst -sha256 -hex | sed 's/^.* //'
```

## 部署 CNI网络插件

```bash
# 在 master 节点上执行
# 下载 calico 配置文件，可能会网络超时
curl https://docs.projectcalico.org/manifests/calico.yaml -O
# wget https://raw.githubusercontent.com/projectcalico/calico/v3.25.0/manifests/calico.yaml

# 修改 calico.yaml 文件中的 CALICO_IPV4POOL_CIDR 配置，修改为与初始化的 cidr 相同

# 修改 IP_AUTODETECTION_METHOD 下的网卡名称

# 删除镜像 [docker.io](http://docker.io)/ 前缀，避免下载过慢导致失败
sed -i 's#docker.io/##g' calico.yaml
```

## 测试

```bash
# 创建部署
kubectl create deployment nginx --image=nginx

# 暴露端口
kubectl expose deployment nginx --port=80 --type=NodePort

# 查看 pod 以及服务信息
kubectl get pod,svc
```

## 在任意节点使用 kubectl

```
# 1. 将 master 节点中 /etc/kubernetes/admin.conf 拷贝到需要运行的服务器的 /etc/kubernetes 目录中
scp /etc/kubernetes/admin.conf root@k8s-node1:/etc/kubernetes

# 2. 在对应的服务器上配置环境变量
echo "export KUBECONFIG=/etc/kubernetes/admin.conf" >> ~/.bash_profile
source ~/.bash_profile
```

