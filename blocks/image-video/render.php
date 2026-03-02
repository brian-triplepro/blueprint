<?php
    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/screenshot.png') . '" alt="Preview" style="width: 100%; height: auto;" />';
        return;
    }
?>

<section class="<?php echo 'block__' . basename( __DIR__ ); ?>">
  <div class="container">
    <div class="video-wrapper">
      <?php
        // Support either a URL (YouTube/Vimeo via oEmbed), an uploaded video file or an uploaded image.
        $video_source = get_field( 'source' );
        $video_url    = get_field( 'video_url' );
        $video_file   = get_field( 'file' );

        // resolve field output to URL if necessary (array/id/string)
        $file_url = '';
        if ( is_array( $video_file ) && ! empty( $video_file['url'] ) ) {
            $file_url = $video_file['url'];
        } elseif ( is_numeric( $video_file ) ) {
            $file_url = wp_get_attachment_url( $video_file );
        } elseif ( is_string( $video_file ) ) {
            $file_url = $video_file;
        }

        if ( $video_source === 'upload-video' && $file_url ) {
            echo '<video controls playsinline preload="metadata" style="max-width:100%;height:auto; width:100%;">';
            echo '<source src="' . esc_url( $file_url ) . '">';
            echo 'Your browser does not support the video tag.';
            echo '</video>';
        } elseif ( $video_source === 'upload-image' && $file_url ) {
            echo '<img src="' . esc_url( $file_url ) . '" alt="" style="max-width:100%;height:auto; width:100%;" />';
        } elseif ( $video_url ) {
            // Normalize YouTube short URLs (e.g. /shorts/...) and youtu.be links so oEmbed recognizes them.
            if ( preg_match( '#youtube\.com/shorts/([A-Za-z0-9_-]+)#', $video_url, $m ) ) {
                $video_url = 'https://www.youtube.com/watch?v=' . $m[1];
            } elseif ( preg_match( '#youtu\.be/([A-Za-z0-9_-]+)#', $video_url, $m ) ) {
                $video_url = 'https://www.youtube.com/watch?v=' . $m[1];
            }

            $embed_code = wp_oembed_get( $video_url );
            if ( $embed_code ) {
                echo $embed_code;
            } else {
                // Try manual iframe fallback for YouTube/Vimeo if oEmbed fails
                if ( preg_match( '#(?:youtube(?:-nocookie)?\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([A-Za-z0-9_-]{5,})#', $video_url, $m ) ) {
                    $id = $m[1];
                    echo '<iframe width="100%" height="800" src="https://www.youtube.com/embed/' . esc_attr( $id ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                } elseif ( preg_match( '#vimeo\.com/(?:video/)?([0-9]+)#', $video_url, $m ) ) {
                    $id = $m[1];
                    echo '<iframe src="https://player.vimeo.com/video/' . esc_attr( $id ) . '" width="100%" height="800"  frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                } else {
                    echo '<p>Video could not be embedded. Please check the URL.</p>';
                }
            }
        } else {
            echo '<p>No video provided. Add a YouTube/Vimeo URL or upload a video file.</p>';
        }
      ?>
    </div>
</section>
