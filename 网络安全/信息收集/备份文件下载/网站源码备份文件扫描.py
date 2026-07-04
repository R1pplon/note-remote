import requests

url1 = 'http://challenge-b7ec033934829d86.sandbox.ctfhub.com:10800'

#网站源码备份文件常用名
list1 = [
'web',
'website',
'backup',
'back',
'www',
'wwwroot',
'temp',
'db',
'data',
'code',
'test',
'admin',
'user',
'sql'
]

# 常见的网站源码备份文件后缀
list2 = ['tar', 'tar.gz', 'zip', 'rar', '7-zip', '7z']
for i in list1:
    for j in list2:
        back = str(i) + '.' + str(j)
        url = str(url1) + '/' + back
        print(url + '    ', end='')
        print(requests.get(url).status_code)
        if requests.get(url).status_code == 200:
            print('存在',end='')
            print(url)
