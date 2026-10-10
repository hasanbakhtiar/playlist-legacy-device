<?php
const BASE = "../";
require __DIR__ . "/../tools/lib.php";

page("Audio", "<ul>" . render_list("audio", ["mp3"]) . "</ul>");