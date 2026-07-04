<?php
// 读取过滤条件
$filters = file('./过滤条件.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
// 读取payload
$payloads = file('./无参RCE_payload.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// 定义黑名单（可自定义添加）
$blacklist = [' ','\t','\r','\n','\+','\[','\^','\]','\"','\-','\$','\*','\?','\<','\>','\=','\`',];

foreach ($payloads as $payload) {
    // 黑名单检查
    $blacklisted = false;
    foreach ($blacklist as $blackitem) {
        if (preg_match('/' . $blackitem . '/', $payload)) {
            $blacklisted = true;
            break;
        }
    }

    if ($blacklisted) {
        // 跳过黑名单payload
        continue;
    }

    $passed = true;

    foreach ($filters as $filter) {
        // 提取条件表达式（移除if和括号）
        preg_match('/if\s*\((.*)\)/', $filter, $matches);
        if (empty($matches)) continue;

        $expression = $matches[1];

        // 自动识别变量名并替换
        $expression = preg_replace_callback(
            '/(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\[[^\]]+\])?)/',
            function($matches) use ($payload) {
                return var_export($payload, true);
            },
            $expression
        );

        // 执行条件判断
        try {
            $result = eval("return $expression;");
            if (!$result) {
                $passed = false;
                break;
            }
        } catch (Throwable $e) {
            $passed = false;
            break;
        }
    }

    if ($passed) {
        echo $payload . PHP_EOL;
    }
}
?>