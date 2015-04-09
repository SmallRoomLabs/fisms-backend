<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>FISMS - Federated Inbound SMS</title>
  <meta name="description" content="">
  <meta name="author" content="mats.engstrom@gmail.com">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="icon" type="image/png" href="images/favicon.png">

<style>

.pageheader {
  height:100px;
  background-color: #ccc;
  padding: 20px;
  margin-bottom: 20px;
  text-align: right;
}


.mybox {
  border: 2px solid #666666;
  background-color: #ccc;
  margin-bottom: 5px;
  -webkit-border-radius: 10px;
  -ms-border-radius: 10px;
  -moz-border-radius: 10px;
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 3px #000;
  -ms-box-shadow: inset 0 0 3px #000;
  box-shadow: inset 0 0 3px #000;
}

.mybox header {
  color: #fff;
  text-shadow: #000 0 1px;
  text-align: center;
  box-shadow: 5px;
  padding: 5px;
  background: -moz-linear-gradient(left center, rgb(0,0,0), rgb(79,79,79), rgb(21,21,21));
  background: -webkit-gradient(
    linear, left top, right top,
    color-stop(0, rgb(0,0,0)),
    color-stop(0.50, rgb(79,79,79)),
    color-stop(1, rgb(21,21,21)));
  background: -webkit-linear-gradient(left center, rgb(0,0,0), rgb(79,79,79), rgb(21,21,21));
  background: -ms-linear-gradient(left center, rgb(0,0,0), rgb(79,79,79), rgb(21,21,21));
  border-bottom: 1px solid #ddd;
  -webkit-border-top-left-radius: 10px;
  -moz-border-radius-topleft: 10px;
  -ms-border-radius-topleft: 10px;
  border-top-left-radius: 10px;
  -webkit-border-top-right-radius: 10px;
  -ms-border-top-right-radius: 10px;
  -moz-border-radius-topright: 10px;
  border-top-right-radius: 10px;
}

.mybox section {
  padding-left:10px;
  font-weight: bold;
}

.mybox article {
  padding-left:10px;
  font-style: italic;
}

.mybox hr {
  margin: 10px 0 10px 0;
  border: 0;
  border-bottom: 1px dashed #ccc;
  background: #999;
}

.expand {
  float: right;
  font-weight: bold;
  cursor:zoom-in;
}

.notfirst {
  display: none;
}

</style>



<script src="/socket.io/socket.io.js"></script>


<script>
  var cnt=0;
  var socket;

  window.onload=function() {
    // Make sure all entries in the left column is shown
    updateTheNotFirstClasses();
    clearPrimaryAndSecondary(); // TODO remove after UI is finalized
    insertSystem('primarycol', '...', 'all-messages ...');
    socket = io.connect();
    socket.on('system', handleSystemMessage);
    socket.on('sms', handleSmsMessage);
  }

  //
  //
  //
  function clearPrimaryAndSecondary() { // TODO remove after UI is finalized
    document.getElementById('primarycol').innerHTML="";
    document.getElementById('secondarycol').innerHTML="";
  }


  //
  //
  //
  function sortDivsInColumn(column) {
    // container is <div id="list">
    var container = document.getElementById("list");
    // all elements below <div id="list">
    var elements = container.childNodes;
    // temporary storage for elements which will be sorted
    var sortMe = [];
    // iterate through all elements in <div id="list">
    for (var i=0; i<elements.length; i++) {
        // skip nodes without an ID, comment blocks for example
        if (!elements[i].id) {
            continue;
        }
        var sortPart = elements[i].id.split("-");
        // only add the element for sorting if it has a dash in it
        if (sortPart.length > 1) {
            /*
             * prepare the ID for faster comparison
             * array will contain:
             *   [0] => number which will be used for sorting 
             *   [1] => element
             * 1 * something is the fastest way I know to convert a string to a
             * number. It should be a number to make it sort in a natural way,
             * so that it will be sorted as 1, 2, 10, 20, and not 1, 10, 2, 20
             */
            sortMe.push([ 1 * sortPart[1] , elements[i] ]);
        }
    }
    // sort the array sortMe, elements with the lowest ID will be first
    sortMe.sort(function(x, y) {
        // remember that the first array element is the number, used for comparison
        return x[0] - y[0];
    });
    // finally append the sorted elements again, the old element will be moved to
    // the new position
    for (var i=0; i<sortMe.length; i++) {
        // remember that the second array element contains the element itself
        container.appendChild(sortMe[i][1]);
    }
  }


  //
  //
  //
  function handleSystemMessage(data) {
    insertSystem('secondarycol', data.country, data.phone)
  }


  //
  //
  //
  function handleSmsMessage(data) {
      insertMessage(idFromPhone(data.phone), data.dt_sender, data.msg);
  }


  //
  //
  //
  function idFromPhone(phone) {
    var id=phone.replace(/\D/g,'');
    return id;
  }


  //
  //
  //
  function buildMessage(dt_sender, msg) {
    var tmp='';
    tmp=tmp+'                   <section>'+dt_sender+'</section>';
    tmp=tmp+'                   <article>'+msg+'</article>';
    return tmp;
  }


  //
  // 
  //
  function buildSystem(country, phone) {

    var tmp='';
    tmp=tmp+'       <div id="'+idFromPhone(phone)+'">';
    tmp=tmp+'          <div class="mybox">';
    tmp=tmp+'            <header>';
    tmp=tmp+'              <span>'+country+' '+phone+'</span>';
    tmp=tmp+'              <span class="expand" onClick="cickExpand(this)">&oplus;</span>';
    tmp=tmp+'            </header>';
    tmp=tmp+'            <div class="first">';
  //tmp=tmp+'              <!-- ~[msgcombo] -->';
    tmp=tmp+'            </div>';
    tmp=tmp+'            <div class="notfirst">';
  //tmp=tmp+'              <!-- <hr> -->';
  //tmp=tmp+'              <!-- ~[msgcombo] -->';
    tmp=tmp+'            </div>';
    tmp=tmp+'          </div>';
    tmp=tmp+'        </div>';
    return tmp;
  }


  //
  //
  //
  function insertSystem(column, country, phone) {
    document.getElementById(column).innerHTML+=buildSystem(country, phone);
    updateTheNotFirstClasses() 
  }



  // Insert a new message at the top in the specified system, scroll the rest of
  // the messages down.
  function insertMessage(systemId, dt_sender, msg) {
    var firstId=document.getElementById(systemId).getElementsByClassName('first')[0];
    var notFirstId=document.getElementById(systemId).getElementsByClassName('notfirst')[0];
    notFirstId.innerHTML='<hr>\n'+firstId.innerHTML+notFirstId.innerHTML;
    firstId.innerHTML=buildMessage(dt_sender, msg);
  }



  //
  // Hides all .notfirst classes except the one that is inside the #primarycol div
  //
  function updateTheNotFirstClasses() {
    var primaries = document.getElementById('primarycol').getElementsByClassName('notfirst');
    var secondaries = document.getElementById('secondarycol').getElementsByClassName('notfirst');
    // Display all .notfirst in the primary column, there should only be one but
    // the loop will also handle zero or many if that ever would happen
    for(var i=0; i<primaries.length; i++) {
        primaries[i].style.display='block';
    }
    // Hide the .notfirst blocks in the secondary column
    for(var i=0; i<secondaries.length; i++) {
        secondaries[i].style.display='none';
    }
  }

  //
  // Move the clicked box from the left over to the right column and expand it
  // so all entries are visible.  The box that originally was in the left column
  // is moved over to the right side and shrunk to show only the 
  //
  function cickExpand(e) {
    // Check if clicking on primary column
    var colId = e.parentNode.parentNode.parentNode.parentNode.getAttribute('id');
    if (colId=='primarycol') {
      alert('Currently ignoring clicks in the left column');
      var container = document.getElementById('secondarycol');
      // all elements below <div id="list">
      var elements = container.childNodes;
      console.log(elements);
      return;
    }

    var srcId = e.parentNode.parentNode.parentNode.getAttribute('id');
    var src = document.getElementById(srcId);
    var dst = document.getElementById('primarycol').getElementsByTagName('div')[0];
    // Swap the inner contents of the div's
    var holdDstInnerHtml=dst.innerHTML;
    dst.innerHTML=src.innerHTML;
    src.innerHTML=holdDstInnerHtml
    // Swap id's of the div's 
    var holdDstId=dst.id;
    dst.id=src.id;
    src.id=holdDstId;
    // We want long list in primary and just the first in the secondary column
    updateTheNotFirstClasses();
  }
</script>



</head>
<body>

  <div class="pageheader">
  <a href="#">Sign up</a> | <a href="#">Login</a>
  </div>

  <div class="container">
    <div class="row">

      <div class="one-half column" id="primarycol">
        <div id="6013294942">
          <div class="mybox">
            <header>
              <span>.my +6013294942</span>
              <span class="expand" onClick="cickExpand(this)">&oplus;</span>
            </header>
            <section>Jan 17 18:35:10 +6019192314</section>
            <article>Apa khabar?</article>
            <div class="notfirst">
              <hr>
              <section>Jan 17 16:44:00 Celcom</section>
              <article>Berhenti - do not enter!</article>
              <hr>
              <section>Jan 17 15:13:22 +6019192314</section>
              <article>Cendol for dessert today maybe? Or do you want something else to eat after dinner? We could go down to the Food steet and have a beer or two instead.</article>
              <hr>
              <section>Jan 16 09:51:35 +0131723111</section>
              <article>Datuk Mats will arrive in 15 minutes</article>
            </div><!--notfirst-->
          </div><!--mybox-->
        </div><!--outer id-->
      </div><!--half column-->

      <div class="one-half column" id="secondarycol">
        <div id="46705485050">
          <div class="mybox">
            <header>
              <span>.uk +44705485050</span>
              <span class="expand" onClick="cickExpand(this)">&oplus;</span>
            </header>
            <section>Jan 17 19:12:10</section>
            <article>Your new pincode is:3333</article>
            <div class="notfirst">
              <hr>
              <section>Jan 17 19:12:07</section>
              <article>Your new pincode is:2222</article>
              <hr>
              <section>Jan 17 19:12:92</section>
              <article>Your new pincode is:1111</article>
            </div><!--notfirst-->
          </div><!--mybox-->
        </div><!--outer id-->
        <div id="46731231232">
          <div class="mybox">
            <header>
              <span>.uk +44731231232</span>
              <span class="expand" onClick="cickExpand(this)">&oplus;</span>
            </header>
            <section>Jan 17 19:10:25</section>
            <article>Jolly good testing</article>
            <div class="notfirst">
            </div><!--notfirst-->
          </div><!--mybox-->
        </div><!--outer id-->
        <div id="6633034722">
          <div class="mybox">
            <header>
              <span>.th +6633034722</span>
              <span class="expand" onClick="cickExpand(this)">&oplus;</span>
            </header>
            <section>Jan 17 18:35:10</section>
            <article>ราคาสมเหตุสมผล และให้ความมั่นใจได้มากกว่า</article>
            <div class="notfirst">
            </div><!--notfirst-->
          </div><!--mybox-->
        </div><!--outer id-->

      </div><!--half column-->

    </div><!--row-->
  </div><!--container-->

</body>
</html>
