<?php
$dbHymn = new PDO('pgsql:host=127.0.0.1 user=phss dbname=phss');
$Q = $dbHymn->prepare("SELECT hymn,type,id,imgseq,img FROM hymn_elements_img ORDER BY hymn,type,id,imgseq;");
$hymns = $Q->execute();
$Q->bindColumn('img', $imgData, \PDO::PARAM_STR);
$Q->bindColumn('hymn', $imgHymn, \PDO::PARAM_INT);
$Q->bindColumn('type', $imgType, \PDO::PARAM_STR);
$Q->bindColumn('id', $imgId, \PDO::PARAM_INT);
$Q->bindColumn('imgseq', $imgSeq, \PDO::PARAM_STR);
while($Q->fetch(\PDO::FETCH_BOUND)) {
    echo $imgHymn . "/" . $imgType . "/" . $imgId . "/" . $imgSeq . "\n";
    $imagick = new Imagick();
    $imagick->readImageBlob(pack('H*',$imgData));
    $imagick->trimImage(0.1);
    $imagick->borderImage('white',10,10);
    // $dbData = bin2hex($imagick);
    $R = $dbHymn->prepare("UPDATE hymn_elements_img SET img= :img WHERE hymn= :hymn and type= :type and id= :id and imgseq= :imgseq");
    $R->execute([':hymn'=>$imgHymn,':type'=>$imgType,':id'=>$imgId,':imgseq'=>$imgSeq,':img'=>bin2hex($imagick)]);
}

?>
