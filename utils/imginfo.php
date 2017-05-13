<?php
$maxWidth = 0;
$maxWidthHymn = 0;
$maxHeight = 0;
$maxHeightHymn = 0;
$files = preg_split('/\s+/', trim(shell_exec("find /srv/www/htdocs/phss/img.orig -type f -iname \*.png")));
for($i = 0; $i < count($files); $i++) {
echo $files[$i] . "\n";
$imagick = new Imagick();
$imagick->readImage($files[$i]);
// echo "Orig: " . strlen($imagick) . ", ";
$imagick->trimImage(0.1);
$imagick->borderImage('white',10,10);
// echo "New: " . strlen($imagick) . "\n";
$myHeight = $imagick->getImageHeight();
$myWidth = $imagick->getImageWidth();
if($myHeight > $maxHeight) {
  $maxHeight = $myHeight;
  $maxHeightHymn = $files[$i];
}
if($myWidth > $maxWidth) {
  $maxWidth = $myWidth;
  $maxWidthHymn = $files[$i];
}
// $dbData = bin2hex($imagick);
// $R = $dbHymn->prepare("UPDATE hymn_elements_img SET img= :img WHERE hymn= :hymn and type= :type and id= :id and imgseq= :imgseq");
// $R->execute([':hymn'=>$imgHymn,':type'=>$imgType,':id'=>$imgId,':imgseq'=>$imgSeq,':img'=>$dbData]);
}

echo "Max Height: " . $maxHeight . " " . $maxHeightHymn . "\n";
echo "Max Width: " . $maxWidth . " " . $maxWidthHymn . "\n";

// $image=imagecreatefromstring(pack('H*',$imgData));
// imagepng($image);
// print(pack('H*',$data));
?>
