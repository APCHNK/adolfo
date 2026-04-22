<?php
$headline = get_sub_field('headline');
$tracks = get_sub_field('tracks');

if (!$tracks || !is_array($tracks) || count($tracks) === 0) return;

// Extract src from iframe embed code
function extract_spotify_src($embed) {
  if (empty($embed)) return '';
  if (preg_match('/src=["\']([^"\']+)["\']/', $embed, $m)) {
    return $m[1];
  }
  // If it's already a URL
  if (strpos($embed, 'http') === 0) return $embed;
  return '';
}

$tracks_json = array_map(function ($track) {
  return array(
    'track_name'  => $track['track_name'] ?? '',
    'artist'      => $track['artist'] ?? '',
    'spotify_url' => extract_spotify_src($track['spotify_embed'] ?? ''),
  );
}, $tracks);

$play_icon = get_template_directory_uri() . '/assets/icons/play-icon.svg';
$pause_icon = get_template_directory_uri() . '/assets/icons/pause-icon.svg';
$first_url = $tracks_json[0]['spotify_url'] ?? '';
?>
<div class="player<?php echo count($tracks_json) > 1 ? ' has-tracklist' : ''; ?>">
  <?php if ($headline) : ?>
    <h2 class="reveal reveal--up"><?php echo esc_html($headline); ?></h2>
  <?php endif; ?>

  <div class="player-wrap reveal reveal--up" data-reveal-delay="200">
    <div class="spotify-embed">
      <?php if ($first_url) : ?>
        <iframe
          id="spotify-player"
          src="<?php echo esc_url($first_url); ?>"
          width="100%"
          height="232"
          frameborder="0"
          allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
          loading="lazy"
          style="border-radius:12px;"
        ></iframe>
      <?php endif; ?>
    </div>

    <?php if (count($tracks_json) > 1) : ?>
    <div class="library-songs">
      <?php foreach ($tracks_json as $i => $song) : ?>
        <div class="library-song <?php echo $i === 0 ? 'is-playing' : ''; ?>" data-spotify-url="<?php echo esc_url($song['spotify_url']); ?>">
          <div class="song-info">
            <img src="<?php echo esc_url($play_icon); ?>" alt="play" class="song-play-icon play-icon" width="32" height="32">
            <img src="<?php echo esc_url($pause_icon); ?>" alt="pause" class="song-play-icon pause-icon" width="32" height="32">
            <div>
              <span class="song-name"><?php echo esc_html($song['track_name']); ?></span>
              <span class="artist"><?php echo esc_html($song['artist']); ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
