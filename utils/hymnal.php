<?php
$xml=simplexml_load_file("hymnal.xml");
echo "<pre>";
echo print_r($xml->HymnEntry[0],true);
$hymns = array();
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $myHymn = array();
  $thisHymn = $xml->HymnEntry[$i];
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  $myHymn['title'] = (string)$thisHymn->Title;
  $myHymn['controlkey'] = (string)$thisHymn->attributes()['ControlKey'];
  $myHymn['author']['last'] = (string)$thisHymn->Author->Last;
  $myHymn['author']['first'] = (string)$thisHymn->Author->First;
  $myHymn['author']['middle'] = (string)$thisHymn->Author->Middle;
  $myHymn['author']['role'] = (string)$thisHymn->Author->Role;
  $myHymn['author']['year'] = (int)$thisHymn->Author->attributes()['Year'];
  $myHymn['composer']['last'] = (string)$thisHymn->Composer->Last;
  $myHymn['composer']['first'] = (string)$thisHymn->Composer->First;
  $myHymn['composer']['middle'] = (string)$thisHymn->Composer->Middle;
  $myHymn['composer']['role'] = (string)$thisHymn->Composer->Role;
  $myHymn['composer']['year'] = (int)$thisHymn->Composer->attributes()['Year'];
  $myHymn['meter'] = (string)$thisHymn->Hymn->attributes()['Meter'];
  $myHymn['authors'] = array();
  for($j = 0; $j < count($thisHymn->AddlAuthor); $j++) {
    $myHymn['authors'][$j]['first'] = (string)$thisHymn->AddlAuthor[$j]->First;
    $myHymn['authors'][$j]['last'] = (string)$thisHymn->AddlAuthor[$j]->Last;
    $myHymn['authors'][$j]['middle'] = (string)$thisHymn->AddlAuthor[$j]->Middle;
    $myHymn['authors'][$j]['role'] = (string)$thisHymn->AddlAuthor[$j]->Role;
    $myHymn['authors'][$j]['year'] = (int)$thisHymn->AddlAuthor[$j]->attributes()['Year'];
  }
  $myHymn['composers'] = array();
  for($j = 0; $j < count($thisHymn->AddlComposer); $j++) {
    $myHymn['composers'][$j]['first'] = (string)$thisHymn->AddlComposer[$j]->First;
    $myHymn['composers'][$j]['last'] = (string)$thisHymn->AddlComposer[$j]->Last;
    $myHymn['composers'][$j]['middle'] = (string)$thisHymn->AddlComposer[$j]->Middle;
    $myHymn['composers'][$j]['role'] = (string)$thisHymn->AddlComposer[$j]->Role;
    $myHymn['composers'][$j]['year'] = (int)$thisHymn->AddlComposer[$j]->attributes()['Year'];
  }
  $myHymn['elements'] = array();
  for($j = 0; $j < count($thisHymn->Hymn->HymnElement); $j++) {
    $order = $j + 1;
    $thisEl = $thisHymn->Hymn->HymnElement[$j];
    $thisType = $thisEl->attributes()['HymnElementCategory'];
    if($thisType == "Verse") {
      $myHymn['elements']['verses'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Chorus") {
      $myHymn['elements']['chorus'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Coda") {
      $myHymn['elements']['coda'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Refrain") {
      $myHymn['elements']['refrain'][$order] = $thisEl->HymnLine;
    } else if($thisType == "CounterParts") {
      $myHymn['elements']['counterparts'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Descant") {
      $myHymn['elements']['descant'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Amen") {
      $myHymn['elements']['amen'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Prolog") {
      $myHymn['elements']['prolog'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Bridge") {
      $myHymn['elements']['bridge'][$order] = $thisEl->HymnLine;
    } else if($thisType == "Sanctus") {
      $myHymn['elements']['sanctus'][$order] = $thisEl->HymnLine;
    } else {
      echo $myIdx . " - " . $thisType . "<br />";
    }
  }
  $hymns[$myIdx] = $myHymn;
}
echo print_r($hymns,true);
echo "</pre>";
?>
