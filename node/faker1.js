var mysql     = require('mysql');
var fs        = require('fs');
var ini       = require('ini');

function randomIntFromInterval(min, max) {
    return Math.floor(Math.random()*(max-min+1)+min);
}
    
  
var config = ini.parse(fs.readFileSync('../../private/config.ini', 'utf-8'))
var systems=[];

// Might change random sentence to using this http://www.htmlgoodies.com/JSBook/sentence.html
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
  if (randomIntFromInterval(1,100)>10) return;
  var id=randomIntFromInterval(1, systems.length);
  var sender=senders[randomIntFromInterval(0,250)];
  if (randomIntFromInterval(1,100)>20) {
    var cc=randomIntFromInterval(10,900);
    var num=randomIntFromInterval(100000000,999999999);
    sender='+'+cc.toString()+num.toString();
  }
  var msg=quotes[randomIntFromInterval(0,quotes.length-1)];
  var query="INSERT INTO msg (system_id, dt, sender, msg) VALUES("+id+",now(),"+mysql.escape(sender)+","+mysql.escape(msg)+");"
  console.log(query);
  connection.query(query, function(err, rows){
    if(err) { 
      console.log("Insert error");    
    } 
  });
},100);
