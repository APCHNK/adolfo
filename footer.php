    <?php get_template_part('template-parts/contact'); ?>

    <footer class="light-bg">
      <div class="columnar">
        <?php
        $copyright = get_field('footer_copyright', 'option') ?: 'Boney M Liz Mitchell. All Right Reserved';
        $email = get_field('footer_email', 'option') ?: 'book.boneym@gmail.com';
        ?>
        <div class="footer-wrap">
          <span><?php echo date('Y'); ?> &copy; <?php echo esc_html($copyright); ?></span>
          <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
        </div>
      </div>
    </footer>
  </div><!-- .app-wrapper -->

  <?php get_template_part('template-parts/modal'); ?>

  <?php if (is_front_page()) : ?>
    <script>document.addEventListener('DOMContentLoaded',function(){
      if(document.querySelector('.description-swiper')){
        var s=document.createElement('script');
        s.src='https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
        s.onload=function(){if(typeof initTextSliders==='function')initTextSliders();};
        document.body.appendChild(s);
      }
    });</script>
  <?php endif; ?>
  <?php wp_footer(); ?>
</body>
</html>
