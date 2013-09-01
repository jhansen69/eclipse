<?php

/*
*  initialize some global variables that will be accessed
* 
*/
$db=''; //global db variable

  function page_header()
  {
      global $db;
      session_start();
      error_reporting(E_ALL ^ E_NOTICE);
      /*
      *
      * INCLUDE ALL NECESSARY HELPER FILES
      * 
      */
      include("includes/Zebra_Database.php");
      $db = new Zebra_Database();
      $sql_server='localhost';
      $sql_user="allthjc0_eclipse";
      $sql_pass="slcrbt511";
      $sql_database="allthjc0_eclipse";
       
      // turn debugging on
      $db->debug = true;
      $db->connect($sql_server, $sql_user, $sql_pass, $sql_database);
      
      include("includes/jhFormClass.php"); //this includes all form handling/rending functionality - requires Zebra_Database class 
      include("includes/functions_common.php");
      
      
      
      
      ?>
    <!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Eclipse Phase Toolkit</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="/styles/normalize.css">
        <link rel="stylesheet" href="/styles/bootstrap.min.css">
        <style type='text/css'>
        body {
            padding:60px;
        }
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <!-- OR remove these lines and place icons directly
            in the site root folder mathiasbynens.be/notes/touch-icons -->
        <link rel="shortcut icon" href="img/favicon.ico">
        <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
        <link rel="stylesheet" href="/styles/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="/styles/jquery.dataTables.css">
        <link rel="stylesheet" href="/styles/jquery-ui-1.10.3.custom.min.css">
        <link rel="stylesheet" href="/styles/main.css">
        <script src="/includes/scripts/jquery-2.0.3.min.js"></script>
        <script src="/includes/scripts/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="/includes/scripts/bootstrap.min.js"></script>
        <script src="/includes/scripts/bootstrap-datetimepicker.min.js"></script>
        <script src="/includes/scripts/bootbox.min.js"></script>
        <script src="/includes/scripts/jquery.validate.min.js"></script>
        <script src="/includes/scripts/jquery.dataTables.min.js"></script>
        <script src="/includes/scripts/modernizr-2.6.2.min.js"></script>
        <script src="/includes/scripts/main.js"></script>
        <script src="/includes/scripts/plugins.js"></script>
        
        <!-- load the Zebra_Database debugging console files -->
        <link rel="stylesheet" href="/includes/zebra/database.css">
        <script src="/includes/zebra/database.js"></script>
        <script src="/includes/zebra/getelementbyclassname.js"></script>
        
        
    </head>
    <body>
    <div class='mcontainer'>
    <?php 
    /* generate sticky page menu */
    page_menu();
    ?>
    
    <?php  
  }
  
  function page_menu_multilevel()
  {
      /* sample of a multi-level bootstrap menu */
      ?>
      <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container">
              <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </a>

              <div class="nav-collapse">
                <ul class="nav">
                        <a class="brand" href="#">Present Ideas</a>
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Help</a></li>
                        <li class="dropdown" id="accountmenu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Account Settings<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Login</a></li>
                                  <li class="dropdown-submenu">
                                  <a tabindex="-1" href="#">More options</a>
                                  <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="#">Second level</a></li>
                                    <li class="dropdown-submenu">
                                      <a href="#">More..</a>
                                      <ul class="dropdown-menu">
                                          <li><a href="#">3rd level</a></li>
                                          <li><a href="#">3rd level</a></li>
                                      </ul>
                                    </li>
                                    <li><a href="#">Second level</a></li>
                                    <li><a href="#">Second level</a></li>
                                  </ul>
                                </li>
                                <li><a href="#">Register</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <ul class="nav pull-right">

                </ul>
              </div><!--/.nav-collapse -->
            </div>
          </div>
        </div>
        
        <!-- or with bootstrap 3 -->
        <div class="navbar navbar-fixed-top navbar-default">
            <div class="navbar-header"><a class="navbar-brand" href="#">Present Ideas</a><a class="navbar-toggle"
                data-toggle="collapse" data-target=".navbar-collapse">
                <span class="glyphicon glyphicon-bar"></span> 
                <span class="glyphicon glyphicon-bar"></span>
                <span class="glyphicon glyphicon-bar"></span>
              </a>
            </div>
            <div class="container">
                <div class="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a>
                        </li>
                        <li><a href="#">Blog</a>
                        </li>
                        <li><a href="#">About</a>
                        </li>
                        <li><a href="#">Help</a>
                        </li>
                        <li class="dropdown" id="accountmenu"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Account Settings<b class="caret"></b></a>

                            <ul
                            class="dropdown-menu">
                                <li><a href="#">Login</a>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#">More options</a>

                                    <ul class="dropdown-menu">
                                        <li><a tabindex="-1" href="#">Second level</a>
                                        </li>
                                        <li class="dropdown-submenu"> <a href="#">More..</a>

                                            <ul class="dropdown-menu">
                                                <li><a href="#">3rd level</a>
                                                </li>
                                                <li><a href="#">3rd level</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Second level</a>
                                        </li>
                                        <li><a href="#">Second level</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#">Register</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Logout</a>
                                </li>
                    </ul>
                    </li>
                    </ul>
                    <ul class="nav pull-right navbar-nav"></ul>
                </div>
                <!--/.navbar-collapse -->
            </div>
        </div>
        
      <?php
  }
  function page_menu()
  {
      /*
      *
      * THIS CREATES A 'STICKY' TOP MENU THAT WILL INCLUDE A SIGN-IN MODAL AND DROPDOWN MENUS
      * 
      */
      ?>
      <div class="navbar navbar-inverse navbar-fixed-top clearfix">
          <div class="navbar-inner">
            <a class="brand" href="http://www.eclipsephase.com" target='_blank'>&nbsp;&nbsp;&nbsp;&nbsp;EP</a>
            <ul class="nav">
              <li class="divider-vertical"></li>
              <li><a href="index.php">Home</a></li>
              
              <li class="dropdown">
                <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown" id='menu_games'>
                  Games <strong class="caret"></strong>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu_games">
                  <li><a href="mygames.php">My Games</a></li>
                  <li><a href="joingame.php">Join a game</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown" id='menu_characters'>
                  Characters <strong class="caret"></strong>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu_characters">
                  <li><a href="mycharacters.php">My Characters</a></li>
                  <li><a href="mycharacters.php?action=create">Create new character</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown" id='menu_gamemaster'>
                  Game Master <strong class="caret"></strong>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu_gamemaster">
                  <li><a href="mycampaigns.php">My Campaigns</a></li>
                  <li><a href="mycampaigns.php?action=create">Create new campaign</a></li>
                </ul>
              </li>
              <li><a href="help.php">Help</a></li>
              
            </ul>
            <ul class="nav pull-right">
              <?php
                  if ($_SESSION['logged_in'])
                  {
                  ?>
                  <li><a href="/users/manage_account.php">Manage Account</a></li>
                  <?php
                  } else {
                      
              ?>
              <li><a href="/users/sign_up">Sign Up</a></li>
              <li class="divider-vertical"></li>
              <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                  <form action="index.php" method="post" accept-charset="UTF-8">
                      <input id="user_username" style="margin-bottom: 15px;" type="text" name="user[username]" placeholder='Email' size="30" />
                      <input id="user_password" style="margin-bottom: 15px;" type="password" name="user[password]" placeholder='Password' size="30" />
                      <input id="user_remember_me" style="float: left; margin-right: 10px;" type="checkbox" name="user[remember_me]" checked />
                      <label class="string optional" for="user_remember_me"> Remember me</label>
                     
                      <input class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="commit" value="Sign In" />
                  </form>
                  <script>
                  $(function() {
                      // Setup drop down menu
                      $('.dropdown-toggle').dropdown();
                     
                      // Fix input element click problem
                      $('.dropdown input, .dropdown label').click(function(e) {
                        e.stopPropagation();
                      });
                    });
                    </script>
                </div>
              </li>
              <?php
                  }
              ?>
            </ul>
          </div><!-- close navbar-inner -->
      </div><!-- close navbar -->
      <div class='clearfix'></div>
      <?php
      
  }
  
  function handle_login()
  {
      
  }
  
  function bad_login()
  {
      
  }
  
  function page_footer()
  {
      print "\n";
      ?>
      </div><!-- closes container div from page_header -->
      <!-- load google analytics code -->
      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-19066851-3', 'eclipsephaseproject.com');
      ga('send', 'pageview');

    </script>
     </body>
</html>

      <?php
      
  }
?>
