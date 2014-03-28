<?php
/** A Stock Index Block **/
class epg_ai1ec_agenda_block extends AQ_Block {
    function __construct() {
        $block_options = array(
            'name' => 'All-In-One-Events Calendar',
            'size' => 'span4',
        );
        parent::__construct('epg_ai1ec_agenda_block', $block_options);
    }

    function form($instance) {
        $defaults = array(
            'title' => '',
            'Categories' => ''
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
            <label for="<?php echo $this->get_field_id('categories') ?>">
                Categories
                <br/>Filter by event category names/slugs (separate names by comma)
                <?php echo aq_field_input('categories', $block_id, $categories, $size = 'full') ?>
            </label>
        </p>

    <?php
    }

    function block($instance) {
        extract($instance);


        if ($title !== NULL) {
            $title = '<h4 class="widget-title">' . $title . '</h4>';
        }

        $cats = explode(',', $categories);

        $filter_cats = array();

        foreach($cats as $cat) {
            $filter_cats[] = sanitize_title(trim($cat), '');
        }

        $filter_categories = '';
        if (!empty($filter_cats)) {
            $cat_string = implode($filter_cats);
            $filter_categories = ' cat_name="' . $cat_string . '"';
        }

        echo $title;
        echo do_shortcode('[ai1ec view="agenda"' . $filter_categories . ']');

    }
}