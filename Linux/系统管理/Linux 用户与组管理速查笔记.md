---
title: "Linux 用户与组管理速查笔记"
date: 2026-07-02
---
## 一、用户与组基础概念

### 1.1 关键文件

| 文件            | 内容        | 字段 (冒号分隔)                          |
| ------------- | --------- | ---------------------------------- |
| `/etc/passwd` | 用户账户信息    | `用户名:密码占位:UID:GID:注释:家目录:Shell`    |
| `/etc/group`  | 组账户信息     | `组名:密码占位:GID:成员列表(逗号分隔)`           |
| `/etc/shadow` | 加密密码及老化策略 | `用户名:加密密码:上次更改:最小:最大:警告:不活动:过期:保留` |

### 1.2 主组与辅助组
- **主组**：`/etc/passwd` 第4字段指定的组，用户创建的文件默认属于此组。
- **辅助组**：`/etc/group` 最后字段列出的额外组成员资格。
- 用户对文件的访问权限取决于所有组（主组+辅助组）的权限叠加。

---

## 二、查看用户/组/进程/文件所有权命令

| 命令 | 用途 | 示例 |
|---|---|---|
| `id [用户]` | 显示 UID、主 GID 及所有辅助组 | `id` , `id root` |
| `ls -l` | 查看文件所有者和组 | `ls -l file.txt` |
| `ls -ld` | 查看目录自身所有权 | `ls -ld /home/user` |
| `ps -au` | 显示进程及其所属用户 | `ps -au` |
| `whoami` | 当前登录用户名 | `whoami` |

---

## 三、获取超级用户权限

### 3.1 `su` – 切换用户
```bash
su -          # 切换至 root (需 root 密码)，加载 root 环境
su - 用户名    # 切换至指定用户
exit          # 退出当前 shell
```

### 3.2 `sudo` – 以 root 执行命令
```bash
sudo -i                  # 获得 root 交互式 shell (推荐)
sudo 命令                # 单次以 root 执行命令
sudo cat /etc/shadow     # 示例：查看仅 root 可读的文件
```
- `/etc/sudoers` 配置 sudo 权限，必须通过 `visudo` 编辑。
- `%wheel  ALL=(ALL:ALL)  ALL` → wheel 组成员可执行任何命令。

---

## 四、本地用户管理

### 4.1 创建用户
```bash
useradd 用户名                  # 创建用户（默认家目录、bash）
useradd -c "全名" 用户名        # 带注释
```

### 4.2 设置/修改密码
```bash
passwd 用户名                  # root 可为任意用户设置密码，普通用户修改自己密码
```

### 4.3 修改用户属性
```bash
usermod -c "新注释" 用户名       # 修改注释（全名）
usermod -L 用户名                # 锁定账户（禁止登录）
usermod -U 用户名                # 解锁账户
usermod -aG 组名 用户名          # 追加到辅助组，**必须加 -a**
usermod -g 组名 用户名           # 更改主组
```
- `-G` 单独使用会覆盖所有辅助组，追加必须用 `-aG`。

### 4.4 验证用户
```bash
grep 用户名 /etc/passwd         # 查看用户条目
id 用户名                        # 查看 UID/GID/所有组
```

### 4.5 删除用户
```bash
userdel 用户名                  # 只删除账户，保留家目录
userdel -r 用户名               # 删除账户和家目录及邮件池
```

---

## 五、本地组管理

### 5.1 创建/修改/删除组
```bash
groupadd 组名                   # 创建组（自动分配 GID）
groupadd -g GID 组名            # 创建组并指定 GID

groupmod -n 新组名 旧组名       # 重命名组
groupmod -g 新GID 组名          # 更改 GID

groupdel 组名                   # 删除组（不能是某用户的主组）
```

### 5.2 查看组
```bash
grep 组名 /etc/group            # 查看组条目
id 用户名                       # 查看用户的所有组
```

### 5.3 管理组成员（通过 `usermod`）
```bash
usermod -aG 组名 用户         # 加入辅助组
usermod -g 组名 用户          # 更改主组
```

---

## 六、密码策略配置

### 6.1 查看 `/etc/shadow` 字段
```
用户名:加密密码:最后修改天:最小:最大:警告:不活动:过期:保留
```
- $6$… ：SHA-512 加密密码

### 6.2 使用 `chage` 设置老化参数
```bash
chage -m 最小天数 -M 最大天数 -W 警告天数 -I 不活动天数 用户名
chage -E YYYY-MM-DD 用户名      # 设置账户过期日期
chage -E -1 用户名               # 清除账户过期日期
chage -l 用户名                  # 列出密码老化信息
```

**示例：** 要求至少7天改一次，最长90天，过期前14天警告，过期后30天锁定。
```bash
sudo chage -m 7 -M 90 -W 14 -I 30 policyuser
```

---

## 七、一键清理与验证

```bash
# 删除带家目录的用户
userdel -r 用户名

# 删除组前确认无主组依赖
groupdel 组名

# 快速验证
grep 用户名 /etc/passwd
grep 组名 /etc/group
id 用户名
```

---

**核心原则：** `useradd` 管理用户，`groupadd` 管理组，`usermod`/`groupmod` 修改属性，`chage` 控制密码有效期，`sudo` 安全提权。
