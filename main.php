<?php
session_start();

require("headers/security_header.php");
require("headers/menu_header.php");

printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=style.css type=text/css>");
printf("<title>DJ Land</title></head>");

preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

if (count($matches)>1){
  //Then we're using IE
  $version = $matches[1];

  switch(true){
    case ($version<=8):
      print(" <body class='ie'> ");
      break;

    default:
      print("<body>");
  }
}


print_menu();
printf("<table width=100%% height=100%%><tr><td align=center>");

printf("<h1>User: %s<br>Logged In</h1>", get_username());

printf("</td></tr></table>");
printf("</body></html>");
?>