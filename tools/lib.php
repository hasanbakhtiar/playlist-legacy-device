<?php
define("ROOT", dirname(__DIR__));
defined("BASE") || define("BASE", "");

function list_files($dir, $exts)
{
    $pattern = ROOT . "/uploads/$dir/*.{" . implode(",", $exts) . "}";
    $files = glob($pattern, GLOB_BRACE) ?: [];
    sort($files);
    return $files;
}

function render_list($dir, $exts)
{
    $html = "";
    foreach (list_files($dir, $exts) as $path) {
        $name = basename($path);
        $url = BASE . "uploads/$dir/" . rawurlencode($name);
        $label = htmlspecialchars(pathinfo($name, PATHINFO_FILENAME));
        $type = strtoupper(pathinfo($name, PATHINFO_EXTENSION));
        $html .= '<li><a href="' . $url . '">' . $label . '</a> (' . $type . ')</li>';
    }
    return $html ?: "<li>Empty</li>";
}

function render_games()
{
    $html = "";
    foreach (list_files("games", ["jar"]) as $path) {
        $name = pathinfo($path, PATHINFO_FILENAME);
        $ext = is_file(ROOT . "/uploads/games/$name.jad") ? "jad" : "jar";
        $url = BASE . "uploads/games/" . rawurlencode($name) . ".$ext";
        $html .= '<li><a href="' . $url . '">' . htmlspecialchars($name) . '</a></li>';
    }
    return $html ?: "<li>Empty</li>";
}

function footer()
{
    return '<p style="font-size:11px;color:#666666;margin-top:12px;">'
        . '&copy; ' . date("Y") . ' <a href="http://webluna.org">Webluna Software</a></p>';
}

function page($title, $body, $home = true)
{
    echo '<html><head><title>' . $title . '</title>'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<style type="text/css">'
        . 'body{font-family:Arial,sans-serif;font-size:14px;background:#ffffff;color:#000000;margin:4px;}'
        . 'h3{font-size:16px;color:#0033cc;margin:6px 0;}'
        . 'li{margin-bottom:6px;}a{color:#0033cc;}'
        . '</style></head><body>'
        . ($home ? '<p><a href="' . BASE . 'index.php">Home</a></p>' : '')
        . '<h3>' . $title . '</h3>' . $body
        . footer()
        . '</body></html>';
}
