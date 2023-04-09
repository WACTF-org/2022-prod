<?php
error_reporting(0);
ini_set("display_errors", 0);

include_once("./flag.php");

class gbucks_code
{
  var $valid_code;
  var $submitted_code;
}

function get_random_char() {
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  return $characters[rand(0, strlen($characters) - 1)];
}

function generate_code() {
  $chars = array();
  for ($i = 0; $i <= 16; $i++) {
    array_push($chars, get_random_char());

    if ($i % 4 == 0 && $i != 0 && $i != 16)
      array_push($chars, "-");
  }
  return join("", $chars);
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>G-BUCKS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.0/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="/app.css" />
</head>

<body>
  <div style="display: none">THE DEBUG PARAMETER, MORTY! IT'LL HELP US FIND THE BUGS.</div>
  <div class="bg"></div>
  <div class="content">
    <center>
      <br>
      <h2>
        <a href="/">
          <p class="title-text">FREE G-BUCKS GENERATOR</p>
        </a>
      </h2>
      <h3>
        <p class="rainbow_text_animated" >Double your G-BUCKS!</p>
      </h3>
      <p>
      <p>
      <p style="color: white">Steps for free G-BUCKS:</p>
      <p style="color: white">1) Goto your local EB Games and ask for a G-BUCKS voucher.</p>
      <p style="color: white">2) Enter your G-BUCKS code below to get a new code worth double!</p>
      <p>
      <form>
        <input type="text" name="generated_code" maxlength="20" placeholder="e.g. <?php echo generate_code() ?>" />
        <p>
        <p>
          <button type="submit" style="color:black">Generate</button>
      </form>
      <br/>
      <p style="color: white">Check your G-BUCKS code!</p>
      <p>
      <form>
        <input type="text" name="input" placeholder="Generate your code and paste it here!"/>
        <p>
        <p>
          <button type="submit" style="color:black">Validate</button>
      </form>
    </center>
    <br>
    <?php
    if (isset($_GET['input'])) {
      $newCode = unserialize(base64_decode($_GET['input']));

      if ($newCode) {
        $newCode->valid_code = generate_code();

        if ($newCode->submitted_code === $newCode->valid_code) {
          echo "<center><strong><p size='25' style=\"color: white\">Enjoy your G-BUCKS! ".$flag."</p></strong><br>";
        } else {
          echo "<center><strong><p style=\"color: red\">Sorry, this code is invalid.<br/>The code to generate a valid code was: " . $newCode->valid_code . " 😔<br/>Maybe buy another voucher and try again?</center><br>";
        }
      } else {
        echo "<center><strong><p style=\"color: red\">Whoops, this code looks invalid.</p></strong></center>";
      }
    }

    if (isset($_GET['generated_code']) && !empty($_GET['generated_code'])) {
      $newCode = new gbucks_code;
      $newCode->submitted_code = $_GET['generated_code'];
      $code = base64_encode(serialize($newCode));

      echo "<center><p style=\"color: white\">Your code has been generated:<br/><br/><strong class=\"code\">" . $code . "</strong></p></center>";
    }

    if (isset($_GET['debug'])) {
      echo "<br><strong><p size='16'><pre style='background: white;background: white;scroll-behavior: auto;overflow: scroll;height: 300px;width: 900px'>" . htmlentities(file_get_contents(__FILE__)) . "</pre></p></strong><br>";
    }
    ?>
  </div>
</body>
</html>