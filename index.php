<?php
const BASE = "";
require __DIR__ . "/tools/lib.php";

page("Playlist", '<ul>'
    . '<li><a href="pages/video.php">Video</a></li>'
    . '<li><a href="pages/audio.php">Audio</a></li>'
    . '<li><a href="pages/games.php">Games</a></li>'
    . '</ul>', false);