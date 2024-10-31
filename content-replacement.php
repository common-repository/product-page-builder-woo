<?php

function wcpb_replace_content( $content ) {
  global $post;

  $posttoreset = $post;

  // Check if we're inside the main loop in a single Post.
  if ( is_singular('product') && in_the_loop() && is_main_query() ) {
    $replacecontent = get_post_meta( $post->ID, 'wcpb-replace-content', true );
    $whichpage = get_post_meta( $post->ID, 'wcpb-which-page', true );

    if ($replacecontent && $whichpage) {
    	$post = get_post( $whichpage );
    	setup_postdata( $post );
    	the_content();
    	wp_reset_postdata();
    }
  }

  return $content;
}
add_filter( 'the_content', 'wcpb_replace_content', 1 );
?>
