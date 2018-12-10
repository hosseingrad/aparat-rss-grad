<?php

if ( !class_exists( 'Widget_Aparat_RSS_List__APARSSGRAD' ) ) {


    class Widget_Aparat_RSS_List__APARSSGRAD extends WP_Widget {

        /**
        * Short News Widget Construct
        *
        */
        public function __construct() {
            $widget_ops = array (
                'classname'     => __( 'widget_recent_entries widget_aparat_rss_aparssgrad', 'aparss-grad' ),
                'description'   => __( 'Showing Aparat videos form a Channel RSS.', 'aparss-grad' )
            );
            parent::__construct( 'aparat-rss-list-aparssgrad', 'GRAD | ' . __( 'Aparat Videos RSS List', 'aparss-grad' ), $widget_ops );
        }



        /**
        * Short News Widget Core
        *
        * @param array $args
        * @param array $instance
        */
        public function widget( $args, $instance ) {

            $widget_title       = isset( $instance['widget_title'] )        ? esc_attr( $instance['widget_title'] )     : '';
            $aparat_rss_link    = isset( $instance['aparat_rss_link'] )     ? esc_attr( $instance['aparat_rss_link'] )  : '';
            $show_channel_tile  = isset( $instance['show_channel_tile'] )   ? (bool) $instance['show_channel_tile']     : false;
            $show_video_num     = isset( $instance['show_video_num'] )      ? absint( $instance['show_video_num'] )     : 0;
            $show_first_full    = isset( $instance['show_first_full'] )     ? (bool) $instance['show_first_full']       : false;
            $show_upload_date   = isset( $instance['show_upload_date'] )    ? (bool) $instance['show_upload_date']      : false;

            $show_seemore_link  = isset( $instance['show_seemore_link'] )   ? (bool) $instance['show_seemore_link']     : false;
            $seemore_link_text  = isset( $instance['seemore_link_text'] )   ? esc_attr( $instance['seemore_link_text'] ): '';

            if ( empty( $seemore_link_text ) )
                $seemore_link_text = __( 'See more videos...', 'aparss-grad' );

            $aparat_rss_object = simplexml_load_file($aparat_rss_link, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ( $aparat_rss_link != '' and $aparat_rss_object ) {

                echo "\n<!-- Aparat Rss Grad -->\n";
                echo $args['before_widget'];

                if ( $show_channel_tile ) {
                    echo $args['before_title'];
                    echo '<a href="' . esc_url( $aparat_rss_object->channel->link ) . '">' . $aparat_rss_object->channel->title . '</a>';
                    echo $args['after_title'];

                } else if ( $widget_title != '' ) {
                    echo $args['before_title'];
                    echo wp_kses_post( $widget_title );
                    echo $args['after_title'];
                }

                $aparat_rss_videos_count = count($aparat_rss_object->channel->item);
                $rows = ($show_video_num > 0 and $show_video_num < $aparat_rss_videos_count) ? $show_video_num : $aparat_rss_videos_count;

                echo "<ul>\n";
                for ($i=0; $i<$rows; $i++) {
                    echo "<li>";

                    if ($i==0 and $show_first_full) {
                        $video_singel_page = file_get_contents($aparat_rss_object->channel->item[$i]->link);
                        preg_match_all( '!<meta.\S*="(.\S*)".*="(.*)"\s*\/*>!', $video_singel_page, $match );

                        $match_array = array_combine( $match[1], $match[2] );
                        $video_singel_page_json = json_decode($match[1][0]);

                        echo "<video width=\"100%\" controls poster=\"".$match_array['og:image']."\">";
                        echo "<source src=\"".$match_array['og:video']."\" type=\"".$match_array['video_type']."\">";
                        echo __( 'Your browser does not support the video tag.', 'aparss-grad' );
                        echo "</video>";

                    }

                    echo "<a href=\"".$aparat_rss_object->channel->item[$i]->link."\" target=\"_blank\">".$aparat_rss_object->channel->item[$i]->title."</a>";

                    if ( ($i == 0 and $show_first_full) or ($show_upload_date) ) {
                        echo "<div class=\"entry-date\"><i class=\"fa fa-clock-o\"></i>".human_time_diff( strtotime( $aparat_rss_object->channel->item[$i]->pubDate ) + (3.5 * 60 * 60), current_time('U') )." ". __('ago', 'aparss-grad') ."</div>";
                    }

                    echo "</li>\n";
                }
                echo "</ul>\n";

                if ( $show_seemore_link )
                    echo '<div class="read-more"><a href="'.$aparat_rss_object->channel->link.'" target="_blank">'.$seemore_link_text.'</a></div>';

                echo $args['after_widget'];
                echo "\n<!-- / Aparat Rss Grad -->\n";

            }


        }


        /**
        * Short News widget form
        *
        * @param array $instance
        */
        public function form( $instance ) {

            $widget_title       = isset( $instance['widget_title'] )        ? esc_attr( $instance['widget_title'] )     : '';
            $aparat_rss_link    = isset( $instance['aparat_rss_link'] )     ? esc_attr( $instance['aparat_rss_link'] )  : '';
            $show_channel_tile  = isset( $instance['show_channel_tile'] )   ? (bool) $instance['show_channel_tile']     : false;
            $show_video_num     = isset( $instance['show_video_num'] )      ? absint( $instance['show_video_num'] )     : 0;
            $show_first_full    = isset( $instance['show_first_full'] )     ? (bool) $instance['show_first_full']       : false;
            $show_upload_date   = isset( $instance['show_upload_date'] )    ? (bool) $instance['show_upload_date']      : false;

            $show_seemore_link  = isset( $instance['show_seemore_link'] )   ? (bool) $instance['show_seemore_link']     : false;
            $seemore_link_text  = isset( $instance['seemore_link_text'] )   ? esc_attr($instance['seemore_link_text'])  : '';

            ?>

            <p><label for="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>"><?php _e( 'Title:', 'aparss-grad' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_title' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>" /></p>

            <p><label for="<?php echo esc_attr( $this->get_field_id( 'aparat_rss_link' ) ); ?>"><?php _e( 'Aparat Channel RSS Link:', 'aparss-grad' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'aparat_rss_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'aparat_rss_link' ) ); ?>" type="text" value="<?php echo esc_attr( $aparat_rss_link ); ?>" />
            <?php if (isset($instance['aparat_rss_link_error']) and $instance['aparat_rss_link_error']): ?>
            <br/><small style="color:red;"><?php _e( 'Enter true Aparat Rss link.', 'aparss-grad'); ?></small>
            <?php endif; ?></p>

            <p><input class="checkbox" type="checkbox" <?php checked( $show_channel_tile ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_channel_tile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_channel_tile' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_channel_tile' ) ); ?>"><?php _e( 'Show Aparat channel title?', 'aparss-grad' ); ?></label>
            <br/><small><?php _e( 'Replace Channel name with widget title', 'aparss-grad'); ?></small></p>

            <p><label for="<?php echo esc_attr( $this->get_field_id( 'show_video_num' ) ); ?>"><?php _e( 'Number of videos to show:', 'aparss-grad' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_video_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_video_num' ) ); ?>" type="number" min="0" value="<?php echo esc_attr( $show_video_num ); ?>" size="3" />
            <br/><small><?php _e( '0 for showing all videos listed in RSS.', 'aparss-grad'); ?></small></p>

            <p><input class="checkbox" type="checkbox" <?php checked( $show_first_full ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_first_full' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_first_full' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_first_full' ) ); ?>"><?php _e( 'Show full details for first video?', 'aparss-grad' ); ?></label></p>

            <p><input class="checkbox" type="checkbox" <?php checked( $show_upload_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_upload_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_upload_date' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_upload_date' ) ); ?>"><?php _e( 'Show uploading all videos date?', 'aparss-grad' ); ?></label></p>

            <hr>

            <p><input class="checkbox" type="checkbox" <?php checked( $show_seemore_link ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_seemore_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_seemore_link' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_seemore_link' ) ); ?>"><?php _e( 'Show Aparat channel link?', 'aparss-grad' ); ?></label></p>

            <p><label for="<?php echo esc_attr( $this->get_field_id( 'seemore_link_text' ) ); ?>"><?php _e( 'Aparat channel link text:', 'aparss-grad' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'seemore_link_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'seemore_link_text' ) ); ?>" placeholder="<?php _e( 'See more videos...', 'aparss-grad' ); ?>" type="text" value="<?php echo esc_attr( $seemore_link_text ); ?>" /></p>

            <?php
        }

        public function update( $new_instance, $old_instance ) {

            $instance = array();
            $instance = $new_instance;
            $aparat_rss_object = simplexml_load_file($new_instance['aparat_rss_link'], 'SimpleXMLElement', LIBXML_NOCDATA);
            $instance['aparat_rss_link_error'] = ( !$aparat_rss_object ) ? true : false;

            $instance['aparat_rss_link'] =  ( ! empty( $new_instance['aparat_rss_link'] ) and $aparat_rss_object  ) ? $new_instance['aparat_rss_link'] : '';

            return $instance;

        }

    }

}





?>
