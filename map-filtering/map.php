<?php
$heading = get_field('map_heading');

// Create id attribute allowing for custom "anchor" value.
$id = 'map-' . $block['id'];
if (!empty($block['anchor'])) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" value.
$className = 'map block-full';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
  <div class="map__container container">
    <div class="map__content">
      <?php if ($heading) echo '<h2 class="map__heading">' . esc_html($heading) . '</h2>'; ?>
      <ul class="map__types">
        <li class="map__types-item">
          <span class="map__types-icon">
            <?php include get_theme_file_path('/svg/marker-member.svg'); ?>
          </span>
          <span class="map__types-text">Jäsenorganisaatio</span>
        </li>
        <li class="map__types-item">
          <span class="map__types-icon">
            <?php include get_theme_file_path('/svg/marker-partner.svg'); ?>
          </span>
          <span class="map__types-text">Kumppaniorganisaatio</span>
        </li>
        <li class="map__types-item">
          <span class="map__types-icon">
            <?php include get_theme_file_path('/svg/marker-clusterer-number.svg'); ?>
          </span>
          <span class="map__types-text">Alueella useita</span>
        </li>
      </ul>
      <fieldset class="map__filter">
        <legend class="map__filter-heading heading-small">Näytä kartalla</legend>
        <div class="map__filter-item">
          <input type="radio" value="all" class="map__filter-radio" name="type" id="all" checked />
          <label for="all">Kaikki</label>
        </div>
        <div class="map__filter-item">
          <input type="radio" value="member" class="map__filter-radio" name="type" id="member" />
          <label for="member">Jäsenet</label>
        </div>
        <div class="map__filter-item">
          <input type="radio" value="partner" class="map__filter-radio" name="type" id="partner" />
          <label for="partner">Kumppanit</label>
        </div>
      </fieldset>
    </div>
  </div>
  <?php if (is_IE()) : ?>
    <div class="map__ie">
      <p>Valitettavasti kartta ei toimi, sillä selaimesi on vanhentunut. Voit päivittää selaimesi esimerkiksi <a href="https://www.microsoft.com/fi-fi/edge">Microsoft Edge -selaimeen</a>, joka on korvannut Internet Explorerin</p>
    </div>
  <?php else : ?>
    <div id="map" class="map__map"></div>
  <?php endif; ?>
</section>

<?php init_map_markers(); ?>