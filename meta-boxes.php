<?php
function wcpb_add_custom_meta_box_2() {
  add_meta_box(
    'wcpb-product-settings',       // $id
    'Product Page Builder',                  // $title
    'wcpb_meta_box',  // $callback
    'product',                // $page
    'normal',                  // $context
    'high'                     // $priority
  );
}
add_action('add_meta_boxes', 'wcpb_add_custom_meta_box_2');

function wcpb_admin_scripts( $hook ) {
  wp_enqueue_style( 'wcpb_css', plugin_dir_url( __FILE__ ) . 'css/wcpb-admin.css', array() );
  wp_enqueue_script( 'wcpb_js', plugin_dir_url( __FILE__ ) . 'js/wcpb-admin.js', array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'wcpb_admin_scripts' );



function wcpb_meta_box() {
  global $post;
  // Use nonce for verification to secure data sending
  wp_nonce_field( basename( __FILE__ ), 'wcpb_our_nonce' );


  $replacecontent = get_post_meta( $post->ID, 'wcpb-replace-content', true );
  $whichpage = get_post_meta( $post->ID, 'wcpb-which-page', true );
  ?>

  <div class="wcpb-meta-field">
    <label class="wcpb-meta-field-label">
      <input type="checkbox" name="wcpb-replace-content" id="wcpb-replace-content" <?php if ($replacecontent) { echo ' checked '; } ?>> Replace description with page content?
    </label>
  </div>

  <div class="wcpb-meta-field wcpb-page-selector" <?php if (!$replacecontent) { echo ' style="display:none;" '; } ?>>
    <label class="wcpb-meta-field-label">
      Which Page?
      <br>
      <small><i>Please note, for non-admins, this page will also now redirect to this product page)</i></small>
      <br>
    </label>
    <select name="wcpb-which-page">
      <?php
      foreach( get_pages() as $page ) {
        ?>
        <option value="<?php echo $page->ID; ?>" <?php if ($page->ID == $whichpage) echo ' selected '; ?>><?php echo $page->post_title; ?></option>
        <?php
      }
      ?>
    </select>
  </div>

  <?php
}



//now we are saving the data
function wcpb_save_meta_fields( $post_id ) {

  // verify nonce
  if (!isset($_POST['wcpb_our_nonce']) || !wp_verify_nonce($_POST['wcpb_our_nonce'], basename(__FILE__)))
      return 'nonce not verified';

  // check autosave
  if ( wp_is_post_autosave( $post_id ) )
      return 'autosave';

  //check post revision
  if ( wp_is_post_revision( $post_id ) )
      return 'revision';

  // check permissions
  if ( 'product' == $_POST['post_type'] ) {
      if ( ! current_user_can( 'edit_page', $post_id ) )
          return 'cannot edit page';
      } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
          return 'cannot edit post';
  }

  if (isset($_POST['wcpb-replace-content'])) {
    // will only ever be an integer.
    if (is_numeric($_POST['wcpb-which-page'])) {
      update_post_meta( $post_id, 'wcpb-replace-content', 1 );

      // will only ever be an integer.
      update_post_meta( $post_id, 'wcpb-which-page', intval($_POST['wcpb-which-page']) );
    }
  } else {
    update_post_meta( $post_id, 'wcpb-replace-content', 0 );
    update_post_meta( $post_id, 'wcpb-which-page', 0 );
  }

  //echo "DONE!".$post_id;
  //exit();


}
add_action( 'save_post', 'wcpb_save_meta_fields' );
add_action( 'new_to_publish', 'wcpb_save_meta_fields' );
