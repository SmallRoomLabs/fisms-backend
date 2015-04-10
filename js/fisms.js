  var cnt=0;
  var socket;

  window.onload=function() {
    // Make sure all entries in the left column is shown
    updateTheNotFirstClasses();
    clearPrimaryAndSecondary(); // TODO remove after UI is finalized
    insertSystem('primarycol', '...', 'all-messages ...');
    socket = io.connect();
    socket.on('system', handleSystemMessage); 	// Adds a new system-box to the UI
    socket.on('sms', handleSmsMessage);		// Adds a new SMS into a system-box
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
  // When this message is received from the node.js over the socket a new box
  // should be displayed in the UI.
  //
  function handleSystemMessage(data) {
    insertSystem('secondarycol', data.country, data.phone)
  }


  //
  // When this message is received from the node.js over the socket a new SMS text
  // should be displayed inside the designated system-box
  //
  function handleSmsMessage(data) {
      insertMessage(idFromPhone(data.phone), data.dt_sender, data.msg);
  }


  //
  // Convert a phone number string into something that can be used as a DOM id by
  // just removing any non-numeric characters
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
