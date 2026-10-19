<?php
/**
 * Template Name: Tour Dates
 *
 * Tour hub: the page title is the H1, then the upcoming dates from
 * Theme Options → Schedule (the same list the front page shows), then the
 * editor content as the intro text. MusicEvent schema for these dates is added in
 * inc/schema-graph.php.
 */
get_header();
$is_ru = function_exists('pll_current_language') && pll_current_language() === 'ru';
$booking = get_page_by_path('booking-tribute-celentano');
$booking_url = $booking ? get_permalink(function_exists('pll_get_post') ? (pll_get_post($booking->ID) ?: $booking->ID) : $booking->ID) : '';
?>

<div class="page-spacing">
  <div class="page">
    <div class="columnar simple-column">
      <h1><?php echo esc_html(get_the_title()); ?></h1>
    </div>
  </div>
</div>

<div class="light-bg spacing-lg">
  <div class="columnar">
    <?php if (adolfo_upcoming_schedule()) : ?>
      <?php
      // schedule.php reads the section title from the current ACF row;
      // outside a row it prints none, so the H2 is rendered here.
      ?>
      <h2 class="tour-dates__title"><?php echo $is_ru ? 'Ближайшие концерты' : 'Upcoming Shows'; ?></h2>
      <?php get_template_part('template-parts/schedule'); ?>
    <?php else : ?>
      <p><?php echo $is_ru ? 'Новые даты скоро появятся.' : 'New dates will be announced soon.'; ?></p>
    <?php endif; ?>
    <?php if ($booking_url) : ?>
      <p class="tour-dates__book">
        <a class="button button--primary" href="<?php echo esc_url($booking_url); ?>">
          <?php echo $is_ru ? 'Заказать трибьют-шоу Челентано' : 'Book the Adriano Celentano Tribute Show'; ?>
        </a>
      </p>
    <?php endif; ?>
  </div>
</div>

<?php
// The intro text sits below the dates so visitors see the schedule first.
while (have_posts()) : the_post();
  if (trim(get_the_content())) : ?>
<div class="page-spacing">
  <div class="page tour-intro" style="padding-top:0;padding-bottom:60px">
    <div class="columnar simple-column">
      <div class="floating"><?php the_content(); ?></div>
    </div>
  </div>
</div>
  <?php endif;
endwhile; ?>

<?php get_footer(); ?>
