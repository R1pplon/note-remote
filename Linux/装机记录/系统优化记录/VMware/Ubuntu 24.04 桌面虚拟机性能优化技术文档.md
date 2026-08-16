> 适用环境：VMware 虚拟机 · Ubuntu 24.04.4 LTS

---

## 1. 目标

对一台 6 vCPU / 7.7 GiB 内存 / HDD 磁盘的 Ubuntu 24.04 桌面虚拟机做综合性能优化，
兼顾四个方面：

| 目标        | 说明                                      |
| --------- | --------------------------------------- |
| 开机速度      | 消除 `plymouth-quit-wait` 20.7s 超时，精简自启服务 |
| 日常响应 / 内存 | 降低 swappiness、启用 zram 压缩交换、关闭无用常驻服务     |
| 磁盘占用      | 限制 journal 日志、移除无用的 snap 应用             |
| 稳定性       | 全程可回滚，不修改 cloud-init、I/O 调度器等高风险项       |

---

## 2. 环境分析（基线数据）

| 项目       | 值                                                          |
| -------- | ---------------------------------------------------------- |
| 虚拟化平台    | VMware（`systemd-detect-virt` → vmware）                     |
| 系统       | Ubuntu 24.04.4 LTS，内核 7.0.0-28-generic                     |
| CPU / 内存 | 6 vCPU / 7.7 GiB，交换分区 4 GiB（`/swap.img`）                   |
| 磁盘       | HDD（`/sys/block/sda/queue/rotational=1`），调度器 `mq-deadline` |
| 桌面       | GNOME（Wayland + Xwayland）                                  |
| 额外组件     | Docker/containerd（无运行容器）、多个 snap 应用                        |

### 基线瓶颈（`systemd-analyze blame`）

```
20.751s plymouth-quit-wait.service   ← 主要瓶颈（VMware 显卡下 Plymouth 等待超时）
 1.445s docker.service
 1.080s NetworkManager.service
  ...（其余 < 1s）
```

---

## 3. 优化项明细

### 3.1 开机提速：移除 splash

**问题**：`plymouth-quit-wait.service` 独占 20.7s，是 VMware 虚拟机下 Plymouth 无真实显卡输出导致的等待超时。

**方案**：`/etc/default/grub` 中 `GRUB_CMDLINE_LINUX_DEFAULT` 从 `"quiet splash"` 改为 `"quiet"`，执行 `update-grub`。

```bash
sed -i 's/GRUB_CMDLINE_LINUX_DEFAULT="quiet splash"/GRUB_CMDLINE_LINUX_DEFAULT="quiet"/' /etc/default/grub
update-grub
```

**效果**：开机 28.0s → 5.4s（见第 4 节）。

### 3.2 禁用 Docker/containerd 自启（保留软件）

**问题**：Docker 常驻拖慢开机 ~1.4s，且当前无容器运行。

**方案**：仅禁用自启，不卸载。

```bash
systemctl disable --now docker.service docker.socket containerd.service
```

**按需恢复**：`systemctl start docker`。

### 3.3 禁用虚拟机无用服务

VMware 桌面虚拟机上以下服务无实际用途，关闭后可释放内存、减少开机串行等待：

| 服务 | 用途 | 关闭理由 |
|---|---|---|
| `bluetooth.service` | 蓝牙 | 虚拟机无蓝牙硬件 |
| `cups.service` / `cups.socket` / `cups.path` / `cups-browsed.service` | 打印 | 无打印需求 |
| `avahi-daemon.service` / `.socket` | mDNS/Bonjour | 虚拟机内无局域网发现需求 |
| `kerneloops.service` | 内核 oops 上报 | 无价值 |
| `apport.service` / `apport-autoreport.path` / `apport-forward.socket` / `apport-autoreport.timer` | 崩溃上报 | 桌面环境非必需 |
| `whoopsie.path` / `whoopsie.service` | Ubuntu 错误上报 | 同上 |
| `gnome-remote-desktop.service` | 远程桌面 | 未使用 |
| `anacron.service` / `anacron.timer` | 延时 cron 补偿 | 虚拟机长期在线，`cron.service` 已覆盖 |

### 3.4 降低 swappiness（60 → 10）

**问题**：`vm.swappiness=60` 会过早将内存页换出到 HDD 交换分区，拖慢交互响应。

**方案**：写入 `/etc/sysctl.d/99-perf.conf`：

```ini
vm.swappiness=10
vm.vfs_cache_pressure=50
```

`vfs_cache_pressure=50` 让内核更倾向保留目录/索引缓存（dentry/inode），对大量小文件操作（如代码仓库、编译）有正面收益。

### 3.5 启用 zram 压缩交换

**问题**：HDD 上的 swap 速度慢，内存压力下响应差。

**方案**：安装 `zram-tools`，配置 `/etc/default/zramswap`：

```ini
PERCENT=50      # 使用物理内存的 50%（约 3.9 GiB）作为 zram
ALGO=zstd       # zstd 压缩：压缩率高于 lz4，CPU 开销适中
PRIORITY=100    # 优先级高于磁盘 swap（-1），内存压力时优先使用 zram
```

```bash
apt-get install -y zram-tools
systemctl enable --now zramswap.service
```

> **踩坑记录**：初版脚本用 `command -v zramctl || apt-get install` 判断，但 `zramctl` 属于 `util-linux`（系统自带），导致短路、`zram-tools` 从未安装。
> 后续又因**先写 `/etc/default/zramswap` 再装包**，触发 dpkg 的 conffile 交互提示；提示输出被 `>/dev/null 2>&1` 吞掉，表现为"卡死"。
> 修复版脚本改为：**先装包 → 再写配置 → 再启服务**，并用 `DEBIAN_FRONTEND=noninteractive` + `--force-confold` 彻底避免交互（见 `optimize.sh`）。

### 3.6 移除无用的 snap 应用

无浏览器需求，移除 Firefox 及仅服务于 GUI 的 snap 与依赖 base：

```bash
snap remove --purge firefox snap-store snapd-desktop-integration gnome-42-2204 gtk-common-themes
```

**保留**：`snapd`（snap 运行时）、`bare`、`firmware-updater`（系统固件更新）、`core22`。
其中 `core22` 是 `firmware-updater` 的 base 依赖，**不能**单独移除。

### 3.7 限制 journal 日志

**方案**：`/etc/systemd/journald.conf.d/limit.conf`：

```ini
[Journal]
SystemMaxUse=50M
SystemKeepFree=1G
MaxRetentionSec=2week
```

`systemctl restart systemd-journald` 生效。

---

## 4. 执行结果（优化前后对比）

| 指标 | 优化前 | 优化后 | 变化 |
|---|---|---|---|
| 开机总时长 | 28.055s | **5.429s** | **-80%** |
| 开机 userspace | 23.682s | 2.328s | -90% |
| `plymouth-quit-wait` | 20.751s | 已消失 | — |
| swappiness | 60 | 10 | — |
| zram 交换 | 无 | zstd 3.9 GiB（PRIO 100） | 新增 |
| journal 占用 | 112.9M | 43.5M | -61% |
| 磁盘已用 | 14G | 13G | -1G |
| snap 应用 | firefox 等 9 个 | 4 个（firefox 等已移除） | — |

### 最终开机时间

```
Startup finished in 3.101s (kernel) + 2.328s (userspace) = 5.429s
graphical.target reached after 2.317s in userspace.
```

### 最终 swap / zram

```
NAME       TYPE      SIZE USED PRIO
/swap.img  file        4G   0B   -1
/dev/zram0 partition 3.9G   0B  100   ← zstd 压缩，优先使用
```

---

## 5. 验证方法

```bash
systemd-analyze                       # 开机时间（<10s 即达标）
systemd-analyze blame | head -8       # 无 plymouth-quit-wait 等异常项
cat /proc/sys/vm/swappiness           # 应为 10
zramctl                               # 应显示 zstd 压缩的 /dev/zram0
swapon --show                         # zram0 应为 PRIO 100
journalctl --disk-usage               # 应 < 50M
systemctl list-unit-files --state=enabled | grep -E 'docker|bluetooth|cups|avahi|apport|kerneloops'  # 应为空
snap list                             # 应只剩 bare/core22/firmware-updater/snapd
```

---

## 6. 回滚方法

提供 `rollback.sh` 一键回滚，或手动执行：

```bash
# 恢复 swappiness
rm -f /etc/sysctl.d/99-perf.conf && sysctl --system

# 恢复自启服务
systemctl enable --now docker.service docker.socket containerd.service
systemctl enable bluetooth.service cups.service avahi-daemon.service anacron.timer

# 移除 zram
systemctl disable --now zramswap.service
apt-get purge -y zram-tools

# 恢复 grub splash（可选）
sed -i 's/GRUB_CMDLINE_LINUX_DEFAULT="quiet"/GRUB_CMDLINE_LINUX_DEFAULT="quiet splash"/' /etc/default/grub
update-grub

# 恢复 journal 上限
rm -f /etc/systemd/journald.conf.d/limit.conf && systemctl restart systemd-journald

# 重装 Firefox（如需）
snap install firefox
```

---

## 7. 候选优化（C 类）评估结论

| 项目 | 结论 | 说明 |
|---|---|---|
| cloud-init | ✅ 已确认禁用，仅做服务 mask 清理 | 见 7.1 |
| I/O 调度器 `bfq` | ⏭ 跳过 | 见 7.2 |
| GNOME 动画调低 | ✅ 已执行 | 见 7.3 |

### 7.1 cloud-init —— 实际已由安装器禁用

**重要更正**：`/etc/cloud/cloud-init.disabled` **早已存在**，由 Ubuntu live 安装器首次启动时自动创建，内容：

```
Disabled by Ubuntu live installer after first boot.
To re-enable cloud-init on this image run:
  sudo cloud-init clean --machine-id
```

佐证：

- `systemd-analyze blame` 中**无任何 cloud-init 项**（4 个服务每次开机空转即退，占用≈0）。
- `cloud-init` 等 4 个服务虽 `enabled`，但因该文件存在，运行时只做一次文件检查就返回，属 no-op。

因此无需 `touch /etc/cloud/cloud-init.disabled`。本次仅用 `c-class.sh` 将 4 个服务 + `cloud-init-hotplugd.socket` **disable + mask**，消除启动噪音、杜绝误启动。不卸载软件，保留"未来作云镜像模板"的能力。

### 7.2 I/O 调度器 bfq —— 跳过

- 磁盘为 **VMware 虚拟 SCSI 盘**，guest 报 `ROTA=1`（模拟机械盘），但物理后端大概率是 SSD/NVMe 数据存储。
- 内核：`mq-deadline` 已内置（当前使用），`bfq` 为模块、存在但未加载。
- `iostat`：`%util ≈ 0.01`，`%idle ≈ 96%`，系统几乎无磁盘 I/O。

bfq 的价值在于"物理机械盘 + 高并发读写下的交互低延迟"；此处虚拟盘 + 零负载，切换后无任何可测收益，故维持 `mq-deadline`。

### 7.3 GNOME 动画调低 —— 已执行

VMware 虚拟显卡下基本为软件渲染（llvmpipe），关闭动画可感知提升响应。

```bash
gsettings set org.gnome.desktop.interface enable-animations false
```

> 需在图形会话内执行（`gsettings` 依赖用户 D-Bus 会话，无法经 sudo/SSH 代跑）。
> 可逆：`gsettings set org.gnome.desktop.interface enable-animations true`。

---

## 8. 附录：脚本说明

| 文件 | 用途 |
|---|---|
| `optimize.sh` | 修复后的完整优化脚本，幂等可重跑、非交互、带校验输出 |
| `rollback.sh` | 反向回滚脚本 |
| `c-class.sh` | C 类清理：mask cloud-init 服务（幂等，带校验） |

**运行方式**：

```bash
sudo bash optimize.sh
sudo bash c-class.sh        # 可选：mask cloud-init 服务
```

**修复版相对初版的关键改进**：

1. 修复 zram 安装短路 bug（不再用 `command -v zramctl` 判断，直接 `apt-get install`）。
2. 修复 conffile 交互"假死"：先装包后写配置，并加 `DEBIAN_FRONTEND=noninteractive` 与 `--force-confold --force-confdef`。
3. 禁用列表补齐 `anacron.timer`（初版漏掉，导致 timer 仍 enabled）。
4. 幂等化：重跑不会重复执行、不会误报。
5. 增加分步骤日志 `[OK]/[SKIP]/[WARN]` 与最终校验汇总。
