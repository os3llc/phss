<?php

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psalms, Hymns, and Spiritual Songs</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/phss.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <script src="js/vendor/jquery.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
  </head>
  <body text="#34282C">
    <div>
        <img src="img/tree_of_life.png" style="float:left;width:75px;border:5px solid white" />
        <h1 style="color:#34282C">Psalms, Hymns & Spiritual Songs</h1>
    <div>
    <ul class="accordion" data-accordion role="tablist" data-allow-all-closed="true">
      <li id="songList" class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title">Presentation Builder</a>
        <div class="accordion-content" data-tab-content>
          <div class="row">
            <div class="large-2 columns">
              <label for="ls_query" class="right inline">Search:</label>
            </div>
            <div class="large-8 columns">
              <input type="search" class='songSearch' id="ls_query" placeholder="Type to start searching ...">
            </div>
            <div class="large-2 columns">
              <select name="searchType" id="searchType" size=1>
                <option value="title" selected>Title</option>
                <option value="all">Entire Song</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="large-2 columns">&nbsp;</div>
            <div class="large-8 columns">
              <div id="ls_results"></div>
            </div>
            <div class="large-2 columns">&nbsp;</div>
          </div>
          <div class="row">
            <div class="large-12 columns">
            <div class="row collapse prefix-radius postfix-radius">
            <div class="large-2 columns">
              <label for="addNumber" class="right prefix">Hymn Number:</label>
            </div>
            <div class="large-2 columns">
              <input type="text" id="addNumber" placeHolder="123" />
            </div>
            <div class="large-1 columns">
              <a href="#" id="doAddNumber" class="button postfix">Add</a>
            </div>
            <div class="large-7 columns">&nbsp;</div>
            </div>
            </div>
          </div>
          <div id="pres_title" class="row">
            <div class="large-2 columns">
              <h5>Presentation Title:</h5>
            </div>
            <div class="large-8 columns">
              <input type="text" name="presTitle" id="presTitle" placeholder="Give Your Presentation a Title" maxlength=32 />
            </div>
            <div class="large-2 columns">
              <select name="presFormat" id="presFormat" size=1>
                <option value="normal" selected>Standard (4:3)</input>
                <option value="widescreen">Widescreen (16:9)</input>
                <option value="widescreen10">Widesceen (16:10)</input>
              </select>
            </div>
          </div>
          <div id="song_list" class="sortable row small-up-1 medium-up-2 large-up-4">
            
          </div>
          <div id="song_buttons" class="row large-up-1">
            <button type="button" id="getPresentation" class="primary button">Get Presentation</button>
          </div>
        </div>
      </li>
      <li id="songListTab" class="accordion-item" data-accordion-item>
        <a id="openSongListTab" href="#" class="accordion-title">List of Songs</a>
        <div class="accordion-content" data-tab-content>
           <table id="songListTable" class="display">
             <thead>
               <tr>
                 <th>Hymn Number</th>
                 <th>Title</th>
                 <th>Author<br/>(Lyrics)</th>
                 <th>Composer<br/>(Tune)</th>
                 <th>Elements</th>
                 <th>iTunes</th>
                 <th>Google</th>
                 <th>Amazon</th>
               </tr>
             </thead>
             <tbody id="songListTableBody">

             </tbody>
           </table>
        </div>
      </li>
      <li id="songsByTopic" class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Songs by Topic</a>
        <div class="accordion-content" data-tab-content>
          <form>
            <table id="songTopicTable" class="display">
              <thead>
                <tr>
                  <select name="topicSelector" id="topicSelector" size=1>
                  
                  </select>
                </tr>
                <tr>
                  <th>Hymn Number</th>
                  <th>Title</th>
                  <th>Author<br/>(Lyrics)</th>
                  <th>Composer<br/>(Tune)</th>
                  <th>Elements</th>
                  <th>iTunes</th>
                  <th>Google</th>
                  <th>Amazon</th>
                </tr>
              </thead>
              <tbody id="songTopicTableBody">

              </tbody>
            </table>
          </form>
        </div>
      </li>
    </ul>
    <script src="js/jquery-ui/jquery-ui.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
    <script>
      function addslashes( str ) {
        return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
      }

      $( function() {
        $( "#song_list" ).sortable();
        $( "#song_list" ).disableSelection();
      } );

      $("#ls_query").on("keyup", function() {
        var searchValue = $(this).val();
        var searchBox = $(this);
        var searchResults = $("#ls_results");
        var searchType = $("#searchType").val();
        var searchUrl = "/phss/hymn/search/";
        if(searchValue.length < 4) {
          searchResults.empty();
          searchResults.css("border","0px");
          searchResults.hide();
          return true;
        }
        if(searchType == "title")
          searchUrl += "title/";
        $.getJSON(searchUrl + searchValue, function(data) {
          searchResults.empty();
          for(i = 0; i < data.length; i++) {
            var myResult = '<a href="#" onClick="addSong(' + data[i]['hymn'] + ',\'' + addslashes(data[i]['title']) + '\');">';
            myResult += data[i]['title'] + " (" + data[i]['hymn'] + ")";
            if(data[i]['line'] != "")
              myResult += ": " + data[i]['line'];
            searchResults.append(myResult + "</a><br/>");
          }
        });
        searchResults.css("border","1px solid #000000");
        searchResults.show();
      });

      function getTitle(hymnNum) {
        var hymnTitle;
        $.ajax({
          url: "/phss/hymn/number/" + hymnNum,
          dataType: 'json',
          async: false,
          data: null,
          success: function(data) {
            hymnTitle = data['title'];
          }
        });
        return hymnTitle;
      }

      function addSong(hymnNum,hymnTitle) {
        var songList = $("#song_list");
        var myCode = "<div class='sortable column hymn' draggable=true id=" + hymnNum + ">";
        myCode += hymnTitle;
        myCode += ' <a href="#" onClick="removeElement(this);">X</a>';
        myCode += "<ul id=\"element_" + hymnNum + "\">";
        myCode += "<li class=hymn_element draggable=false id=" + hymnNum + "_t hymn=" + hymnNum + ">";
        myCode += '<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" tabindex="1" title="Title Slide">Title</span>';
        myCode += "<input type=checkbox id=" + hymnNum + "_t checked /></li>";
        $.ajax({
          url: "/phss/hymn/elements/" + hymnNum,
          dataType: 'json',
          async: false,
          data: null,
          success: function(data) {
            var hasChorus = 0;
            var chorusEl = null;
            for(i = 0; i < data.length; i++) {
              if(data[i]['type'] == "chorus" || data[i]['type'] == "refrain") {
                hasChorus = 1;
                chorusEl = data[i];
                break;
              }
            }
            if(hasChorus && chorusEl) {
              var newData = data;
              for(i = 1; i < newData.length; i++) {
                if(newData[i]['type'] == "chorus" || newData[i]['type'] == "refrain" || newData[i]['type'] == "counterparts")
                  continue;
                newData.splice(i,0,chorusEl);
                i++;
              }
              data = newData;
            }
            for(i = 0; i < data.length; i++) {
              myCode += "<li class=hymn_element draggable=true id=" + data[i]['hymn'] + "_" + data[i]['elseq'];
              myCode += " hymn=" + data[i]['hymn'] + " elseq=" + data[i]['elseq'] + ">";
              myCode += '<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" tabindex="1"';
              myCode += ' title="' + data[i]['element'].replace(/\//g,'\n').replace(/[\\"]/g, '&quot;') + '">';
              myCode += data[i]['type'].charAt(0) + data[i]['id'] + "</span>";
              myCode += '<input type=checkbox id=' + data[i]['hymn'] + '_' + data[i]['elseq'] + ' checked />';
              myCode += "</li>";
            }
          }
        });
        myCode += "</ul></div>";
        songList.append(myCode);
        $( "#element_" + hymnNum ).sortable();
        $( "#element_" + hymnNum ).disableSelection();
        $("#ls_query").val('');
        $("#ls_results").hide();
        $("#ls_results").empty();
      }

      function removeElement(link) {
        myLi = $(link).parent();
        if (myLi.attr('class').includes("hymn_element")) {
          myUl = myLi.parent("ul");
          myHymn = myUl.closest(".hymn");
          if(myUl.children().length <= 1) {
            myHymn.remove();
            return true;
          }
        }
        myLi.remove();
      }

      $("#doAddNumber").on("click", function(e) {
        console.log("Clicked.");
        var qNum = $("#addNumber").val();
        if(qNum == null)
          return false;
        if(isNaN(qNum))
          return false;
        var qTitle = getTitle(qNum);
        addSong(qNum,qTitle);
      });

      $("#getPresentation").on("click", function(e) {
        format = $("#presFormat").val();
        presTitle = $("#presTitle").val().replace(/ /g,"_");
        if (!presTitle) {
          alert("Please give your presentation a title.");
          $("#presTitle").focus();
          return false;
        }
        baseUrl = "/phss/hymns/presentation/" + format + "/" + presTitle;
        hymns = $(".hymn");
        numHymns = hymns.length;
        if (numHymns < 1) {
          alert("You need to select some songs!");
          return false;
        }
        numElements = 0;
        hymns.each(function() {
          hymn = $(this).attr('id');
          baseUrl += "/" + hymn + ".";
          hymnElements = $(this).find("input:checkbox:checked");
          numElements = hymnElements.length;
          hymnElements.each(function(index) {
            elId = $(this).attr('id').replace(hymn + "_",'');
            baseUrl += elId;
            if (index < numElements - 1)
              baseUrl += ",";
          });
          
        });
        if (numElements < 1) {
          alert("You can't have a song without any elements.");
          return false;
        }
        e.preventDefault();
        window.location.href = baseUrl;
      });

      $("#songListTab > a").on("click", function() {
        if($(this).parent().hasClass("is-active")) {
          $("#songListTableBody").empty();
          $.getJSON("/phss/hymns/all/title", function(data) {
            var tableHtml = '';
            $.each(data, function(i, item) {
              tableHtml += '<tr><td>';
              tableHtml += item.number;
              tableHtml += '</td><td>';
              tableHtml += item.title;
              tableHtml += '</td><td>';
              tableHtml += item.author;
              tableHtml += '</td><td>';
              tableHtml += item.composer;
              tableHtml += '</td><td>';
              $.each(item.elements, function(j, element) {
                tableHtml += '<span data-tooltip aria-haspopup="true" class="has-tip element-tip" ';
                tableHtml += 'data-disable-hover="false" tabindex="1"';
                tableHtml += ' title="' + element.element.replace(/\//g,'\n').replace(/[\\"]/g, '&quot;') + '">';
                tableHtml += element.id + "</span>&nbsp;";
              });
              tableHtml += '</td><td>';
              tableHtml += '&nbsp;';
              tableHtml += '</td><td>';
              tableHtml += '&nbsp;';
              tableHtml += '</td><td>';
              tableHtml += '&nbsp;';
              tableHtml += '</td></tr>';
            });
            $("#songListTableBody").append(tableHtml);
            $("#songListTable").DataTable({"order": [[1, "asc" ]], "paging": false, "info": false });
          });
        }
      });

      $("#songsByTopic > a").on("click", function() {
        if($(this).parent().hasClass("is-active")) {
          $("#songTopicTableBody").empty();
          $("#topicSelector").empty();
          $("#topicSelector").append($('<option>', {
            value: -1,
            text: '--Select a Topic--'
          }));
          $.getJSON("/phss/topics/all", function(data) {
            $.each(data, function(i, item) {
              $("#topicSelector").append($('<option>', {
                value: item.id,
                text: item.topic
              }));
            });
          });
        }
      });

      $("#topicSelector").on('change', function() {
        $("#songTopicTableBody").empty();
        if($(this).val() < 1)
          return;
        $.getJSON("/phss/hymns/topic/" + $(this).val(), function(data) {
          var tableHtml = '';
          $.each(data, function(i, item) {
            tableHtml += '<tr><td>';
            tableHtml += item.hymn;
            tableHtml += '</td><td>';
            tableHtml += item.title;
            tableHtml += '</td><td>';
            tableHtml += item.author;
            tableHtml += '</td><td>';
            tableHtml += item.composer;
            tableHtml += '</td><td>';
            $.each(item.elements, function(j, element) {
              tableHtml += '<span data-tooltip aria-haspopup="true" class="has-tip element-tip" ';
              tableHtml += 'data-disable-hover="false" tabindex="1"';
              tableHtml += ' title="' + element.element.replace(/\//g,'\n').replace(/[\\"]/g, '&quot;') + '">';
              tableHtml += element.id + "</span>&nbsp;";
            });
            tableHtml += '</td><td>';
            tableHtml += '&nbsp;';
            tableHtml += '</td><td>';
            tableHtml += '&nbsp;';
            tableHtml += '</td><td>';
            tableHtml += '&nbsp;';
            tableHtml += '</td></tr>';
          });
          $("#songTopicTableBody").append(tableHtml);
          $("#songTopicTable").DataTable({"order": [[1, "asc" ]], "paging": false, "info": false });
        });
      });

    </script>
  </body>
</html>
