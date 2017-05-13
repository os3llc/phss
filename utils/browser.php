<?php

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
    <link rel="stylesheet" href="../css/foundation.css">
    <link rel="stylesheet" href="../css/app.css">
  </head>
  <body>
    <div id="hymnTable">
    <table class="hover">
      <thead>
        <tr>
          <th>Number</th>
          <th>Title</th>
          <th>Author</th>
          <th>Composer</th>
        </tr>
      </thead>
      <tbody id="show-data">

      </tbody>
    </table>
    <a href="#" id="get-data" class="button">Get Hymns</a>
    </div>
    <div class="reveal" id="songReveal" data-reveal>
      <ul>
        <li id="sBookNum">Number in Book:</li>
        <li id="sTitle">Title:</li>
        <li id="sMeter">Meter:</li>
        <li id="sAuthor">Author:</li>
        <li id="sComposer">Composer:</li>
        <li id="sCopyright">Copyright:</li>
        <li id="sKey">Key/Scale:</li>
        <li id="sTune">Tune: </li>
        <li id="sSummary">Summary: </li>
        <li id="sStartNote">Starting Note: </li>
        <li id="sTimeSig">Time Signature: </li>
        <li>
          <div id="sLyrics">

          </div>
        </li>
      </ul>
    </div>
    <script src="../js/vendor/jquery.js"></script>
    <script src="../js/vendor/what-input.js"></script>
    <script src="../js/vendor/foundation.js"></script>
    <script src="../js/app.js"></script>
    <script src="../js/phss.js"></script>
    <script>
      $(document).ready(function () {
        $('#get-data').click(function () {
          var showData = $('#show-data');

          $.getJSON('/phss/hymn/all', function (data) {
            console.log(data);

            var items = data;
            var table = '';
            for(i = 0; i < items.length; i++) {
              var content = '<a href="#' + items[i]['number'] + '" id="showSong">';
              content = '<tr><td>' + items[i]['number'];
              content += '</td><td>' + items[i]['title'];
              content += '</td><td>' + items[i]['author'];
              content += '</td><td>' + items[i]['composer'];
              content + '</td></tr></a>';
              table += content;
            }

            showData.empty();
            showData.append(table);
          });

          showData.append('<tr><td colspan=4>Loading the JSON file.</td></tr>');
        });
      });
    </script>
  </body>
</html>
