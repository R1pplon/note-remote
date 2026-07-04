---
title: "iSCSI 网络块级存储"
date: 2026-07-02
---
注意：**目标块设备必须是未被挂载的**

## Fedora server 配置

查看挂载情况

```bash
lsblk
```

卸载设备

```bash
sudo umount /dev/sdX
```


安装并启动 iSCSI 目标服务

```bash
sudo dnf install targetcli -y
sudo systemctl enable --now target
```

使用 `targetcli` 交互式配置界面：

```bash
sudo targetcli
```

在 `targetcli` 中依次执行以下命令

1. **创建后端存储（将物理磁盘映射为后端块设备）：**
    ```bash
    cd backstores/block
    create name=sdb_block dev=/dev/sdb
    ```

2. **创建 iSCSI Target：**
```bash
cd /iscsi
create
```
此时会生成一个 IQN，类似 `iqn.2003-01.org.linux-iscsi.fedora.x8664:sn.xxxxxxxx`，记下它

3. **创建 LUN（将后端存储绑定到 Target）：**
```bash
cd iqn.2003-01.org.linux-iscsi.fedora.x8664:sn.xxxxxxxx/tpg1/luns
create /backstores/block/sdb_block
```

4. **取消访问控制认证（为了简化内网配置，关闭鉴权）：**
```bash
cd ../acls
# 创建一个允许任何 initiator 连接的 ACL，或者直接修改 tpg1 属性
cd ..
set attribute authentication=0 demo_mode_write_protect=0
```

5. **保存并退出：**
```bash
cd /
saveconfig
exit
```

**配置防火墙：**

```bash
sudo firewall-cmd --add-service=iscsi-target --permanent
sudo firewall-cmd --reload
```

## Windows配置

### 步骤 1：在 Windows 上找到 Initiator IQN

打开 Windows 的 iSCSI 发起程序（`iscsicpl.exe`），在 **“配置”** 选项卡中，你会看到 **“发起程序名称”**，类似：

```
iqn.1991-05.com.microsoft:your-pc-name
```

**复制这个 IQN。**

### 步骤 2：在 Fedora 的 targetcli 中添加 ACL

```bash
sudo targetcli
```

```bash
cd /iscsi/iqn.2003-01.org.linux-iscsi.localhost.x8664:sn.8b9a9476e3ee/tpg1/acls
create iqn.1991-05.com.microsoft:your-pc-name
```

> ⚠️ 把上面的 IQN 替换成你 Windows 上实际显示的那个！

```bash
cd /
saveconfig
exit
```

### 步骤 3：确认防火墙放行

```
sudo firewall-cmd --add-service=iscsi-target --permanent
sudo firewall-cmd --reload
```

如果提示 service 不存在，直接放行端口：

```
sudo firewall-cmd --add-port=3260/tcp --permanent
sudo firewall-cmd --reload
```

### 步骤 4：在 Windows 上重新连接

回到 Windows iSCSI 发起程序：

1. 选中目标，点击 **“连接”**
2. 确保没有勾选 CHAP 认证
3. 点击确定

此时状态应该变为 **“已连接”**。

然后打开 **磁盘管理**（`diskmgmt.msc`），你应该能看到一块新磁盘出现，就是你 Fedora 上的 `sdb`，带有它原来的 NTFS 分区。
