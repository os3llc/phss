<?php
$xml=simplexml_load_file("hymnal.xml");
echo "<pre>";
$allAuthors = array();
$authors = array();
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $allAuthors[] = $xml->HymnEntry[$i]->Author;
  if(is_object($xml->HymnEntry[$i]->AddlAuthor)) {
    $allAuthors[] = $xml->HymnEntry[$i]->AddlAuthor;
  } else if(is_array($xml->HymnEntry[$i]->AddlAuthor)) {
    for($j = 0; $j < count($xml->HymnEntry[$i]->AddlAuthor); $j++) {
      $allAuthors[] = $xml->HymnEntry[$i]->AddlAuthor[$j];
    }
  }
}
for($i = 0; $i < count($allAuthors); $i++) {
  $newAuthor = array();
  $myAuthor = $allAuthors[$i];
  for($j = 0; $j < count($authors); $j++) {
    if($authors[$j]['last'] == (string)$myAuthor->Last)
      if($authors[$j]['middle'] == (string)$myAuthor->Middle)
        if($authors[$j]['first'] == (string)$myAuthor->First)
          if($authors[$j]['suffix'] == (string)$myAuthor->Suffix)
            continue 2;
  }
  $newAuthor['last'] = (string)$myAuthor->Last;
  $newAuthor['middle'] = (string)$myAuthor->Middle;
  $newAuthor['first'] = (string)$myAuthor->First;
  $newAuthor['suffix'] = (string)$myAuthor->Suffix;
  $authors[] = $newAuthor;
}
$pgDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
for($i = 0; $i < count($authors); $i++) {
	pg_insert($pgDB,"authors",$authors[$i]);
}
pg_close($pgDB);
echo "</pre>";
?>
