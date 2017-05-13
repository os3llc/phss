<?php
$xml=simplexml_load_file("hymnal.xml");
$hymnDB = pg_connect("host=127.0.0.1 user=phss dbname=phss");
$authors = "SELECT * FROM authors ORDER BY last,first,suffix,middle;";
$authors = pg_query($hymnDB,$authors);
$authors = pg_fetch_all($authors);
$composers = "SELECT * FROM composers ORDER BY last,first,suffix,middle;";
$composers = pg_query($hymnDB,$composers);
$composers = pg_fetch_all($composers);
echo "<pre>";
$hymns = array();
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $myHymn = array();
  $thisHymn = $xml->HymnEntry[$i];
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  $myHymn['number'] = $myIdx;
  $myHymn['title'] = (string)$thisHymn->Title;
  $myHymn['ckey'] = (string)$thisHymn->attributes()['ControlKey'];
  $myHymn['meter'] = (string)$thisHymn->Hymn->attributes()['Meter'];
  if((int)$thisHymn->Author->attributes()['Year'] > 0)
    $myHymn['author_year'] = (int)$thisHymn->Author->attributes()['Year'];
  if((string)$thisHymn->Author->Role != "")
    $myHymn['author_role'] = (string)$thisHymn->Author->Role;
  if((int)$thisHymn->Composer->attributes()['Year'] > 0)
    $myHymn['composer_year'] = (int)$thisHymn->Composer->attributes()['Year'];
  if((string)$thisHymn->Composer->Role != "")
    $myHymn['composer_role'] = (string)$thisHymn->Composer->Role;
  $author['last'] = (string)$thisHymn->Author->Last;
  $author['first'] = (string)$thisHymn->Author->First;
  $author['middle'] = (string)$thisHymn->Author->Middle;
  $author['suffix'] = (string)$thisHymn->Author->Suffix;
  $composer['last'] = (string)$thisHymn->Composer->Last;
  $composer['first'] = (string)$thisHymn->Composer->First;
  $composer['middle'] = (string)$thisHymn->Composer->Middle;
  $composer['suffix'] = (string)$thisHymn->Composer->Suffix;
  for($j = 0; $j < count($authors); $j++) {
    if($authors[$j]['last'] == $author['last'])
      if($authors[$j]['first'] == $author['first'])
        if($authors[$j]['middle'] == $author['middle'])
          if($authors[$j]['suffix'] == $author['suffix']) {
            $myHymn['author'] = (int)$authors[$j]['id'];
            break;
          }
  }
  for($j = 0; $j < count($composers); $j++) {
    if($composers[$j]['last'] == $composer['last'])
      if($composers[$j]['first'] == $composer['first'])
        if($composers[$j]['middle'] == $composer['middle'])
          if($composers[$j]['suffix'] == $composer['suffix']) {
            $myHymn['composer'] = (int)$composers[$j]['id'];
            break;
          }
  }
  echo pg_insert($hymnDB,"hymns",$myHymn) . "<br />";
}
echo "</pre>";
pg_close($hymnDB);
?>
