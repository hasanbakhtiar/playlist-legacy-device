<?php
const BASE = "../";
require __DIR__ . "/../tools/lib.php";

page("Games", "<ul>" . render_games() . "</ul>");