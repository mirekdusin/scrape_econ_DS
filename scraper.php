<?php

$file = file_get_contents("./pages");

$lines = explode(PHP_EOL, $file);

const url_root = "https://www.econ.muni.cz";

$results = array();

$file = fopen("./results", "w");

foreach ($lines as $line) {
    $url = url_root . $line;
    $page_content = file_get_contents($url);

    if (!$page_content) {
        continue;
    }

    array_push($results, $url);
    $found = false;

    $dom = new DOMDocument;
    $dom->loadHTML($page_content);

    foreach ($dom->getElementsByTagName('a') as $node) {
        $ur = preg_replace("/\s+/", "", $node->getAttribute("href"));

        if (strpos($ur, "/do/econ/") !== false) {
            if (strpos($ur, "http") !== false) {
                $results[sizeof($results) - 1] .= "," . $ur;
            }else{
                $results[sizeof($results) - 1] .= ",https://is.muni.cz/auth" . $ur;
            }
            $found = true;
        }
    }

    if (!$found) {
        array_pop($results);
    }
}

foreach ($results as $line) {
    fwrite($file, $line . "\n");
}

fclose($file);
