console.log('Executing getAllTopics method.');
var pg = require('pg');

exports.handler = function(event, context, callback) {

    var q = "SELECT * FROM topics ORDER BY topic";

    var conn = 'pg://user:pass@endpoint.aws.example:5432/dbname';
    var client = new pg.Client(conn);
    client.connect();

    var query = client.query(q);
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
