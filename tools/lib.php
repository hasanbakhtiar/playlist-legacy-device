<?php
define("ROOT", dirname(__DIR__));
defined("BASE") || define("BASE", "");

function list_files($dir, $exts) {
    $base = ROOT . "/uploads/$dir";
    if (!is_dir($base)) return [];
    $files = [];
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $f) {
        if ($f->isFile() && in_array(strtolower($f->getExtension()), $exts)) {
            $files[] = str_replace("\\", "/", substr($f->getPathname(), strlen($base) + 1));
        }
    }
    sort($files);
    return $files;
}

function file_url($dir, $rel) {
    return BASE . "uploads/$dir/" . implode("/", array_map("rawurlencode", explode("/", $rel)));
}

function item_media($dir, $rel) {
    $name = basename($rel);
    $label = htmlspecialchars(pathinfo($name, PATHINFO_FILENAME));
    $type = strtoupper(pathinfo($name, PATHINFO_EXTENSION));
    return '<li><a href="' . file_url($dir, $rel) . '">' . $label . '</a> (' . $type . ')</li>';
}

function item_game($dir, $rel) {
    $jad = preg_replace('/\.jar$/i', '.jad', $rel);
    $target = is_file(ROOT . "/uploads/$dir/$jad") ? $jad : $rel;
    $label = htmlspecialchars(pathinfo($rel, PATHINFO_FILENAME));
    return '<li><a href="' . file_url($dir, $target) . '">' . $label . '</a></li>';
}

function render_section($dir, $exts, $item) {
    $groups = [];
    foreach (list_files($dir, $exts) as $rel) {
        $groups[dirname($rel)][] = $rel;
    }
    ksort($groups);
    $html = "";
    foreach ($groups as $folder => $items) {
        if ($folder !== ".") {
            $html .= '<li><b>' . htmlspecialchars($folder) . '</b></li>';
        }
        foreach ($items as $rel) {
            $html .= $item($dir, $rel);
        }
    }
    return $html ?: "<li>Empty</li>";
}

function render_list($dir, $exts) {
    return render_section($dir, $exts, "item_media");
}

function render_games() {
    return render_section("games", ["jar"], "item_game");
}

function footer() {
    return '<p style="font-size:11px;color:#666666;margin-top:12px;">'
         . '&copy; ' . date("Y") . ' <a href="http://webluna.org">Webluna Software</a></p>';
}

function page($title, $body, $home = true) {
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