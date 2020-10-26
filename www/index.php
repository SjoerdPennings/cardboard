<!DOCTYPE html>
<html>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Trispace:wght@300&display=swap');

    @keyframes blink {
      50% {
        opacity: 0;
      }
    }

    @-webkit-keyframes blink {
      50% {
        opacity: 0;
      }
    }

    body {
      font-family: 'Trispace', sans-serif;
      
    }

    i {
      font-size: 24px;
    }

    span.icons {
      min-height: 30px;
      display: inline-flex;
      align-items: center;
    }

    span.blinkr {
      animation-name: blink;
      animation-duration: 1.2s;
      animation-iteration-count: infinite;
      animation-timing-function: step-start;
    }

    .container {
      height: 100vh; 
      display: flex;
      flex-direction: column;
      align-items: center; 
      justify-content: center;
    }

    .container .content {
      border: 1px solid #939393;
      background:white;
      margin:auto;
      width:800px;
      color: #737373;
    }

    .content {
      text-align: center
    }

  </style>

  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@master/devicon.min.css">


  </head>

<?php
  
  $prefix = 'Apache/';
  $str = apache_get_version();
  
  if (0 === strpos($str, $prefix)) {
    $apacheV = substr($str, strlen($prefix));
    $apacheV = rtrim($apacheV, ' (Ubuntu)');
  }
    
    

  function getMySQLVersion()
  {
    $output = shell_exec('mysql -V');
    preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
    return $version[0];
  }
  
?>

  <body>
    <div class="container"> 
      <div class="content">
        <h1>&gt;dev.box<span class="blinkr">_</span></h1>
        <span class="icons">
          <i class="devicon-ubuntu-plain colored"></i>Ubuntu <?php echo parse_ini_string(shell_exec('cat /etc/lsb-release'))['DISTRIB_RELEASE']; ?> &nbsp;
          <i class="devicon-php-plain colored"></i>PHP <?php echo phpversion(); ?> &nbsp; 
          <i class="devicon-mysql-plain colored"></i>MySQL <?php echo getMySQLVersion(); ?> &nbsp;
          <i class="devicon-apache-plain colored"></i>Apache <?php echo $apacheV; ?> &nbsp; 
          <i class="devicon-nodejs-plain colored"></i>Node <?php echo ltrim(shell_exec("node -v"), 'v'); ?></span><br>
      </div>
    </div> 
  </body>
</html>

