<?php
// $xml=simplexml_load_file("hymnal.xml");
$dbHymn = new PDO('pgsql:host=127.0.0.1 user=phss dbname=phss');
$Q = $dbHymn->prepare("SELECT img FROM hymn_elements_img WHERE hymn=1 AND type='verse' AND id=1 AND imgseq='a';");
$hymns = $Q->execute();
$Q->bindColumn('img', $imgData, \PDO::PARAM_STR);
$Q->fetch(\PDO::FETCH_BOUND);
header("Content-type: image/png");
$imagick = new Imagick();
$imagick->readImageBlob(pack('H*',$imgData));
// $imagick->trimImage(0.1);
// $imagick->borderImage('white',10,10);
echo $imagick;
// $image=imagecreatefromstring(pack('H*',$imgData));
// imagepng($image);
// print(pack('H*',$data));
?>
