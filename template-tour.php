<?php
/**
 * Template Name: Tour Dates
 *
 * Tour hub: the page title is the H1, the editor content is the intro, and
 * the upcoming dates come from Theme Options → Schedule (the same list the
 * front page shows). MusicEvent schema for these dates is added in
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
      <?php while (have_posts()) : the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <?php if (trim(get_the_content())) : ?>
          <div class="floating"><?php the_content(); ?></div>
        <?php endif; ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>

<div class="light-bg">
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

<?php get_footer(); ?>
