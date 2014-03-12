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
                'position_id' => '',
                'width' => '',
                'height' => ''
            );
            $instance = wp_parse_args($instance, $defaults);
            extract($instance);
            ?>
            <p class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    <?php _e('Position','engine'); ?>
                    <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
                </label>
            </p>
            <p class="description">
                <label for="<?php echo $this->get_field_id('position_id') ?>">
                    <?php _e('Position ID','engine'); ?>
                    <?php echo aq_field_input('position_id', $block_id, $position_id, $size = 'full') ?>
                </label>
            </p>
            <p class="description">
                <label for="<?php echo $this->get_field_id('width') ?>">
                    <?php _e('Width','engine'); ?>
                    <?php echo aq_field_input('Width', $block_id, $width, $size = 'full') ?>
                </label>
            </p>
        <?php
        }
        function block($instance) {
            extract($instance);
            echo '<!-- ' . $title . ' -->';
            // start positioning div
            echo  '<div id="' . $position_id . '">';
            // echo script
            echo '<script type="text/javascript">' .
                'googletag.cmd.push(function() { googletag.display("' . $position_id . '"); });' .
                '</script>';
            // close div
            echo '</div>';
        }
    }
}