<?php

function wcpb_page_redirects() {
  global $post;

  //admin may want to make / view edits
  if ( current_user_can( 'edit_page', $post->ID ) )
    return;

  if ($post->post_type == "page") {

    $args = array(
  		'post_type' => 'product',
  		'meta_query' => array(

  	    'relation' => 'AND',

  	    'wcpb-which-page_clause' => array(
  	      'key' => 'wcpb-which-page',
  	      'compare' => 'IN',
  	      'value' => $post->ID,
  	    ),

  	    'wcpb-replace-content_clause' => array(
  	      'key' => 'wcpb-replace-content',
  	      'compare' => 'IN',
  	      'value' => true,
  	    ),
  	  ),
  	);

  	$redirecttoproductpage = false;

  	$the_query = new WP_Query( $args );
  	if ( $the_query->have_posts() ) {
  	  while ( $the_query->have_posts() ) {
  	    $the_query->the_post();
  			$redirecttoproductpage = get_the_ID();
  	  }
  	}
  	/* Restore original Post Data */
  	wp_reset_postdata();

  	if ($redirecttoproductpage) {
  		wp_redirect( get_permalink( $redirecttoproductpage ) );
  		exit();
  	}
  }


}
add_action( 'template_redirect' , 'wcpb_page_redirects' );
