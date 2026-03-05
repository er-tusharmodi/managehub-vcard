<?php
$templateDir = __DIR__ . '/vcard-template';
$dirs = glob($templateDir . '/*/default.json');
$anyCorruption = false;

foreach ($dirs as $file) {
    $tmpl = basename(dirname($file));
    $tpl  = json_decode(file_get_contents($file), true) ?? [];
    foreach ($tpl as $sec => $val) {
        if (!is_array($val)) continue;
        $keys   = array_keys($val);
        $hasInt = false;
        $hasStr = false;
        foreach ($keys as $k) {
            if (is_int($k)) $hasInt = true;
            else $hasStr = true;
        }
        if ($hasInt && $hasStr) {
            echo "CORRUPTED: $tmpl / $sec — keys: " . implode(', ', array_slice($keys, 0, 10)) . PHP_EOL;
            $anyCorruption = true;
        }
    }
}

if (!$anyCorruption) {
    echo "All clean — no corruption found." . PHP_EOL;
}
