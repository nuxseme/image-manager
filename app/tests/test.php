<?php
require('autoload.php');
$file_info = getimagesize('source/cat.png');
print_r($file_info);

$file = new \app\models\image\ImageEntity('source/cat.png');
echo $file->format();
print_r( $file->getInfo() );
echo $file->getDir();
echo $file->getSize();




