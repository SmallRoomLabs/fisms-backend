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
  <link rel="stylesheet" href="css/fisms.css">
  <link rel="icon" type="image/png" href="images/favicon.png">

  <!-- All requests to /socket.io are being handed off to a node.js-backend by nginx -->
  <script src="/socket.io/socket.io.js"></script>
  <script src="/js/fisms.js"></script>
  <!-- read config, setup error handler -->
  <?php require 'funcs.php' ?>

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
