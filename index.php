<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>106!|\\|</title>

    <link rel="stylesheet" type="text/css" href="styles.css" />
  </head>

  <body background="/img/gif/bg205.gif">
    <div class="wiredGang">
      <div class="darker">
        <div class="dark">
          <div class="opacity">
            <img src="img/gif/laingod.gif" alt="wired" />

            <div class="opacityContent">
              <h1 id="intro">DAM 2.0</h1>
              <hr />
              <h2 class="jp">
                <a href="start-pre-midblogN0.html">&lceil;ラリサが大好きですく&rfloor;</a>
              </h2>
            </div>

            <div class="opacityContent2">
            <form name="myForm" method="POST" action="login.php">
              <p>User ID:</p>
              
              <input
                class="text"
                type="text"
                name="username"
                size="25"
                placeholder="michiko"
              />
              <br />
              <p>PassWord:</p>
              
                <input
                  class="text"
                  type="password"
                  size="25"
                  name="password"
                  placeholder="••••••••••••"
                />
                <br />
                <button class="button" type="submit"> Login
                </button>
              </form>

              <?php
              session_start();
              if (isset($_SESSION['error'])) {
                  echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
                  unset($_SESSION['error']);
              }
              ?>

              <p class="descp">
                <b>NOTE:</b> To login you have to have a invite from an existing
                member or direct invite from our god Halfon.
              </p>

              <br />
              <br />

              <a href="lainalone.html"
                >Help & Guidelines</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
