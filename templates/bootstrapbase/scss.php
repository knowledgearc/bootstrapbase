<?php
require_once 'scss.inc.php';
$scss = new scssc();
$imported_scss_file = array();
foreach (glob("scss/*.scss") as $file) {
 $imported_scss_file[] = $scss->compile('@import "'.$file.'";');
}
$file =  file_put_contents("css/navigation.css", $imported_scss_file);


