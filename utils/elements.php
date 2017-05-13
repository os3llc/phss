<?php
function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = pg_escape_string($t);
        }
    }
    return '{' . implode(",", $result) . '}'; // format
}
$xml=simplexml_load_file("hymnal.xml");
$hymnDB = pg_connect("host=127.0.0.1 user=phss dbname=phss");
echo "<pre>";
for($i = 0; $i < count($xml->HymnEntry); $i++) {
  $myHymn = array();
  $thisHymn = $xml->HymnEntry[$i];
  $myIdx = (int)$thisHymn->attributes()['HymnNumber'];
  for($j = 0; $j < count($thisHymn->Hymn->HymnElement); $j++) {
    $order = $j + 1;
    $thisEl = $thisHymn->Hymn->HymnElement[$j];
    $thisType = strtolower((string)$thisEl->attributes()['HymnElementCategory']);
    $myEl = (array)$thisEl->HymnLine;
    $inEl = to_pg_array($myEl);
    $q = "SELECT max(id) AS id FROM hymn_elements WHERE hymn=" . $myIdx . " AND type='" . $thisType . "';";
    $q = pg_query($hymnDB,$q);
    $q = pg_fetch_all($q);
    $last_id = (int)$q[0]['id'];
    if($last_id > 0)
     $last_id++;
    else
     $last_id = 1;
    $INSERT = "INSERT INTO hymn_elements VALUES(" . $myIdx . ",'" . $thisType . "'," . $last_id . ",'" . $inEl . "'," . $order . ");";
    $IQ = pg_query($hymnDB,$INSERT);
  }
}
echo "</pre>";
pg_close($hymnDB);
?>
