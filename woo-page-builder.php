<?php
/*
Plugin Name: Product Page Builder - Woo
Description: Use your favourite page builder for your woocommerce product pages, just as you would for your other WordPress pages!
Version: 2.0
Author: Karl Cooper
Author URI: https://prolifik.co.uk/
License: GPLv2 or later
Text Domain: wcpb
*/


// add meta fields to the admin -> edit product page
include_once "meta-boxes.php";

// replace the_content() on the product pages (as applicable) with the content from the page chosen
include_once "content-replacement.php";

// if not logged in as an admin, redirect the user to the new page.
include_once "page-redirect.php";

?>
