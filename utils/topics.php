<?php
$xml=simplexml_load_file("hymnal.xml");
$hymnDB = pg_connect("host=127.0.0.1 dbname=phss user=phss");
$topics = "SELECT * FROM topics ORDER BY topic;";
$topics = pg_query($hymnDB,$topics);
$topics = pg_fetch_all($topics);
echo "<pre>";
echo print_r($xml->HymnEntry[0],true);
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $myHymn = array();
  $thisHymn = $xml->HymnEntry[$i];
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  $thisTopic = $thisHymn->HymnData->Topic;
  if(is_array($thisTopic) || is_object($thisTopic))
    for($j = 0; $j < count($thisTopic); $j++) {
      for($k = 0; $k < count($topics); $k++) {
        if((string)$thisTopic[$j] == $topics[$k]['topic']) {
          $myTopic = array();
          $myTopic['hymn'] = $myIdx;
          $myTopic['topic'] = $topics[$k]['id'];
          pg_insert($hymnDB,"hymn_topics",$myTopic);
        }
      }
    }
  else
    for($j = 0; $j < count($topics); $j++)
      if((string)$thisTopic == $topics[$j]['topic']) {
        $myTopic = array();
        $myTopic['hymn'] = $myIdx;
        $myTopic['topic'] = $topics[$j]['id'];
        pg_insert($hymnDB,"hymn_topics",$myTopic);
      }
}
pg_close($hymnDB);
?>
