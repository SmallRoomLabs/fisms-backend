var  server   = require('http').createServer();
var  io       = require('socket.io').listen(server);
var mysql     = require('mysql');

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : ':-)',
  database : 'fisms'
});

connection.connect(function(err){
  if(!err) {
    console.log("Database is connected");  
  } else {
    console.log("Error connecting database");  
  }
});

server.listen(3000);
console.log('Listing on port 3000');

io.sockets.on('connection', function(socket) {
  console.log('Conection on port 3000');
});



var systems=[
  {country:'.se', phone:'+461111111111'},
  {country:'.se', phone:'+462222222222'},
  {country:'.se', phone:'+463333333333'},
  {country:'.se', phone:'+464444444444'},
];


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
    socket.emit('sms', messages[i]);
  }
});
