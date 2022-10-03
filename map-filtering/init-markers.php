<?php

// Check if browser is IE
function is_IE() {
  return (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false));
}

// Init map markers
function init_map_markers() {
  // Return if browser is IE
  if (is_IE()) return;

  // Create map markers array
  $markers = [];
  $marker_args = array(
    'post_type' => 'members',
    'order'    => 'ASC',
    'numberposts' => -1,
    'posts_per_page' => -1
  );

  $markers_query = new WP_Query($marker_args);

  // Go through members and push their data to markers array
  if ($markers_query->have_posts()) :
    while ($markers_query->have_posts()) :  $markers_query->the_post();
      $marker_name = get_the_title(get_the_id());
      $marker_type = get_field('member_type') == 'steering_group' ? 'member' : get_field('member_type');
      $marker_lat = esc_html(get_field('member_lat'));
      $marker_lng = esc_html(get_field('member_lng'));
      $marker_website = esc_attr(get_field('member_website'));
      $marker_text = wp_kses_post(get_field('member_marker_text'));
      $marker_highlighted = get_field('member_type') == 'steering_group' ? true : false;

      // Check if markers have all required information
      if ($marker_name && $marker_type && $marker_lat && $marker_lng) :

        // Create sub-array of marker item
        $marker_info = array(
          $marker_name, $marker_type, $marker_lat, $marker_lng, $marker_text, $marker_website, $marker_highlighted
        );

        // Push marker item array to markers array
        $markers[] = $marker_info;
      endif;
    endwhile;
    wp_reset_postdata();
  endif;

  // Encode markers array for javascript
  $markers = json_encode($markers);

  return $markers;
}
