var server    = require('http').createServer();
var io        = require('socket.io').listen(server);
var mysql     = require('mysql');
var fs        = require('fs');
var ini       = require('ini');
  
var config = ini.parse(fs.readFileSync('../../private/config.ini', 'utf-8'))
var systems=[
  {country:'DATABASE', phone:'ERROR'},
];

  
var connection = mysql.createConnection({
  host     : config.db.server,
  user     : config.db.user,
  password : config.db.pw,
  database : config.db.database
});


connection.connect(function(err){
  if(err) {
    console.log("Error connecting database");  
    return;
  }
  console.log('Connected to database');
  connection.query("SELECT country,phone FROM system ORDER by country,phone;", function(err, rows){
    console.log('Sent query for systems to database');
    if(!err) { 
      console.log(rows);
      systems=rows;
    } 
  });
});

server.listen(3000);
console.log('Listing on port 3000');

io.sockets.on('connection', function(socket) {
  console.log('Conection on port 3000');
});




var messages=[
  {phone:'461111111111', dt_sender:'abc', msg:'Hello World'},
  {phone:'461111111111', dt_sender:'def', msg:'apa?'},
  {phone:'461111111111', dt_sender:'ghi', msg:'Foobar'},
  {phone:'461111111111', dt_sender:'jkl', msg:'And Bletch!'},

]

io.on('connection', function (socket) {
  for (var i=0; i<systems.length; i++) {
    socket.emit('system', systems[i]);
  }
  for (var i=0; i<messages.length; i++) {
//    socket.emit('sms', messages[i]);
  }
});

var maxId=1560;

setInterval(function() {
  var query="SELECT msg.id AS id, system.phone AS phone, CONCAT(msg.dt,' [',msg.sender,']') AS dt_sender, msg.msg AS msg FROM msg LEFT JOIN system ON system.id=msg.system_id WHERE msg.id>"+maxId+" ORDER BY msg.id;";
  connection.query(query, function(err, rows){
    if(!err) {
      for (var i=0; i<rows.length; i++) {
        io.sockets.emit('sms', rows[i]);
        if (maxId<rows[i].id) {
          maxId=rows[i].id;
          console.log('maxId is now '+maxId);
        }
        console.log(rows[i]);
      }
    }
  });
},1000);
