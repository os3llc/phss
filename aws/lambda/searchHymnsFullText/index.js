console.log('Executing getHymnByNumber method.');
var pg = require('pg');

exports.handler = function(event, context, callback) {

    if (event.a === undefined)
        callback('400 Invalid Input');

    var conn = 'pg://user:pass@endpoint.aws.example:5432/dbname';
    var client = new pg.Client(conn);
    client.connect();

    var query = client.query('SELECT * FROM v_hymns_author_composer WHERE number=' + event.a);
    query.on('row', function(row, result) {
        result.addRow(row);
    });
    query.on('end', function(result) {
        console.log(JSON.stringify(result.rows));
        var jsonObj = JSON.parse(JSON.stringify(result.rows));
        client.end();
        context.succeed(jsonObj);
    });
};
