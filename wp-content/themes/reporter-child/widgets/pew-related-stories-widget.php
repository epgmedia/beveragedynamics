<?php
/**
 * Ad Position Widget
 * Adds a widget that takes an ad position's variables and creates a new ad position
 *
 */
class pew_related_stories_widget extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'pew_related_stories_widget', __("Show related stories - PEW plugin", 'reactor') );
        $this->WP_Widget('pew_related_stories_widget', 'Related Posts', $widget_ops);
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr( $instance['title'] ) : '';
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo $title; ?>" placeholder="Widget Title" /></p>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function pew_related( $args = array(), $post_id = '', $related_id = '' ) {
        $post_ids = $this->get_pew_related_data( $args, $post_id, $related_id );

        if( !$post_ids ) {
            return false;
        }
        echo $post_ids;
        $defaults = array(
            'post__in' => $post_ids,
            'orderby' => 'post__in',
            'post_type' => array('post'),
            'posts_per_page' => min( array(count($post_ids), 10)),
            'related_title' => 'Related Posts'
        );
        $options = wp_parse_args( $args, $defaults );

        $related_posts = new WP_Query( $options );
        if( $related_posts->have_posts() ):
            ?>
            <h5><?=$options['related_title']?></h5>
            <div id="related-material" class="promo">
                <?php while ( $related_posts->have_posts() ):
                    $related_posts->the_post();
                    ?>
                    <a class="post" href="<?=the_permalink();?>">
                        <div class="meta">
                            <?php
                            $post_project = wp_get_object_terms($related_posts->post->ID, 'projects');
                            $project = 'Pew Research Center';
                            $project_slug = '';
                            if( isset($post_project[0]) ) {
                                $project = $post_project[0]->name;
                                $project_slug =  $post_project[0]->slug;
                            } elseif( $related_posts->post->post_type == 'fact-tank' ) {
                                $project = 'Fact Tank';
                                $project_slug = 'fact-tank';
                            }
                            ?>
                            <span class="project <?=$project_slug;?> right-seperator"><?=$project;?></span>
                            <span class="date"><?php the_time('M j, Y'); ?></span>
                        </div>
                        <h2><?=the_title();?></h2>
                    </a>
                <?php endwhile;
                wp_reset_postdata();

                ?>
                </ol>
            </div>
        <?php
        endif;
    }

    function get_pew_related_data($args, $post_id, $related_id) {
        global $post, $wpdb;
        $post_id = intval( $post_id );
        if( !$post_id && $post->ID ) {
            $post_id = $post->ID;
        }

        if( !$post_id ) {
            return false;
        }

        $defaults = array(
            'taxonomy' => 'topics',
            'post_type' => array('post'),
            'max' => 5
        );
        $options = wp_parse_args( $args, $defaults );

        $transient_name = 'pew-related-' . $options['taxonomy'] . '-' . $post_id;

        if( isset($_GET['flush-related-links']) && is_user_logged_in() ) {
            echo '<p>Related links flushed! (' . $transient_name . ')</p>';
            delete_transient( $transient_name );
        }

        $output = get_transient( $transient_name );
        if( $output !== false && !is_preview() ) {
            //echo $transient_name . ' read!';
            return $output;
        }

        $args = array(
            'fields' => 'ids',
            'orderby' => 'count',
            'order' => 'ASC'
        );
        $orig_terms_set = wp_get_object_terms( $post_id, $options['taxonomy'], $args );

        //Make sure each returned term id to be an integer.
        $orig_terms_set = array_map('intval', $orig_terms_set);

        //Store a copy that we'll be reducing by one item for each iteration.
        $terms_to_iterate = $orig_terms_set;

        $post_args = array(
            'fields' => 'ids',
            'post_type' => $options['post_type'],
            'post__not_in' => array($post_id),
            'posts_per_page' => 50
        );
        $output = array();
        while( count( $terms_to_iterate ) > 1 ) {

            $post_args['tax_query'] = array(
                array(
                    'taxonomy' => $options['taxonomy'],
                    'field' => 'id',
                    'terms' => $terms_to_iterate,
                    'operator' => 'AND'
                )
            );

            $posts = get_posts( $post_args );

            /*
            echo '<br>';
            echo '<br>';
            echo $wpdb->last_query;
            echo '<br>';
            echo 'Terms: ' . implode(', ', $terms_to_iterate);
            echo '<br>';
            echo 'Posts: ';
            echo '<br>';
            print_r( $posts );
            echo '<br>';
            echo '<br>';
            echo '<br>';
            */

            foreach( $posts as $id ) {
                $id = intval( $id );
                if( !in_array( $id, $output) ) {
                    $output[] = $id;
                }
            }
            array_pop( $terms_to_iterate );
        }

        $post_args['posts_per_page'] = 10;
        $post_args['tax_query'] = array(
            array(
                'taxonomy' => $options['taxonomy'],
                'field' => 'id',
                'terms' => $orig_terms_set
            )
        );

        $posts = get_posts( $post_args );

        foreach( $posts as $count => $id ) {
            $id = intval( $id );
            if( !in_array( $id, $output) ) {
                $output[] = $id;
            }
            if( count($output) > $options['max'] ) {
                //We have enough related post IDs now, stop the loop.
                break;
            }
        }

        if( !is_preview() ) {
            //echo $transient_name . ' set!';
            set_transient( $transient_name, $output, 24 * HOUR_IN_SECONDS );
        }

        return $output;
    }


    function widget($args, $instance) {
        global $post;
        $this->pew_related($args, $post->post_id);
    }
}