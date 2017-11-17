<?php
$array = array('Stylesheetssss' => 'csss', 'Javascripts' => 'jsss');

foreach($array as $dir){
    if ( !file_exists($dir) ) {
        mkdir($dir, 0777, true) ;
        echo $dir . ' directory has been created.';
    }
}
?>