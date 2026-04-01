<?php

    if ( isset( $block['data']['is_preview'] ) && $block['data']['is_preview'] ) {
        $screenshot_uri  = get_stylesheet_directory_uri() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
        $screenshot_file = get_stylesheet_directory() . '/blocks/' . basename( __DIR__ ) . '/images/preview.png';
        if ( file_exists( $screenshot_file ) ) {
            echo '<img src="' . esc_url( $screenshot_uri ) . '" alt="Preview" style="width:100%;height:auto;" />';
            return;
        }
    }

    $colors = get_field('colors') ?: array();
    $bg_color = $colors['bg_color'] ?? 'background';
    $text_color = $colors['text_color'] ?? 'dark';

    $content = get_field('content') ?: array();
    $title = $content['title'] ?? '';

    $faqs = get_field( 'faqs', 'option' ) ?: array();

    $section_classes = 'blueprint-' . basename( __DIR__ );
    $section_classes .= ' bg-' . $bg_color;
    $section_classes .= ' text-' . $text_color;
    if ( get_field( 'rm_pb' ) === true ) $section_classes .= ' pb-0';

    $block_id = 'faq-' . ( $block['id'] ?? wp_unique_id() );
?>

<?php if ( ! empty( $faqs ) ) : ?>
<section class="<?= esc_attr( $section_classes ); ?>">
    <div class="container">
        <?php if ( $title ) : ?>
            <h2 <?php echo acf_inline_text_editing_attrs( 'content.title' ); ?> class="mb-[30px]">
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>

        <div class="faq-accordion" id="<?php echo esc_attr( $block_id ); ?>">
            <?php foreach ( $faqs as $index => $faq ) :
                $question = $faq['faq_question'] ?? '';
                $answer   = $faq['faq_answer'] ?? '';
                if ( ! $question ) continue;
                $item_id = $block_id . '-item-' . $index;
            ?>
                <div class="faq-accordion__item border-b border-current">
                    <button
                        class="faq-accordion__trigger w-full flex items-center justify-between py-[20px] text-left font-semibold cursor-pointer"
                        type="button"
                        aria-expanded="false"
                        aria-controls="<?php echo esc_attr( $item_id ); ?>"
                    >
                        <span><?php echo esc_html( $question ); ?></span>
                        <svg class="faq-accordion__icon w-[20px] h-[20px] shrink-0 ml-[16px] transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div
                        id="<?php echo esc_attr( $item_id ); ?>"
                        class="faq-accordion__content grid grid-rows-[0fr] transition-[grid-template-rows] duration-300"
                        role="region"
                    >
                        <div class="overflow-hidden">
                            <div class="pb-[20px]">
                                <?php echo wp_kses_post( $answer ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
(function() {
    const accordion = document.getElementById('<?php echo esc_js( $block_id ); ?>');
    if (!accordion) return;

    accordion.querySelectorAll('.faq-accordion__trigger').forEach(function(button) {
        button.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';

            // Close all other items
            accordion.querySelectorAll('.faq-accordion__trigger').forEach(function(other) {
                if (other !== button) {
                    other.setAttribute('aria-expanded', false);
                    document.getElementById(other.getAttribute('aria-controls')).style.gridTemplateRows = '0fr';
                    other.querySelector('.faq-accordion__icon').style.transform = '';
                }
            });

            // Toggle clicked item
            const content = document.getElementById(this.getAttribute('aria-controls'));
            this.setAttribute('aria-expanded', !expanded);
            content.style.gridTemplateRows = expanded ? '0fr' : '1fr';
            this.querySelector('.faq-accordion__icon').style.transform = expanded ? '' : 'rotate(180deg)';
        });
    });
})();
</script>
<?php endif; ?>