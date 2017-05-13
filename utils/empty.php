<?php
// $xml=simplexml_load_file("hymnal.xml");
$hymnDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
$hymns = "SELECT number,title FROM hymns ORDER BY number;";
$hymns = pg_query($hymnDB,$hymns);
$hymns = pg_fetch_all($hymns);
echo "<pre>";
for($i = 0; $i < count($hymns); $i++) {
  $myNum = $hymns[$i]['number'];
  $myPrefix = str_replace("(","_",$hymns[$i]['title']);
  $myPrefix = strtolower(preg_replace("/[^A-Za-z0-9_]/", '', $myPrefix));
  if(count(scandir("img/" . $myNum)) <= 2) {
    echo $myNum . " - " . $hymns[$i]['title'] . ", " . $myPrefix . "<br />";
    foreach(glob("SlideImages/" . $myPrefix . "_*.png") as $imgFile) {
      $newName = preg_replace("/^[^_]*_/",'',$imgFile);
      $newName = preg_replace("/^[^_]*_/",'',$newName);
      echo $myNum . " - " . $imgFile . " - img/" . $myNum . "/" . $newName . "<br />";
      // mkdir("img/" . $myNum);
      // copy($imgFile,"img/" . $myNum . "/" . $newName);
    }
  }
}
echo "</pre>";
?>
