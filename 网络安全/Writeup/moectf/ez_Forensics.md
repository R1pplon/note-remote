---
title: "ez_Forensics"
date: 2024-09-01
---
# ez_Forensics

[内存取证 - Hello CTF](https://hello-ctf.com/HC_MISC/memory)
安装工具
执行命令：

```bash
获取信息
python2 vol.py -f flag.raw imageinfo

cmd命令扫描
python2 vol.py -f flag.raw --profile=Win7SP1x64 cmdscan
```

题目描述：`ubw亲眼看着npm在cmd中输入命令，将flag写入了flag.txt，然后删除了flag.txt`
提取已执行的 cmd 命令即可获取 flag。
