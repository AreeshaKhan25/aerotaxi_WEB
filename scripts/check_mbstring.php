<?php
echo "PHP_BINARY=" . PHP_BINARY . "\n";
echo "PHP_VERSION=" . PHP_VERSION . "\n";
echo "php_ini_loaded_file=" . php_ini_loaded_file() . "\n";
echo "extension_loaded(mbstring)=" . (extension_loaded('mbstring') ? 'yes' : 'no') . "\n";
echo "function_exists(mb_split)=" . (function_exists('mb_split') ? 'yes' : 'no') . "\n";
$exts = get_loaded_extensions();
sort($exts);
echo "loaded_extensions=" . implode(',', $exts) . "\n";
