# 使用 Tty 执行命令

在本步骤中，你将学习如何在容器中使用 tty 执行命令。

1. 使用 `kubectl exec` 命令并添加 `-it` 选项以通过 tty 执行命令：

   ```bash
   kubectl exec -it nginx-busybox -- /bin/sh
   ```

2. 进入容器 shell 后，运行以下命令：

   ```bash
   echo "Hello, world!"
   ```

3. 退出容器 shell：

   ```bash
   exit
   ```
