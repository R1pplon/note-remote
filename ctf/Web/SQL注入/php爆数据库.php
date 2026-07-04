<?php

$host = "localhost";
$user = "root";
$pass = "123456";

try {
    // 创建PDO实例
    $pdo = new PDO("mysql:host=$host", $user,$pass);

    // 设置PDO错误模式为异常
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 获取所有数据库的列表
    $databases =$pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);

    // 遍历每个数据库
    foreach ($databases as$database) {
        // 选择数据库
        $pdo->query("USE `$database`");

        // 获取当前数据库的所有表
        $tables =$pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        // 遍历每个表
        foreach ($tables as$table) {
            // 获取表中的所有数据
            $stmt =$pdo->query("SELECT * FROM `$table`");
            $rows =$stmt->fetchAll(PDO::FETCH_ASSOC);

            // 输出表名
            echo "Database: $database, Table:$table<br>";

            // 输出表数据
            foreach ($rows as$row) {
                echo '<pre>';
                print_r($row);
                echo '</pre>';
            }
        }
    }
} catch (PDOException $e) {
    // 输出异常信息
    die("数据库连接失败: " . $e->getMessage());
}
?>
