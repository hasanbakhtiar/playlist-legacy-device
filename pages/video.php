<?php
const BASE = "../";
require __DIR__ . "/../tools/lib.php";

page("Video", "<ul>" . render_list("video", ["mp4", "3gp"]) . "</ul>");