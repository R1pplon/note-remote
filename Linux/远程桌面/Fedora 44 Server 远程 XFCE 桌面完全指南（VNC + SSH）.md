---
title: "Fedora 44 Server 远程 XFCE 桌面完全指南（VNC + SSH）"
date: 2026-07-02
---
> **核心思路**：服务器保持纯命令行模式（`multi-user.target`）运行以节省资源，仅在需要图形界面时，通过 systemd 启动 VNC 虚拟屏幕，并通过 SSH 隧道加密访问。


## 第一阶段：安装与初始化 (服务器端)

### 1. 安装 TigerVNC 服务端

```bash
sudo dnf install tigervnc-server
```

### 2. 设置 VNC 密码

**必须在你需要远程的普通用户（如 `r1pple`）下执行**，不要加 `sudo`：

```bash
vncpasswd
```

按提示输入密码，View-only 密码选 n 即可

## 第二阶段：官方标准配置 (服务器端)

Fedora 44 废弃了旧版的 `vncserver` 命令，采用全新的 systemd 管理方式。
### 1. 配置用户映射

编辑官方映射文件，将屏幕号 `:1` 映射到你的用户（端口将对应为 5901）：

```bash
sudo nano /etc/tigervnc/vncserver.users
```

在文件末尾添加：

```text
:1=r1pple
```

### 2. 配置 VNC 会话与参数

VNC 不再依赖 `~/.vnc/xstartup`，而是通过配置文件设定桌面环境和参数。
编辑用户级配置文件：

```bash
nano ~/.vnc/config
```

写入以下内容（包含 XFCE 启动与性能优化参数）：

```text
session=xfce
geometry=1920x1080
alwaysshared
localhost
```

参数说明：
- `session=xfce` 直接调用 XFCE 会话
- `localhost` 强制仅监听本地，为 SSH 隧道提供安全保障。

### 3. 修复 SELinux 上下文 (重要)

如果你之前创建过 `.vnc` 目录，必须修复 SELinux 标签，否则服务可能无法启动：

```bash
restorecon -RFv /home/r1pple/.vnc
```

## 第三阶段：服务管理 (服务器端)

使用 systemd 进行 VNC 服务的管理（注意 Fedora 44 要求使用 `sudo`）：

| 操作         | 命令                                    |
| ---------- | ------------------------------------- |
| **启动 VNC** | `sudo systemctl start vncserver@:1`   |
| **关闭 VNC** | `sudo systemctl stop vncserver@:1`    |
| **查看状态**   | `sudo systemctl status vncserver@:1`  |
| **开机自启**   | `sudo systemctl enable vncserver@:1`  |
| **取消自启**   | `sudo systemctl disable vncserver@:1` |

启动后，使用 `status` 查看应显示 `active` 且无报错。

## 第四阶段：客户端连接 (本地电脑)

由于我们配置了 `localhost`，VNC 端口不对外暴露，必须通过 SSH 隧道连接。

### 1. 建立加密隧道

在你的**本地电脑**终端执行：

```bash
ssh -L 5901:localhost:5901 r1pple@<服务器IP>
```

如果想要更低延迟，可加速加密算法：

```bash
ssh -c aes128-gcm@openssh.com -L 5901:localhost:5901 r1pple@<服务器IP>
```

**登录成功后，保持此终端窗口不要关闭。**

### 2. 客户端连接

在本地电脑打开 VNC 客户端（推荐 RealVNC Viewer 或 TigerVNC）：
*   连接地址填：`localhost:5901` (或 `localhost:1`)
*   输入之前 `vncpasswd` 设置的密码

## 第五阶段：画面与性能优化 (连接成功后操作)

### 解决卡顿 (XFCE 桌面设置)

VNC 传输对特效极其敏感，进入 XFCE 桌面后务必做以下关闭：
*   **关闭合成器(特效)**：终端运行 `xfwm4-tweaks-settings` -> **Compositor** 选项卡 -> **取消勾选 Enable display compositing**。
*   **更换纯色壁纸**：右键桌面 -> Desktop Settings -> Background 改为 **Solid color** (纯黑或深灰)。

## 速查清单：日常工作流

当你平时不需要桌面，只需 SSH 命令行时，服务器零额外资源消耗。
当你需要远程桌面时，只需两步：
1. [服务器] 启动服务：`sudo systemctl start vncserver@:1`
2. [本地电脑] 建隧道并连接：`ssh -L 5901:localhost:5901 r1pple@IP` -> VNC 客户端连 `localhost:5901`
用完桌面后，关掉释放资源：
3. [服务器] 关闭服务：`sudo systemctl stop vncserver@:1`

当你需要本地桌面时：
1. **临时从命令行启动图形界面**
```bash
sudo systemctl isolate graphical.target
```
2. **临时关闭图形界面，进入纯命令行**
```bash
sudo systemctl isolate multi-user.target
```
