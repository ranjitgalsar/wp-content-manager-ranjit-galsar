<div class="promo-cards">
    <?php foreach($promo_blocks as $promo_block): ?>
        <?php
            $thumbnail = get_the_post_thumbnail_url($promo_block);
            $content = get_the_content(null,false,$promo_block->ID);
        ?>
        <div class="promo-card">
            <img loading="lazy" src="<?php echo $thumbnail; ?>" alt="<?php echo $promo_block->post_title; ?>">
            <h3><?php echo $promo_block->post_title; ?></h3>
            <p><?php echo $content; ?></p>
        </div>
    <?php endforeach; ?>
</div>