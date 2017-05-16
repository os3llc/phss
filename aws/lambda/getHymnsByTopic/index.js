console.log('Executing getHymnByNumber method.');
var pg = require('pg');

exports.handler = function(event, context, callback) {

    if (event.topic === undefined)
        callback('400 Invalid Input');

    var q = 'SELECT * FROM v_hymns_author_composer ';
    q += 'INNER JOIN hymn_topics ON hymn_topics.hymn = v_hymns_author_composer.number ';
    q += 'WHERE hymn_topics.topic=' + event.topic + ' ORDER BY title;';

    var conn = 'pg://user:pass@blah.rds.amazonaws.com:5432/dbname';
    var client = new pg.Client(conn);
    client.connect();

    var query = client.query(q);
    var hymns = [];
    query.on('row', function(row, result) {
        result.addRow(row);
    });
    query.on('end', function(result) {
        hymns = result.rows;
        console.log(hymns);
    });

    console.log(hymns);

    for(i = 0; i < hymns.length; i++) {
        q = 'SELECT type, (upper(left(type,1)) || CAST(id as text)) as id, array_to_string(element,'/') AS element ';
        q += 'FROM hymn_elements WHERE hymn=' + hymn[i]['number'] + ' ORDER BY elseq;';
        query = client.query(q);
        query.on('row', function(row, result) {
          result.addRow(row);
        });
        query.on('end', function(result) {
            hymns[i]['elements'] = result.rows;
        });
    };
    client.on('drain', function() {
        console.log(JSON.stringify(hymns));
        var jsonObj = JSON.parse(JSON.stringify(hymns));
        client.end();
        context.succeed(jsonObj);
    });
};
