<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '-1');


echo '<pre>';
print_r($_SERVER);
echo '<pre>';
exit;



function isBrowserAllowed() {
    // $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36';
    $skipList = [
        'Wget', 'SemrushBot', 'Barkrowler', 'AhrefsBot', 'YandexBot', 'MJ12bot', 'DotBot', 'ImagesiftBot',
        'ClaudeBot', 'bingbot', 'Googlebot',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36'
    ];

    // $pattern = '/' . implode('|', array_map('preg_quote', $skipList)) . '/i';
    $pattern = '~' . implode('|', array_map('preg_quote', $skipList, array_fill(0, count($skipList), '~'))) . '~i';

    $matches = preg_grep($pattern, [$userAgent]);

    if (!empty($matches)) {
        return false;
    }

    return true;
}

echo isBrowserAllowed() ? 1 : 0;