<?php
$dbHymn = new PDO('pgsql:host=127.0.0.1 user=phss dbname=phss');
$Q = $dbHymn->prepare("SELECT number,title FROM hymns ORDER BY number;");
$hymns = $Q->execute();
$hymns = $Q->fetchAll();
for($i = 0; $i < count($hymns); $i++) {
  $myNum = $hymns[$i]['number'];
  echo $myNum . "\n";
  $myElements = array();
  $E = $dbHymn->prepare("SELECT elseq,type,id FROM hymn_elements WHERE hymn=" . $myNum . " ORDER BY hymn,elseq;");
  $myElements = $E->execute();
  $myElements = $E->fetchAll(PDO::FETCH_CLASS);
  for($j = 0; $j < count($myElements); $j++) {
    $thisEl = (array)$myElements[$j];
    $myType = $thisEl['type'];
    $myId = $thisEl['id'];
    $elSeq = $thisEl['elseq'];
    if($myType == "verse")
      $fName = "img/" . $myNum . "/" . $myType . "_" . $myId . "_*.png";
    else
      $fName = "img/" . $myNum . "/" . $myType . "_*.png";

    foreach(glob($fName) as $imgFile) {
      echo $myNum . " - " . $imgFile . "<br />";
      $dbHymn->beginTransaction();
      $dbData = bin2hex(file_get_contents($imgFile));
      /*
      $dbData = $dbHymn->pgsqlLOBCreate();
      $dbStream = $dbHymn->pgsqlLOBOpen($dbData,'w');
      $myHandle = fopen($imgFile,"rb");
      stream_copy_to_stream($myHandle,$dbStream);
      */
      $fileParts = split("/",$imgFile);
      $myFile = $fileParts[2];
      $myParts = split("_",$myFile);
      if($myType != "verse")
        $mySeq = str_replace(".png","",$myParts[1]);
      else
        $mySeq = str_replace(".png","",$myParts[2]);
      $myIn = $dbHymn->prepare("INSERT INTO hymn_elements_img(hymn,type,id,imgseq,elseq,img) VALUES(:myHymn, :myType, :myId, :mySeq, :elSeq, :myImg)");
      $myIn->execute([':myHymn'=>$myNum,':myType'=>$myType,':myId'=>$myId,':mySeq'=>$mySeq,':elSeq'=>$elSeq,':myImg'=>$dbData]);
      /*
      $myIn->bindParam(':myHymn', $myNum);
      $myIn->bindParam(':myType', $myType);
      $myIn->bindParam(':myId', $myId);
      $myIn->bindParam(':mySeq', $mySeq);
      $myIn->bindParam(':elSeq', $elSeq);
      $myIn->bindParam(':myImg', $dbData);
      $myIn->execute();
      */
      $dbHymn->commit();
    }
  }
}
?>
