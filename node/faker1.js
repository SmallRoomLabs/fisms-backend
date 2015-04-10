var mysql     = require('mysql');
var fs        = require('fs');
var ini       = require('ini');
  
var config = ini.parse(fs.readFileSync('../../private/config.ini', 'utf-8'))
var systems=[
];

var quotes = fs.readFileSync('../doc/quotes.txt').toString().split("\n");
console.log('Read '+quotes.length+' quotes from file');
var senders = fs.readFileSync('../doc/senders.txt').toString().split("\n");
console.log('Read '+senders.length+' senders from file');
  
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


setInterval(function() {
  if (systems.length<1) return;
  if (Math.random()>0.1) return;
  var id=Math.floor((Math.random() * systems.length));
  var sender=mysql.escape(senders[Math.floor((Math.random() * 250))]);
  if (Math.random()>0.2) {
    var cc=10+Math.floor(Math.random()*900);
    var num=100000000+Math.floor(Math.random()*900000000);
    sender='+'+cc.toString()+num.toString();
  }
  var msg=mysql.escape(quotes[Math.floor((Math.random() * quotes.length))]);
  var query="INSERT INTO msg (system_id, dt, sender, msg) VALUES("+id+",now(),'"+sender+"',"+msg+");"
  console.log(query);
},100);
