<?php
$xml=simplexml_load_file("hymnal.xml");
$hymnDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
echo "<pre>";
$sources = "SELECT * FROM capture_sources;";
$sources = pg_query($hymnDB,$sources);
$sources = pg_fetch_all($sources);
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $thisHymn = $xml->HymnEntry[$i];
  $thisSources = $thisHymn->HymnData->CaptureSource;
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  for($j = 0; $j < count($thisSources); $j++) {
    for($k = 0; $k < count($sources); $k++) {
      if((string)$thisSources[$j] == $sources[$k]['source']) {
        $mySrc = array();
        $mySrc['hymn'] = $myIdx;
        $mySrc['source'] = $sources[$k]['id'];
        pg_insert($hymnDB,"hymn_sources",$mySrc);
      }
    }
  }
}
echo "</pre>";
pg_close($hymnDB);
?>
