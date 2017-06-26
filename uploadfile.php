<?php
if (!isset($_FILES['image'])){
    die ("<h1>:-/</h1>");
}
$file = $_FILES['image'];
file_put_contents(basename($file['name']), file_get_contents($file['tmp_name']));
?>
