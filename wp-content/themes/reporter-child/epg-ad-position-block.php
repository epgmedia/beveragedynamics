<?php
/** An Ad Placement Block **/
class EPG_Stock_Index_Block extends AQ_Block {

    //set and create block
    function __construct() {
        $block_options = array(
            'name' => 'Stock Index',
            'size' => 'span4',
        );

        //create the block
        parent::__construct('EPG_Stock_Index_Block', $block_options);
    }

    function form($instance) {

        $defaults = array(
            'Title' => '',
        );
        $instance = wp_parse_args($instance, $defaults);
        extract($instance);

        ?>
        <p class="description">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Title
                <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
            </label>
        </p>
        <p class="description">
            <label for="<?php echo $this->get_field_id('symbols') ?>">
                Stock Symbols (Comma separated list)
                <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
            </label>
        </p>
        <p class="description">
            <label for="<?php echo $this->get_field_id('symbols') ?>">
                Stock Symbols (Comma separated list)
                <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
            </label>
        </p>

    <?php
    }

    function block($instance) {
        extract($instance);

        if($title) echo '<h4 class="widget-title">'.strip_tags($title).'</h4>';
        echo wpautop(do_shortcode(htmlspecialchars_decode(stripslashes_deep($text))));
    }
}

aq_register_block('EPG_Stock_Index_Block');
