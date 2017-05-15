<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time','300');
date_default_timezone_set('America/New_York');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Shape\Drawing;
use PhpOffice\PhpPresentation\Shape\RichText\Paragraph;
use PhpOffice\PhpPresentation\Slide\Background\Color as BGColor;
use PhpOffice\PhpPresentation\Slide\Transition;
use PhpOffice\PhpPresentation\DocumentLayout;

function dbConnect() {
  $myDB = new PDO('pgsql:host=127.0.0.1 user=phss dbname=phss');
  return $myDB;
}

function createPres($layout) {
  $thisLayout = new DocumentLayout();
  if($layout == "widescreen")
    $thisLayout->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9, true);
  else
    $thisLayout->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_4X3, true);
  $thisPres = new PhpPresentation();
  $thisPres->setLayout($thisLayout);
  $thisPres->removeSlideByIndex(0);
  return $thisPres;
}

function addTitleSlide(&$pres, $hymnInfo) {
  $thisSlide = $pres->createSlide();
  $bgBlack = new BGColor();
  $bgBlack->setColor(new Color(Color::COLOR_BLACK));
  $thisSlide->setBackground($bgBlack);
  $thisTrans = new Transition();
  $thisTrans->setManualTrigger(true)
            ->setTimeTrigger(false)
            ->setTransitionType(Transition::TRANSITION_PUSH_RIGHT)
            ->setSpeed(Transition::SPEED_FAST);
  $thisSlide->setTransition($thisTrans);
  $titleText = $thisSlide->createRichTextShape()
              ->setHeight(50)
              ->setWidth($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL))
              ->setOffsetX(0);
  $titleText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
  $titleText->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
  $titleRun = $titleText->createTextRun($hymnInfo['title']);
  $titleRun->getFont()->setBold(TRUE)->setSize(32)->setColor(new Color('FFFFFF'));
  $titleText->setOffsetY(($pres->getLayout()->getCY(DocumentLayout::UNIT_PIXEL) / 2) - ($titleText->getHeight() / 2));
  $authorText = $thisSlide->createRichTextShape()
                          ->setHeight(50)
                          ->setWidth($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL))
                          ->setOffsetX(0);
  $authorText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
  $authorText->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
  $authorRun = $authorText->createTextRun("Author: " . $hymnInfo['author'] . " / Composer: " . $hymnInfo['composer']);
  $authorRun->getFont()->setBold(FALSE)->setSize(24)->setColor(new Color('FFFFFF'));
  $authorText->setOffsetY(($pres->getLayout()->getCY(DocumentLayout::UNIT_PIXEL) / 2) + ($titleText->getHeight() / 2) + ($authorText->getHeight() / 2));
  $copyrightText = $thisSlide->createRichTextShape()
                             ->setHeight(25)
                             ->setWidth($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)/2)
                             ->setOffsetX(10)
                             ->setOffsetY($pres->getLayout()->getCY(DocumentLayout::UNIT_PIXEL) - 35);
  $copyrightText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
  $copyrightText->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
  $copyrightRun = $copyrightText->createTextRun($hymnInfo['copyright']);
  $copyrightRun->getFont()->setBold(FALSE)->setSize(12)->setColor(new Color('FFFFFF'));
  $keyMeterText = $thisSlide->createRichTextShape()
                            ->setHeight(100)
                            ->setWidth($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)/2)
                            ->setOffsetX($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)/2)
                            ->setOffsetY($pres->getLayout()->getCY(DocumentLayout::UNIT_PIXEL) - 110);
  $keyMeterText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
  $keyMeterText->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
  $keyMeterRun = $keyMeterText->createTextRun($hymnInfo['key'] . " " . $hymnInfo['keyscale']);
  $keyMeterRun->getFont()->setBold(FALSE)->setSize(12)->setColor(new Color('FFFFFF'));
  $keyMeterText->createBreak();
  $keyMeterRun = $keyMeterText->createTextRun("Starting Note: " . $hymnInfo['startnote']);
  $keyMeterRun->getFont()->setBold(FALSE)->setSize(12)->setColor(new Color('FFFFFF'));
  $keyMeterText->createBreak();
  $keyMeterRun = $keyMeterText->createTextRun("Time Signature: " . $hymnInfo['timesignature']);
  $keyMeterRun->getFont()->setBold(FALSE)->setSize(12)->setColor(new Color('FFFFFF'));
  $keyMeterText->createBreak();
  $keyMeterRun = $keyMeterText->createTextRun("Meter: " . $hymnInfo['meter']);
  $keyMeterRun->getFont()->setBold(FALSE)->setSize(12)->setColor(new Color('FFFFFF'));
}

function addSlide(&$pres, $image) {
  $thisSlide = $pres->createSlide();
  $thisTrans = new Transition();
  $thisTrans->setManualTrigger(true);
  $thisTrans->setTimeTrigger(false);
  $thisTrans->setTransitionType(Transition::TRANSITION_PUSH_RIGHT);
  $thisTrans->setSpeed(Transition::SPEED_FAST);
  $thisSlide->setTransition($thisTrans);
  $thisShape = new Drawing\Gd();
  $thisShape->setImageResource($image)
    ->setRenderingFunction(Drawing\Gd::RENDERING_PNG)
    ->setMimeType(Drawing\Gd::MIMETYPE_DEFAULT)
    ->setHeight($pres->getLayout()->getCY(DocumentLayout::UNIT_PIXEL)-20)
    ->setOffsetX(10)
    ->setOffsetY(10);
  if ($thisShape->getWidth() > $pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL))
    $thisShape->setWidth($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)-100);
  else if ($thisShape->getWidth() > $pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)-20)
    $thisShape->setWidth($pres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)-20);
  $thisSlide->addShape($thisShape);
}

function isVerse($element) {
  if($element['type'] == "verse")
    return true;
  return false;
}

function isChorus($element) {
  if($element['type'] == "chorus")
    return true;
  return false;
}

require 'vendor/autoload.php';
$phssApp = new \Slim\App;

$phssApp->get('/hymn/number/{number}', function(Request $request, Response $response) {
  $dbHymn = dbConnect();
  $HYMN = "SELECT * FROM v_hymns_author_composer WHERE number=" . $request->getAttribute('number') . ";";
  $HYMN = $dbHymn->prepare($HYMN);
  $R = $HYMN->execute();
  $HYMN = (array)$HYMN->fetchAll(PDO::FETCH_CLASS)[0];
  $ELEMENTS = "SELECT type,id,element FROM hymn_elements WHERE hymn=" . $request->getAttribute('number') . " ORDER BY elseq;";
  $ELEMENTS = $dbHymn->prepare($ELEMENTS);
  $R = $ELEMENTS->execute();
  $ELEMENTS = (array)$ELEMENTS->fetchAll(PDO::FETCH_CLASS);
  $HYMN['elements'] = $ELEMENTS;
  echo json_encode($HYMN);
});

$phssApp->get('/hymn/search/title/{word}', function(Request $request, Response $response) {
  $dbHymn = dbConnect();
  $WORD = str_replace(' ','%',$request->getAttribute('word'));
  $Q = "SELECT * FROM search_title('" . $WORD . "');";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute();
  $Q = (array)$Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode($Q);
});

$phssApp->get('/hymn/search/{word}', function(Request $request, Response $response) {
  $dbHymn = dbConnect();
  $WORD = str_replace(' ','%',$request->getAttribute('word'));
  $Q = "SELECT * FROM search_hymns('" . $WORD . "');";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute();
  $Q = (array)$Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode($Q);
});

$phssApp->get('/hymn/all', function(Request $request, Response $response) {
  $dbHymn = dbConnect();
  $Q = "SELECT * FROM v_hymns_author_composer ORDER BY title;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute();
  $Q = (array)$Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode($Q);
});

$phssApp->get('/hymn/elements/{n}', function($req, $resp, $args) {
  $dbHymn = dbConnect();
  $Q = "SELECT hymn, elseq, type, id, array_to_string(element,'/') AS element FROM hymn_elements WHERE hymn= :hymn ORDER BY elseq;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute(array($args['n']));
  $Q = (array)$Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode($Q);
});

$phssApp->get('/test/hymn/elements/{n}', function($req, $resp, $args) {
  $dbHymn = dbConnect();
  $Q = "SELECT hymn, elseq, type, id, element FROM hymn_elements WHERE hymn= :hymn ORDER BY elseq;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute(array($args['n']));
  $Q = (array)$Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode($Q);
});

$phssApp->get('/hymn/element/{n}/{e}', function($req, $resp, $args) {
  $dbHymn = dbConnect();
  $Q = "SELECT * FROM hymn_elements WHERE hymn= :hymn AND elseq= :elseq;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute(array($args['n'],$args['e']));
  $Q = (array)$Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode($Q);
});

$phssApp->get('/hymn/images/{n}[/{e}[/{i}]]', function($req, $resp, $args) {
  $dbHymn = dbConnect();
  $qParams = array();
  $qParams[] = $args['n'];
  $Q = "SELECT hymn,elseq,type,id,imgseq FROM hymn_elements_img WHERE hymn= :hymn";
  if($args['e'] && $args['e'] != "") {
    $Q .= " AND type= :type";
    $qParams[] = $args['e'];
  }
  if($args['i'] && $args['i'] != "") {
    $Q .= " AND id= :id";
    $qParams[] = $args['i'];
  }
  $Q .= " ORDER BY elseq,imgseq;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute($qParams);
  echo json_encode((array)$Q->fetchAll(PDO::FETCH_CLASS));
});

$phssApp->get('/hymn/image/{n}/{e}/{i}/{s}', function($request, $response, $args) {
  /*
  $dbHymn = dbConnect();
  $Q = "SELECT img FROM hymn_elements_img WHERE hymn= :hymn AND type= :type AND id= :id AND imgseq= :imgseq;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute(array($args['n'],$args['e'],$args['i'],$args['s']));
  $imgData="";
  $Q->bindColumn('img', $imgData, \PDO::PARAM_STR);
  $Q->fetch(\PDO::FETCH_BOUND);
  $response->write(pack('H*',$imgData));
  */
  readfile("trimmed/" . $args['n'] . "/" . $args['e'] . "_" . $args['i'] . "_" . $args['s'] . ".png");
  return $response->withHeader('Content-Type', 'image/png');
});

$phssApp->get('/hymn/img/{n}/{e}/{i}/{s}', function($request, $response, $args) {
  readfile("trimmed/" . $args['n'] . "/" . $args['e'] . "_" . $args['i'] . "_" . $args['s'] . ".png");
  return $response->withHeader('Content-Type', 'image/png');
});

$phssApp->get('/hymn/presentation/{number}[/{layout}]', function($request, $response, $args) {
  $phssLayout = new DocumentLayout();
  if($args['layout'] == "widescreen") {
    $phssLayout->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9, true);
    $myHeight = 625;
    $myOffsetY = 0;
    $myOffsetX = 0;
  } else {
    $phssLayout->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_4X3, true);
    $myHeight = 710;
    $myOffsetY = 0;
    $myOffsetX = 0;
  }
  // $writers = array('PowerPoint2007' => 'pptx', 'ODPresentation' => 'odp');
  $dbHymn = dbConnect();
  $phssPres = new PhpPresentation();
  $phssPres->setLayout($phssLayout);
  $phssPres->removeSlideByIndex(0);
  $Q = "SELECT type,id,imgseq FROM hymn_elements_img WHERE hymn= :hymn ORDER BY elseq,imgseq;";
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute(array($args['number']));
  while($row = $Q->fetch(PDO::FETCH_ASSOC)) {
    $thisSlide = $phssPres->createSlide();
    // $thisImg = imagecreatefromstring(pack('H*',fgets($row['img'])));
    $imgPath = "trimmed/" . $args['number'] . "/" . $row['type'] . "_" . $row['id'] . "_" . $row['imgseq'] . ".png";
    $thisImg = imagecreatefrompng($imgPath);
    $thisShape = new Drawing\Gd();
    $thisShape->setName($args['number'] . " " . $row['type'] . " " . $row['id'] . " " . $row['imgseq'])
              ->setDescription('Sample')
              ->setImageResource($thisImg)
              ->setRenderingFunction(Drawing\Gd::RENDERING_PNG)
              ->setMimeType(Drawing\Gd::MIMETYPE_DEFAULT)
              ->setHeight($myHeight)
              ->setOffsetX($myOffsetX)
              ->setOffsetY($myOffsetY);
    $thisSlide->addShape($thisShape);
    $thisImg = null;
    $thisShape = null;
    $thisTrans = new Transition();
    $thisTrans->setManualTrigger(true);
    $thisTrans->setTimeTrigger(false);
    $thisTrans->setTransitionType(Transition::TRANSITION_PUSH_RIGHT);
    $thisTrans->setSpeed(SPEED_FAST);
    $thisSlide->setTransition($thisTrans);
  }
  $xmlWriter = IOFactory::createWriter($phssPres, "PowerPoint2007");
  $xmlWriter->save(__DIR__ . "/output/{$args['number']}.pptx");
  readfile(__DIR__ . "/output/{$args['number']}.pptx");
  return $response->withHeader('Content-Description','File Transfer')->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation')->withHeader('Content-Disposition','attachment; filename=' . $args['number'] . '.pptx')->withHeader('Content-Transfer-Encoding','binary')->withHeader('Expires',0)->withHeader('Cache-Control','must-revalidate')->withHeader('Pragma','public')->withHeader('Content-Length',filesize(__DIR__ . "/output/" . $args['number'] . ".pptx"));
});

$phssApp->get('/hymn/presentation/{number}/{layout}/elements/{elements:.*}', function($req, $resp, $args) {
  $dbHymn = dbConnect();
  $elements = explode('/', $req->getAttribute('elements'));
  $phssPres = createPres($args['layout']);
  foreach($elements as $element) {
    $Q = "SELECT * FROM hymn_elements_img WHERE hymn= :hymn AND elseq= :elseq ORDER BY imgseq;";
    $Q = $dbHymn->prepare($Q);
    $R = $Q->execute(array($args['number'],$element));
    while($imgrow = $Q->fetch(PDO::FETCH_ASSOC)) {
      $thisImg = null;
      // $thisImg = imagecreatefromstring(pack('H*',fgets($imgrow['img'])));
      $imgPath = "trimmed/" . $args['number'] . "/" . $imgrow['type'] . "_" . $imgrow['id'] . "_" . $imgrow['imgseq'] . ".png";
      $thisImg = imagecreatefrompng($imgPath);
      addSlide($phssPres, $thisImg);
    }
  }
  $xmlWriter = IOFactory::createWriter($phssPres, "PowerPoint2007");
  $xmlWriter->save(__DIR__ . "/output/{$args['number']}.pptx");
  readfile(__DIR__ . "/output/{$args['number']}.pptx");
  return $resp->withHeader('Content-Description','File Transfer')
              ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
              ->withHeader('Content-Disposition','attachment; filename=phss_hymn_' . $args['number'] . '.pptx')
              ->withHeader('Content-Transfer-Encoding','binary')
              ->withHeader('Expires',0)
              ->withHeader('Cache-Control','must-revalidate')
              ->withHeader('Pragma','public')->withHeader('Content-Length',filesize(__DIR__ . "/output/" . $args['number'] . ".pptx"));
});

$phssApp->get('/hymns/presentation/{layout}/{title}/{song_elements:.*}', function($req, $resp, $args) {
  $presTitle = $req->getAttribute('title');
  $presTimestamp = date("YmdHis");
  if($presTitle == NULL || $presTitle == "")
    $presTitle = $presTimestamp;
  $dbHymn = dbConnect();
  $songParam = $req->getAttribute('song_elements');
  if(substr($songParam,-1) == '/')
    $songParam = substr($songParam, 0, -1);
  $hymns = explode('/', $songParam);
  $myPres = createPres($args['layout']);
  foreach($hymns as $hymn) {
    $arrayStuff = explode(".", $hymn);
    $thisHymn = $arrayStuff[0];
    $elements = explode(',', $arrayStuff[1]);
    $Q = "SELECT * FROM v_hymns_author_composer WHERE number= :hymn";
    $Q = $dbHymn->prepare($Q);
    $R = $Q->execute(array($thisHymn));
    $R = $Q->fetch(PDO::FETCH_ASSOC);
    addTitleSlide($myPres, $R);
    foreach($elements as $element) {
      $Q = "SELECT * FROM hymn_elements_img WHERE hymn= :hymn AND elseq= :elseq ORDER BY imgseq;";
      $Q = $dbHymn->prepare($Q);
      $R = $Q->execute(array($thisHymn,$element));
      while($imgrow = $Q->fetch(PDO::FETCH_ASSOC)) {
        $thisImg = null;
        // $thisImg = imagecreatefromstring(pack('H*',fgets($imgrow['img'])));
        $imgPath = "trimmed/" . $thisHymn . "/" . $imgrow['type'] . "_" . $imgrow['id'] . "_" . $imgrow['imgseq'] . ".png";
        $thisImg = imagecreatefrompng($imgPath);
        addSlide($myPres, $thisImg);
      }
    }
    $lastSlide = $myPres->setActiveSlideIndex($myPres->getSlideCount() - 1);
    $endText = $lastSlide->createRichTextShape()
              ->setHeight(25)
              ->setWidth(90)
              ->setOffsetX($myPres->getLayout()->getCX(DocumentLayout::UNIT_PIXEL)-90-10)
              ->setOffsetY($myPres->getLayout()->getCY(DocumentLayout::UNIT_PIXEL)-30-20);
    $endText->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->setStartColor(new Color( 'FF000000' ))
              ->setEndColor(new Color( 'FF000000' ));
    $endText->getShadow()->setVisible(true)->setAlpha(75)->setBlurRadius(2)->setDirection(45);
    $endText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $endText->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $textRun = $endText->createTextRun("END");
    $textRun->getFont()->setBold(TRUE)->setSize(28)->setColor(new Color('FFFFFF'));
  }
  $xmlWriter = IOFactory::createWriter($myPres, "PowerPoint2007");
  $xmlWriter->save(__DIR__ . "/output/" . $presTimestamp . ".pptx");
  readfile(__DIR__ . "/output/" . $presTimestamp . ".pptx");
  return $resp->withHeader('Content-Description','File Transfer')
              ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
              ->withHeader('Content-Disposition','attachment; filename=' . $presTitle . '.pptx')
              ->withHeader('Content-Transfer-Encoding','binary')
              ->withHeader('Expires',0)
              ->withHeader('Cache-Control','must-revalidate')
              ->withHeader('Pragma','public')->withHeader('Content-Length',filesize(__DIR__ . "/output/" . $presTimestamp . ".pptx"));
});

$phssApp->get('/hymns/all/{sort}', function($req, $resp, $args) {
  $Q = "SELECT * FROM v_hymns_author_composer ORDER BY " . $req->getAttribute("sort") . ";";
  $dbHymn = dbConnect();
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute();
  $HYMNS = $Q->fetchAll(PDO::FETCH_CLASS);
  foreach($HYMNS as $hymn) {
    $Q = "SELECT type, (upper(left(type,1)) || CAST(id as text)) as id, array_to_string(element,'/') AS element FROM hymn_elements WHERE hymn= :hymn ORDER BY elseq;";
    $Q = $dbHymn->prepare($Q);
    $R = $Q->execute(array($hymn->number));
    $Q = $Q->fetchAll(PDO::FETCH_CLASS);
    $hymn->elements = $Q;
  }
  echo json_encode((array)$HYMNS); 
  return $resp->withHeader('Content-Type', 'application/json');
});

$phssApp->get('/hymns/topic/{id}', function($req, $resp, $args) {
  $Q = "SELECT * FROM v_hymns_author_composer INNER JOIN hymn_topics ON hymn_topics.hymn = v_hymns_author_composer.number WHERE hymn_topics.topic= :topic ORDER BY title;";
  $dbHymn = dbConnect();
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute(array($req->getAttribute("id")));
  $HYMNS = $Q->fetchAll(PDO::FETCH_CLASS);
  foreach($HYMNS as $hymn) {
    $Q = "SELECT type, (upper(left(type,1)) || CAST(id as text)) as id, array_to_string(element,'/') AS element FROM hymn_elements WHERE hymn= :hymn ORDER BY elseq;";
    $Q = $dbHymn->prepare($Q);
    $R = $Q->execute(array($hymn->number));
    $Q = $Q->fetchAll(PDO::FETCH_CLASS);
    $hymn->elements = $Q;
  }
  echo json_encode((array)$HYMNS);
  return $resp->withHeader('Content-Type', 'application/json');
});

$phssApp->get('/topics/all', function($req, $resp, $args) {
  $Q = "SELECT * FROM topics ORDER BY topic;";
  $dbHymn = dbConnect();
  $Q = $dbHymn->prepare($Q);
  $R = $Q->execute();
  $TOPICS = $Q->fetchAll(PDO::FETCH_CLASS);
  echo json_encode((array)$TOPICS);
  return $resp->withHeader('Content-Type', 'application/json');
});

$phssApp->run();

?>
