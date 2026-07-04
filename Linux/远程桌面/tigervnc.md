---
title: "tigervnc"
date: 2026-07-02
---
/usr/share/doc/tigervnc/HOWTO.md
# 变更内容
此前版本的 TigerVNC 有一个名为 `vncserver` 的包装脚本，用户可以手动运行它以启动 *Xvnc* 进程。使用方法非常简单，只需执行：
```
$ vncserver :x [vncserver 选项] [Xvnc 选项]
```
即可。虽然这种方法运行良好，但当用户希望通过 *systemd* 启动 TigerVNC 服务器时会出现问题。因此，整个机制已被彻底改变，现在有一种新的工作方式。

# 如何启动 TigerVNC 服务器

## 添加用户映射
通过用户映射，可以将用户与特定端口绑定。映射应在 `/etc/tigervnc/vncserver.users` 配置文件中完成。打开该文件后，您会发现一些示例，格式非常直观，基本映射格式为：
```
:x=用户
```
例如：
```
:1=test
:2=vncuser
```

## 配置 Xvnc 选项
要配置 Xvnc 参数，请进入与用户映射文件相同的目录，打开 `vncserver-config-defaults` 配置文件。此文件是默认的 Xvnc 配置，将应用于每个用户，除非出现以下情况：
* 用户在 `$HOME/.vnc/config` 中有自己的配置。
* 在 `vncserver-config-mandatory` 配置文件中为同一选项配置了不同的值，该文件会覆盖默认配置，并且其优先级甚至高于用户级配置。此选项供系统管理员在需要强制使用特定 *Xvnc* 选项时使用。

配置文件的格式也很简单，采用以下形式：
```
选项=值
选项
```
例如：
```
session=gnome
securitytypes=vncauth,tlsvnc
desktop=sandbox
geometry=2000x1200
localhost
alwaysshared
```
### 注意：
有一个重要的选项您必须设置，即您想要启动的会话类型。例如，当您想启动 GNOME 桌面时，必须使用：
```
session=gnome
```
该值应与 `/usr/share/xsessions` 目录中的会话桌面文件名称匹配。

## 设置 VNC 密码
您需要为每个用户设置密码才能启动 TigerVNC 服务器。要创建密码，只需以将要启动服务器的用户身份运行：
```
$ vncpasswd
```
### 注意：
如果您之前为您的用户使用过 TigerVNC 并且已经创建了密码，那么您需要确保 `vncpasswd` 创建的 `$HOME/.vnc` 文件夹具有正确的 *SELinux* 上下文。您可以删除此文件夹，然后通过再次创建密码来重新生成它，或者运行：
```
$ restorecon -RFv /home/<用户>/.vnc
```

## 启动 TigerVNC 服务器
最后，您可以使用 systemd 服务来启动服务器。只需以 root 身份运行：
```
$ systemctl start vncserver@:x
```
或者，如果普通用户有权限运行 `sudo`，则可以运行：
```
$ sudo systemctl start vncserver@:x
```
不要忘记将 `:x` 替换为您在用户映射文件中配置的实际端口号。按照我们的示例，运行：
```
$ systemctl start vncserver@:1
```
将会为用户 `test` 启动一个带 GNOME 会话的 TigerVNC 服务器。

### 注意：
如果您之前使用 TigerVNC 并且习惯于通过 *systemd* 启动它，那么您需要移除之前的 *systemd* 配置文件（这些文件很可能被您复制到了 `/etc/systemd/system/vncserver@.service`），否则该服务文件将优先于最新 TigerVNC 安装的新文件。

# 限制
您无法为已经登录图形会话的用户启动 TigerVNC 服务器。避免以 `root` 用户身份运行服务器，因为这不安全。虽然以 `root` 身份运行服务器通常可以工作，但不建议这样做，并且可能会出现某些功能无法正常运行的情况。
