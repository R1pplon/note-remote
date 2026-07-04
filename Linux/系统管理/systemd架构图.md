---
title: "systemd架构图"
date: 2026-07-02
---
## systemd Utilities
- systemctl
- journalctl
- notify
- analyze
- cgls
- cgtop
- loginctl
- nspawn

## systemd Daemons
- systemd
- journald
- networkd
- logind
- user
- session

## systemd Targets
- bootmode
- basic
- shutdown
- reboot
- multi-user
  - dbus
  - telephony
  - dlog
  - logind
- graphical
  - user-session
- user-session
  - display service
  - tizen service

## systemd Core
- manager
- systemd
- unit
  - service
  - timer
  - mount
  - target
  - snapshot
  - path
  - socket
  - swap
- login
  - multiseat
  - inhibit
  - session
  - pam
- namespace
- log
- cgroup
- dbus

## systemd Libraries
- dbus-1
- libpam
- libcap
- libcryptsetup
- tcpwrapper
- libaudit
- libnotify

## Linux Kernel
- cgroups
- autofs
- kdbus
