<?php
$mux_id = get_sub_field('mux_playback_id');
if (!$mux_id) return;
?>
<div class="booking-hero">
  <div class="booking-hero__video reveal reveal--up" data-mux-id="<?php echo esc_attr($mux_id); ?>">
  </div>
</div>
<script>
(function(){
  var el = document.querySelector('[data-mux-id]');
  if (!el) return;
  var loaded = false;
  new IntersectionObserver(function(entries){
    if (entries[0].isIntersecting && !loaded) {
      loaded = true;
      var id = el.getAttribute('data-mux-id');
      var s = document.createElement('script');
      s.type = 'module';
      s.src = 'https://cdn.jsdelivr.net/npm/@mux/mux-player@3/dist/mux-player.mjs';
      s.onload = function(){
        el.innerHTML = '<mux-player playback-id="'+id+'" autoplay muted loop playsinline stream-type="on-demand" default-hidden-captions playback-rates="" no-hot-keys preload="auto" prefer-playback="mse" min-resolution="720p" max-resolution="1080p" style="width:100%;height:100%;display:block;--controls:none;--media-object-fit:cover;--media-object-position:center;"></mux-player>';
      };
      document.head.appendChild(s);
    }
  }, {rootMargin: '200px'}).observe(el);
})();
</script>
