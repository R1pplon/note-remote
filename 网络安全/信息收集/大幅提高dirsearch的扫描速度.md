---
title: "大幅提高dirsearch的扫描速度"
date: 2024-11-04
---
# 大幅提高dirsearch的扫描速度

做题时要对网站进行扫描，先用御剑扫描一下，速度在100/s左右，然后用dirsearch扫描，速度只有10/s，这速度有点幽默了😅
受不了了直接看题解，别人dirsearch两分钟就扫完了
说明是我的dirsearch有问题，网上查资料去

[dirsearch扫描速度慢的bug修复](https://liangmaxwell.github.io/2023/03/26/dirsearch-sao-miao-su-du-man-de-bug-xiu-fu/)

解决办法：
`/lib/controller/controller.py`在550行左右的代码更改一下:

```python
def process(self):
    while True:
        try:
            while not self.fuzzer.is_finished():
                if self.is_timed_out():
                    raise SkipTargetInterrupt(
                        "Runtime exceeded the maximum set by the user"
                    )
                time.sleep(0.75) # 添加这行代码

            break

        except KeyboardInterrupt:
            self.handle_pause()
```

修改后扫描速度300/s左右，终于能用了
