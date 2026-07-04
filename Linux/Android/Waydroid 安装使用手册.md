---
title: "Waydroid 安装使用手册"
date: 2026-07-02
---
## 准备工作

Waydroid对系统环境有两个要求，安装前需要提前检查一下：

1. **内核模块支持**
    系统需加载 **binder_linux** 和 **ashmem_linux** 内核模块

2. **Wayland桌面环境**
    Waydroid仅支持**Wayland**，不兼容X11

检查方法：

```sh
lsmod | grep binder

echo  $XDG_SESSION_TYPE
```

## 安装Waydroid

```sh
# 安装依赖
sudo apt update
sudo apt install curl ca-certificates -y

# 添加官方源
curl -s https://repo.waydro.id | sudo bash

# 安装waydroid
sudo apt install waydroid -y
```

验证安装

```sh
# 检查Waydroid版本
waydroid --version

# 检查服务状态
systemctl status waydroid-container
```

## 初始化Waydroid

初始化Waydroid可以选择**自动下载官方镜像**和**手动安装本地镜像**，

### 自动初始化

由于自动初始化下载太慢，不推荐用这种方法

```sh
sudo waydroid init
```

或直接在桌面找到并打开 waydroid

### 手动初始化

自行下载文件，注意架构

1. **system.img**
2. **vendor.img**

[WayDroid - Browse /images at SourceForge.net](https://sourceforge.net/projects/waydroid/files/images/)

将文件放到 `/usr/share/waydroid-extra/images/` 目录下

```sh
# 停止Waydroid服务
sudo systemctl stop waydroid-container

# 创建目录
sudo mkdir -p /usr/share/waydroid-extra/images/

# 复制本地镜像
# /path/to/your/xxx改为正确文件路径
sudo cp /path/to/your/system.img /usr/share/waydroid-extra/images/
sudo cp /path/to/your/vendor.img /usr/share/waydroid-extra/images/

# 强制初始化
sudo waydroid init -f
```

## 启动Waydroid

```sh
# 启动容器服务
sudo systemctl start waydroid-container

# 打开Android界面
waydroid

# 或者使用全屏模式
waydroid show-full-ui

# 或者自定义屏幕分辨率
waydroid show-full-ui --display 1920x1080
```

## 关闭waydroid

```sh
waydroid session stop
sudo waydroid container stop
```

## adb连接

`waydroid status` 获取 IP 地址

```sh
waydroid status
Session:	RUNNING
Container:	RUNNING
Vendor type:	MAINLINE
IP address:	192.168.240.112
Session user:	r1pple(1000)
Wayland display:	wayland-0

adb connect 192.168.240.112
already connected to 192.168.240.112:5555

adb devices 
List of devices attached
192.168.240.112:5555	device
```

## 安卓软件

```sh
# 安装
waydroid app install xxx.apk 
```

## 使用体验

### 禁用屏幕键盘

打字时停止弹出屏幕键盘

Waydroid 默认在选择输入字段时会显示 Android 虚拟键盘。
要禁用该功能并只使用实体键盘，请关闭以下设置：
`Settings > System > Languages & input > Physical keyboard > Use on-screen keyboard`

## 扩展

```sh
# 设置多窗口模式
waydroid prop set persist.waydroid.multi_windows true

# 为应用添加触屏模拟
waydroid prop set persist.waydroid.fake_touch com.hypergryph.arknights

# 避免在窗口上出现多个鼠标指针
waydroid prop set persist.waydroid.cursor_on_subsurface true

# 设置共享文件夹为/Download文件夹（可自行修改文件夹路径）
sudo mount --bind ~/下载 ~/.local/share/waydroid/data/media/0/Download
```

```sh
sudo apt install lzip sqlite3

# 克隆waydroid_script仓库
git clone https://github.com/casualsnek/waydroid_script
# 切换至waydroid_script目录
cd waydroid_script

# 创建虚拟环境
python3 -m venv venv

# 安装脚本需要的依赖
venv/bin/pip install -r requirements.txt

# 执行waydroid_scrip脚本
sudo venv/bin/python3 main.py
```

选择安卓版本和应用
在安卓内查看系统信息
应用推荐选择
- `magisk` 面具 获取root权限
- `libhoudini` Arm转译

<!-- broken image:  (assets/Waydroid%20安装使用手册/file-20260221211820075.png) -->

## 真机伪装

// todo

## 代理抓包

### 证书安装

从 BurpSuite 导出证书 `cacert.der`

```sh
# 转换为 PEM（Android 需要 PEM 格式）
openssl x509 -inform DER -in cacert.der -out cacert.pem

# ## 安装自签名的CA证书
sudo venv/bin/python3 main.py install mitm --ca-cert ./cacert.pem
INFO: Creating directory: /var/lib/waydroid/overlay/system/etc/security/cacerts INFO: Copying /home/r1pple/下载/cacert.pem to system trust store INFO: Target file: /var/lib/waydroid/overlay/system/etc/security/cacerts/9a5ba575.0 INFO: mitm installation finished
```

### burp suite 设置透明代理

burp suite 的 代理设置，修改监听器

- 监听 `0.0.0.0` 或特定 IP
- **勾选 Support invisible proxying** 

<!-- broken image:  (assets/Waydroid%20安装使用手册/file-20260222010415555.png) -->

### 配置 iptables 透明代理规则

在使用 Waydroid 进行抓包或代理时，理解如何正确添加、修改和删除规则至关重要，以免造成网络不通或规则冲突。

#### 添加规则

将来自 `waydroid0` 网卡的所有 TCP 流量重定向到 Burp 的 8080 端口

```sh
sudo iptables -t nat -A PREROUTING -i waydroid0 -p tcp -j REDIRECT --to-ports 8080

# **验证规则是否添加成功**
sudo iptables -t nat -L -n -v
Chain PREROUTING (policy ACCEPT 2254 packets, 479K bytes)
 pkts bytes target     prot opt in     out     source               destination         
  236 14176 REDIRECT   tcp  --  waydroid0 *       0.0.0.0/0            0.0.0.0/0            redir ports 8080
```

#### ### 撤销/删除规则

当你不再需要抓包，或者想要恢复正常网络连接时，必须删除这条重定向规则。
有两种删除方式，推荐使用“按规则匹配删除”。

##### 按规则内容删除

推荐，更安全
将添加命令中的 `-A` 改为 `-D`，其他参数保持完全一致（包括旧端口号）：

```sh
sudo iptables -t nat -D PREROUTING -i waydroid0 -p tcp -j REDIRECT --to-ports 8080
```

##### 按行号删除

如果不清楚具体参数，可以先查看行号

```sh
sudo iptables -t nat -L PREROUTING --line-numbers

# 假设输出显示该规则在第 1 行
sudo iptables -t nat -D PREROUTING 1
```

#### 修改规则

在已设置规则的情况下，`iptables` 的规则不支持直接“编辑”。
如果需要修改端口（例如从 Burp 的 8080 改为 Charles 的 8888，或自定义端口）
标准的做法是 **先删除旧规则，再添加新规则**。

## Android Studio 开发

adb连接后即可在 Device Manager 查看使用

<!-- broken image:  (assets/Waydroid%20安装使用手册/file-20260222015611254.png) -->

## 参考

[Waydroid](https://docs.waydro.id/)
[Waydroid - ArchWiki](https://wiki.archlinuxcn.org/zh-cn/Waydroid)
[在PC上满速运行Android应用，WayDroid安装使用指南](https://www.bilibili.com/video/BV18z421B7YB)
[使用Waydroid作为抓包测试环境 - MyLog - 我的经验与记录](https://brc.cool/linux/Android/%E4%BD%BF%E7%94%A8Waydroid%E4%BD%9C%E4%B8%BA%E6%8A%93%E5%8C%85%E6%B5%8B%E8%AF%95%E7%8E%AF%E5%A2%83.html#%E4%BD%BF%E7%94%A8waydroid%E4%BD%9C%E4%B8%BA%E6%8A%93%E5%8C%85%E6%B5%8B%E8%AF%95%E7%8E%AF%E5%A2%83)
