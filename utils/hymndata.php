<?php
ini_set("max_execution_time",600);
$xml=simplexml_load_file("hymnal.xml");
$hymnDB=pg_connect("host=127.0.0.1 dbname=phss user=phss");
echo "<pre>";
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $myHymn = array();
  $thisHymn = $xml->HymnEntry[$i];
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  echo $myIdx . ", ";
  $myData = $thisHymn->HymnData;
  $hymnData = array();
  $hymnData['hymn'] = $myIdx;
  $hymnData['copyright'] = (string)$myData->Copyright;
  $hymnData['key'] = (string)$myData->Key;
  $hymnData['keyscale'] = (string)$myData->KeyScale;
  $hymnData['startnote'] = (string)$myData->StartingNote;
  $hymnData['timesignature'] = (string)$myData->TimeSignature;
  $hymnData['tune'] = (string)$myData->HymnTune;
  $hymnData['summary'] = (string)$myData->HymnSummary;
  $Q = pg_insert($hymnDB,"hymn_data",$hymnData);
  echo pg_result_error($Q);
  for($j = 0; $j < count($myData->Notes); $j++) {
   $hymnNote = array();
   $hymnNote['hymn'] = $myIdx;
   $hymnNote['note'] = (string)$myData->Notes[$j];
   $Q = pg_insert($hymnDB,"hymn_data_notes",$hymnNote);
   echo pg_result_error($Q);
  }
  $myProcess = (array)$thisHymn->Process;
  foreach($myProcess as $procKey => $procVal) {
    $hymnProcess = array();
    if($procKey == "@attributes")
      continue;
    else if($procKey == "History")
      continue;
    else {
      $hymnProcess['hymn'] = $myIdx;
      $hymnProcess['step'] = (string)$procKey;
      $hymnProcess['initials'] = (string)$procVal;
      $Q = pg_insert($hymnDB,"hymn_process",$hymnProcess);
      echo pg_result_error($Q);
    }
  }
  $myHistory = $thisHymn->Process->History->HistoryEntry;
  for($j = 0; $j < count($myHistory); $j++) {
    $hymnHistory = array();
    $hymnHistory['hymn'] = $myIdx;
    $hymnHistory['step'] = (string)$myHistory[$j]->attributes()->step;
    $hymnHistory['steptime'] = (string)$myHistory[$j]->attributes()->when;
    $hymnHistory['initials'] = (string)$myHistory[$j]->attributes()->who;
    $Q = pg_insert($hymnDB,"hymn_process_history",$hymnHistory);
    echo pg_result_error($Q);
  }
}
echo "</pre>";
pg_close($hymnDB);
?>
