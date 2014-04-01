<div class="large-9 small-12 engine-block column center-column">
    <div class="block-Slider">
        <?php
        $args = array(
            'th_size'        => 'medium',
            'cat_id'         => $category->cat_ID,
            'qty'            => 4,
            'random'         => '0',
            'autoplay'       => '6000',
            'excerpt_length' => '30'
        );
        display_category_slider($args);
        ?>
    </div>
</div>