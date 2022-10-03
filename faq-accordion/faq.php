<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'faq-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" value.
$className = 'faq block-spacing is-style-narrow';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}

$className .= get_field('remove_margin_top') ? ' block-no-top-margin' : '';
$className .= get_field('remove_margin_bottom') ? ' block-no-bottom-margin' : '';

$questions = get_field('faq_items');

if ($questions) :
?>

  <section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" role="tablist">
    <?php foreach ($questions as $post) : setup_postdata($post);
      $item = $post->ID;
      $button_id = 'tab_' . $block['id'] . '_' . $item;
      $content_id = 'panel_' . $block['id'] . '_' . $item;
      $item_id = get_post_type($item) . '_' . $item;
      $title = get_the_title($item);
      $content = strip_tags(apply_filters('the_content', get_the_content($item)));

      if (!empty(get_the_content($item))) : ?>
        <div id="<?php echo esc_attr($item_id); ?>" class="faq__item accordion__item js-accordion-item">
          <button id="<?php echo esc_attr($button_id); ?>" class="accordion__button js-accordion-button" type="button" role="tab" aria-expanded="false" aria-controls="<?php echo esc_attr($content_id); ?>">
            <?php echo esc_html($title); ?>
            <span class="accordion__button-icon"></span>
          </button>
          <div id="<?php echo esc_attr($content_id); ?>" class="accordion__content js-accordion-content" role="tabpanel" aria-hidden="true" aria-labelledby="<?php echo esc_attr($button_id); ?>">
            <div class="accordion__inner">
              <?php
              // If content is more than 250 characters, trim content and add read more link
              if (strlen($content) > 250) :
                $content = substr($content, 0, 250);
                $content = substr($content, 0, strripos($content, ' '));
                $content = '<p>' . $content . '...</p>';
                $content .= '<p><a href="' . get_permalink($item) . '" class="arrow-link">' . string_translate('Lue lisää') . '</a></p>';
              else :
                $content = '<p>' . $content . '</p>';
              endif;

              echo wp_kses_post($content); ?>
            </div>
          </div>
        </div>
    <?php endif;
    endforeach;
    wp_reset_postdata();
    ?>
  </section>
<?php endif; ?>