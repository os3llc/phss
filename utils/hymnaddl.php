<?php
$hymnDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
$xml=simplexml_load_file("hymnal.xml");
$Q = "SELECT * FROM authors ORDER BY last,first,middle,suffix;";
$Q = pg_query($hymnDB,$Q);
$authors = pg_fetch_all($Q);
$Q = "SELECT * FROM composers ORDER BY last,first,middle,suffix;";
$Q = pg_query($hymnDB,$Q);
$composers = pg_fetch_all($Q);
echo "<pre>";
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $myHymn = array();
  $thisHymn = $xml->HymnEntry[$i];
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  for($j = 0; $j < count($thisHymn->AddlAuthor); $j++) {
    $thisAuthor = array();
    $thisAuthor['first'] = (string)$thisHymn->AddlAuthor[$j]->First;
    $thisAuthor['last'] = (string)$thisHymn->AddlAuthor[$j]->Last;
    $thisAuthor['middle'] = (string)$thisHymn->AddlAuthor[$j]->Middle;
    $thisAuthor['suffix'] = (string)$thisHymn->AddlAuthor[$j]->Suffix;
    $thisAuthor['role'] = (string)$thisHymn->AddlAuthor[$j]->Role;
    $thisAuthor['year'] = (int)$thisHymn->AddlAuthor[$j]->attributes()['Year'];
    for($k = 0; $k < count($authors); $k++) {
      if($thisAuthor['last'] == $authors[$k]['last'])
        if($thisAuthor['first'] == $authors[$k]['first'])
          if($thisAuthor['middle'] == $authors[$k]['middle'])
            if($thisAuthor['suffix'] == $authors[$k]['suffix']) {
              $inAuthor = array();
              $inAuthor['hymn'] = $myIdx;
              $inAuthor['author'] = $authors[$k]['id'];
              if($thisAuthor['year'] > 0)
                $inAuthor['year'] = $thisAuthor['year'];
              if($thisAuthor['role'] != "")
                $inAuthor['role'] = $thisAuthor['role'];
              pg_insert($hymnDB,"hymn_addl_authors",$inAuthor);
           }
    }
  }
  for($j = 0; $j < count($thisHymn->AddlComposer); $j++) {
    $thisComposer = array();
    $thisComposer['first'] = (string)$thisHymn->AddlComposer[$j]->First;
    $thisComposer['last'] = (string)$thisHymn->AddlComposer[$j]->Last;
    $thisComposer['middle'] = (string)$thisHymn->AddlComposer[$j]->Middle;
    $thisComposer['suffix'] = (string)$thisHymn->AddlComposer[$j]->Suffix;
    $thisComposer['role'] = (string)$thisHymn->AddlComposer[$j]->Role;
    $thisComposer['year'] = (int)$thisHymn->AddlComposer[$j]->attributes()['Year'];
    for($k = 0; $k < count($composers); $k++) {
      if($thisComposer['last'] == $composers[$k]['last'])
        if($thisComposer['first'] == $composers[$k]['first'])
          if($thisComposer['middle'] == $composers[$k]['middle'])
            if($thisComposer['suffix'] == $composers[$k]['suffix']) {
              $inComposer = array();
              $inComposer['hymn'] = $myIdx;
              $inComposer['composer'] = $composers[$k]['id'];
              if($thisComposer['year'] > 0)
                $inComposer['year'] = $thisComposer['year'];
              if($thisComposer['role'] != "")
                $inComposer['role'] = $thisComposer['role'];
              pg_insert($hymnDB,"hymn_addl_composers",$inComposer);
           }
    }
  }
}
echo "</pre>";
?>
