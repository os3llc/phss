<?php
// $xml=simplexml_load_file("hymnal.xml");
$hymnDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
$hymns = "SELECT number,title FROM hymns ORDER BY number;";
$hymns = pg_query($hymnDB,$hymns);
$hymns = pg_fetch_all($hymns);
echo "<pre>";
for($i = 0; $i < count($hymns); $i++) {
  $myPrefix = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $hymns[$i]['title']));
  $myNum = $hymns[$i]['number'];
  foreach(glob("SlideImages/" . $myPrefix . "_*.png") as $imgFile) {
    $newName = preg_replace("/^[^_]*_/",'',$imgFile);
    echo $myNum . " - " . $imgFile . " - img/" . $myNum . "/" . $newName . "<br />";
    mkdir("img/" . $myNum);
    copy($imgFile,"img/" . $myNum . "/" . $newName);
  }
}
echo "</pre>";
?>
