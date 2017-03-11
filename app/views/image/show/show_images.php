<?php
if(!empty($files))
{
    foreach ($files as $file) :?>
        <img border="1" style="border-color:#ccc;" src="<?=$file?>"/><br/>
    <?php endforeach;
}else{
    echo '无图片';
}
