<?php
/** A Stock Index Block **/
if(!class_exists('EPG_Ad_Position_Block')) {
    class EPG_Ad_Position_Block extends AQ_Block {
        function __construct() {
            $block_options = array(
                'name' => 'Ad-Position',
                'size' => 'span6',
            );
            parent::__construct('EPG_Ad_Position_Block', $block_options);
        }

        function form($instance) {
            $defaults = array(
                'title' => '',
                'position' => '',
                'location' => ''
            );
            $instance = wp_parse_args($instance, $defaults);

            extract($instance);

            ?>
            <p class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    <?php _e('Title','engine'); ?>
                    <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
                </label>
            </p>
            <p class="description">
                <label for="<?php echo $this->get_field_id('position') ?>">
                    <?php _e('Position:','engine'); ?>
                    <?php echo aq_field_input('position', $block_id, $position, $size = 'full') ?>
                </label>
            </p>
            <p class="description">
                <label for="<?php echo $this->get_field_id('location') ?>">
                    <?php _e('Location','engine'); ?>
                    <?php echo aq_field_input('location', $block_id, $location, $size = 'full') ?>
                </label>
            </p>
        <?php
        }
        function block($instance) {

            extract($instance);

            the_ad_position($position, $location, $inline = FALSE);
        }
    }
}