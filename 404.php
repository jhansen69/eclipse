<?php
include("includes/functions_page.php");
page_header();
page();
page_footer();


function page()
{
    print "<div class='alert alert-error'>";
    print "Sorry, the page you are looking for was not found!";
    print $_GET['rurl'];
    print "</div>\n";
    
}
?>