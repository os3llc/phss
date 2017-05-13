<?php
$xml=simplexml_load_file("hymnal.xml");
echo "<pre>";
$allComposers = array();
$composers = array();
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $allComposers[] = $xml->HymnEntry[$i]->Composer;
  if(is_object($xml->HymnEntry[$i]->AddlComposer)) {
    $allComposers[] = $xml->HymnEntry[$i]->AddlComposer;
  } else if(is_array($xml->HymnEntry[$i]->AddlComposer)) {
    for($j = 0; $j < count($xml->HymnEntry[$i]->AddlComposer); $j++) {
      $allComposers[] = $xml->HymnEntry[$i]->AddlComposer[$j];
    }
  }
}
for($i = 0; $i < count($allComposers); $i++) {
  $newComposer = array();
  $myComposer = $allComposers[$i];
  for($j = 0; $j < count($composers); $j++) {
    if($composers[$j]['last'] == (string)$myComposer->Last)
      if($composers[$j]['middle'] == (string)$myComposer->Middle)
        if($composers[$j]['first'] == (string)$myComposer->First)
          if($composers[$j]['suffix'] == (string)$myComposer->Suffix)
            continue 2;
  }
  $newComposer['last'] = (string)$myComposer->Last;
  $newComposer['middle'] = (string)$myComposer->Middle;
  $newComposer['first'] = (string)$myComposer->First;
  $newComposer['suffix'] = (string)$myComposer->Suffix;
  $composers[] = $newComposer;
}
$pgDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
for($i = 0; $i < count($composers); $i++) {
	pg_insert($pgDB,"composers",$composers[$i]);
}
pg_close($pgDB);
echo "</pre>";
?>
