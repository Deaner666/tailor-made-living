<?php

echo "You made it to mtd-hires-image-save.php. Congratulations";

$image_data = file_get_contents($_REQUEST['url']);

echo $image_data;