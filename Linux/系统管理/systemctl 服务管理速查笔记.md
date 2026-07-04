---
title: "systemctl 服务管理速查笔记"
date: 2026-07-02
---
## 核心概念

- **systemd**：RHEL 的初始化系统和服务管理器。
- **服务单元 (service unit)**：以 `.service` 结尾的配置文件，定义服务如何启动、停止、重载等。
- **Unit 状态**：
  - `active (running)`：正在运行。
  - `inactive (dead)`：已停止。
  - `active (exited)`：一次性任务完成。
  - `failed`：启动或运行失败。
- **启用/禁用**：控制开机自启（enable 创建符号链接，disable 移除）。
- **屏蔽/取消屏蔽**：mask 将服务链接到 `/dev/null`，彻底禁止启动（比 disable 更强制）。
- **重载并非重启**：`reload` 保持 PID 不变，平滑更新配置（需服务支持 `ExecReload`）；`restart` 会终止并重启进程。

## 命令速查表

| 操作                | 命令                                          | 说明                                |
| ----------------- | ------------------------------------------- | --------------------------------- |
| 列出已加载且活动的服务       | `systemctl list-units --type=service`       | 仅显示活动服务，按 `q` 退出                  |
| 列出所有服务 (含非活动)     | `systemctl list-units --type=service --all` | 包括 inactive、failed 等状态            |
| 列出所有已安装的单元文件及启用状态 | `systemctl list-unit-files --type=service`  | 显示 enabled/disabled/static/masked |
| 查看某服务详细状态         | `systemctl status <服务名>`                    | 显示 PID、内存、日志片段等                   |
| 快速判断服务是否活动        | `systemctl is-active <服务名>`                 | 输出 active 或 inactive              |
| 快速判断是否启用自启        | `systemctl is-enabled <服务名>`                | 输出 enabled/disabled/static 等      |
| 快速判断服务是否失败        | `systemctl is-failed <服务名>`                 | 输出 active 或 failed                |
| 启动服务              | `sudo systemctl start <服务名>`                |                                   |
| 停止服务              | `sudo systemctl stop <服务名>`                 |                                   |
| 重启服务              | `sudo systemctl restart <服务名>`              | 停止后再启动，PID 改变                     |
| 重新加载配置 (平滑)       | `sudo systemctl reload <服务名>`               | 需 `ExecReload=` 定义，否则报错           |
| 重载或重启 (智能回退)      | `sudo systemctl reload-or-restart <服务名>`    | 优先 reload，不成功则 restart            |
| 启用开机自启            | `sudo systemctl enable <服务名>`               |                                   |
| 禁用开机自启            | `sudo systemctl disable <服务名>`              |                                   |
| 启用并立即启动           | `sudo systemctl enable --now <服务名>`         | 一次完成 enable + start               |
| 禁用并立即停止           | `sudo systemctl disable --now <服务名>`        | 一次完成 disable + stop               |
| 屏蔽服务 (禁止手动启动和自启)  | `sudo systemctl mask <服务名>`                 | 创建到 /dev/null 的链接；先 stop 再 mask   |
| 取消屏蔽服务            | `sudo systemctl unmask <服务名>`               | 删除 /dev/null 链接，恢复原服务文件           |
| 重载 systemd 守护进程   | `sudo systemctl daemon-reload`              | 新建或修改单元文件后必须执行                    |
| 清除失败状态            | `sudo systemctl reset-failed`               | 清除已删除服务留下的失败记录                    |
| 查看日志 (journal)    | `journalctl -u <服务名>`                       | 查看服务日志（如需补充）                      |

## 常用服务文件示例

```ini
[Unit]
Description=描述
After=network.target

[Service]
Type=simple
ExecStart=启动命令
ExecStop=停止命令
ExecReload=/bin/kill -HUP $MAINPID
Restart=on-failure

[Install]
WantedBy=multi-user.target
```

## 快速场景

### 新建并测试服务
1. 创建 `/etc/systemd/system/mytest.service` 文件。
2. `sudo systemctl daemon-reload`
3. `sudo systemctl start mytest.service`
4. `systemctl status mytest.service`
5. `sudo systemctl enable --now mytest.service` （需要时）
6. 验证：`tail -f /tmp/mytest.log`

### 修改配置后生效
- 如果支持重载：`sudo systemctl reload mytest.service`
- 安全通用方式：`sudo systemctl reload-or-restart mytest.service`

### 彻底删除自定义服务
1. `sudo systemctl stop mytest.service`
2. `sudo systemctl disable mytest.service`
3. `sudo systemctl unmask mytest.service` （若被屏蔽过）
4. `sudo rm /etc/systemd/system/mytest.service`
5. `sudo systemctl daemon-reload`
6. `sudo systemctl reset-failed`（如有失败记录）
7. 清理日志和附属脚本。
