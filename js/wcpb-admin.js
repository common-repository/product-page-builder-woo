jQuery( document ).ready( function($) {

  function wcpb_update_visible_fields() {
    if ($( '#wcpb-replace-content' ).is( ':checked' )) {
      $( '.wcpb-meta-field.wcpb-page-selector' ).slideDown();
    } else {
      $( '.wcpb-meta-field.wcpb-page-selector' ).slideUp();
    }
  }
  
  $( '#wcpb-replace-content' ).change( function() {
    wcpb_update_visible_fields();
  });

  if ($( '#wcpb-replace-content' ).length) {
    wcpb_update_visible_fields();
  }
});
