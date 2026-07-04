---
title: "Linux 文件权限速查笔记"
date: 2026-07-02
---
## 一、权限概念

### 三类用户
- **u**（user）—— 文件所有者
- **g**（group）—— 所属组成员
- **o**（others）—— 其他用户
- **a** —— 所有三类（u+g+o）

### 三种权限
| 权限 | 文件                   | 目录                          |
|------|------------------------|-------------------------------|
| r    | 读取文件内容            | 列出目录内容 (`ls`)           |
| w    | 修改文件内容            | 创建/删除/重命名目录内文件     |
| x    | 执行文件                | 进入目录 (`cd`)               |

### ls -l 输出解读
```
-rwxr-x---  1  labex  devs  0  Jun 6 01:02  file
├─文件类型 (- 普通文件, d 目录, l 符号链接)
├─u权限(rwx)
├─g权限(r-x)
├─o权限(---)
```
**特殊权限标记位：**
- `s` 在 u 的执行位 → SUID
- `s` 在 g 的执行位 → SGID
- `t` 在 o 的执行位 → 粘滞位（Sticky Bit）
- 大写 `S` / `T` 表示对应位置没有 x 权限，仅有特殊位

---

## 二、chmod 符号模式（直观增减）

**语法：** `chmod [who][+-=][权限] 文件`

- **who:** `u` `g` `o` `a`（可组合，如 `go`）
- **操作：** `+` 加权限，`-` 减权限，`=` 精确设置

**示例：**
```bash
chmod go-w file         # 移除组和其他用户的写权限
chmod u+x script.sh     # 给所有者增加执行权限
chmod a=rw,g=r,o=r file # 精确设置：所有者 rw，组 r，其他 r
chmod u=rw,go=r file    # 同上（更简洁）
chmod a+x file          # 所有人增加执行权限
```

---

## 三、chmod 八进制（数字）模式

| 权限组合 | rwx | rw- | r-x | r-- | --- |
|----------|-----|-----|-----|-----|-----|
| 八进制值 | 7   | 6   | 5   | 4   | 0   |

**计算：** r=4, w=2, x=1，每位求和。

**语法：** `chmod OGO 文件`（O=所有者位，G=组位，O=其他位）

**常用组合：**
- `644` → `rw-r--r--` （普通文件）
- `755` → `rwxr-xr-x` （可执行文件/目录）
- `600` → `rw-------` （私有文件）
- `750` → `rwxr-x---` （同组成员可访问）
- `770` → `rwxrwx---` （共享可执行）

**示例：**
```bash
chmod 644 document.txt
chmod 755 script.sh
chmod 750 project_dir
```

---

## 四、特殊权限（八进制前置位）

| 权限       | 八进制值 | 作用于文件                            | 作用于目录                          | ls 标示         |
|------------|----------|---------------------------------------|-------------------------------------|-----------------|
| SUID       | 4        | 以**文件所有者**身份执行              | 无意义                              | `s` 在 u 的执行位 |
| SGID       | 2        | 以**文件所属组**身份执行              | 新文件继承目录的**所属组**          | `s` 在 g 的执行位 |
| Sticky Bit | 1        | 无意义                                | 用户只能删除**自己的文件**          | `t` 在 o 的执行位 |

**设置方式：**
```bash
chmod u+s file     # SUID
chmod g+s dir      # SGID
chmod o+t dir      # Sticky

chmod 4755 file    # SUID + 755
chmod 2770 dir     # SGID + 770
chmod 1777 dir     # Sticky + 777
```

**注意：** 特殊位必须配合执行位(`x`)才显示小写`s/t`，否则显示大写`S/T`。

---

## 五、chown 更改所有者/所属组

**语法：** `chown [选项] [所有者][:所属组] 文件`

- 仅改所有者：`chown user file`
- 同时改所有者和组：`chown user:group file`
- 仅改组（等价于 chgrp）：`chown :group file`
- 递归改目录树：`chown -R user:group dir`

**示例：**
```bash
sudo chown developer file
sudo chown labex:devs file
sudo chown :devs script.sh
sudo chown -R developer:devs my_files/
```

---

## 六、umask 默认权限掩码

- 查看当前值：`umask`
- 临时设置：`umask 0022`（仅当前shell会话有效）

**原理：**  
从最大权限中减去 umask 值：
- 文件最大：`666`
- 目录最大：`777`

**umask 计算示例：**

| umask | 文件最终权限 | 目录最终权限 |
|-------|--------------|--------------|
| 0022  | 644 (rw-r--r--) | 755 (rwxr-xr-x) |
| 0002  | 664 (rw-rw-r--) | 775 (rwxrwxr-x) |
| 0077  | 600 (rw-------) | 700 (rwx------) |

**注意：** 新建文件默认不赋予执行权限（即使 umask 允许）。  
**永久设置：** 写入 `~/.bashrc` 或 `/etc/profile`。

---

## 七、常用命令速查表

| 命令                        | 用途                          |
|-----------------------------|-------------------------------|
| `ls -l file`                | 查看文件权限及属性             |
| `ls -ld dir`                | 查看目录本身的权限             |
| `chmod go-w file`           | 符号法移除写权限               |
| `chmod u+x file`            | 符号法增加执行权限             |
| `chmod 644 file`            | 数字法设为 rw-r--r--          |
| `chmod 755 script`          | 数字法设为 rwxr-xr-x          |
| `chmod u+s file`            | 设置 SUID                     |
| `chmod g+s dir`             | 设置 SGID                     |
| `chmod o+t dir`             | 设置粘滞位                    |
| `chown user file`           | 更改所有者                    |
| `chown user:group file`     | 更改所有者和组                |
| `chown :group file`         | 仅更改所属组                  |
| `chown -R user:group dir`   | 递归更改所有权                |
| `umask`                     | 查看或设置默认权限掩码         |
