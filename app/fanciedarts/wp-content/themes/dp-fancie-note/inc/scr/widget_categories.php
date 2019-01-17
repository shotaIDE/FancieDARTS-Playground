<?php
/****************************************************************
* Original category widget source
****************************************************************/
class DP_Category_Walker extends Walker_Category {
    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr( $category->name ),
            $category
        );

        $link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
        if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
            /**
             * Filter the category description for display.
             *
             * @since 1.2.0
             *
             * @param string $description Category description.
             * @param object $category    Category object.
             */
            $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
        }

        $link .= '>';
        $link .= $cat_name;

        if ( ! empty( $args['show_count'] ) ) {
            $link .= ' <span class="count">' . number_format_i18n( $category->count ) . '</span>';
        }

        $link .= '</a>';

        if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
            $link .= ' ';

            if ( empty( $args['feed_image'] ) ) {
                $link .= '(';
            }

            $link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

            if ( empty( $args['feed'] ) ) {
                $alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s','DigiPress' ), $cat_name ) . '"';
            } else {
                $alt = ' alt="' . $args['feed'] . '"';
                $name = $args['feed'];
                $link .= empty( $args['title'] ) ? '' : $args['title'];
            }

            $link .= '>';

            if ( empty( $args['feed_image'] ) ) {
                $link .= $name;
            } else {
                $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
            }
            $link .= '</a>';

            if ( empty( $args['feed_image'] ) ) {
                $link .= ')';
            }
        }

        if ( 'list' == $args['style'] ) {
            $output .= "\t<li";
            $class = 'cat-item cat-item-' . $category->term_id;
            if ( ! empty( $args['current_category'] ) ) {
                $_current_category = get_term( $args['current_category'], $category->taxonomy );
                if ( $category->term_id == $args['current_category'] ) {
                    $class .=  ' current-cat';
                } elseif ( $category->term_id == $_current_category->parent ) {
                    $class .=  ' current-cat-parent';
                }
            } else if ( is_single() ) {
                $dp_cats = get_the_category();
                if ( is_array($dp_cats) && isset($dp_cats[0]) ) {
                    if ( $dp_cats[0]->term_id == $category->term_id ) {
                        $class .= ' current-cat';
                    }
                }
            }
            $output .=  ' class="' . $class . '"';
            $output .= ">$link\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}


/****************************************************************
* Override default widget categories
****************************************************************/
function dp_override_widget_categories() {
    class DP_Widget_Categories extends WP_Widget_Categories {
        private $taxonomy = 'category';
        private $exclude_cat = '';
        private $order = 'DESC';
        private $orderby = 'name';

        public function widget( $args, $instance ) {
            if ( !empty( $instance['taxonomy'] ) ) {
                $this->taxonomy = $instance['taxonomy'];
            }
            if ($this->taxonomy !== 'category') {
                add_filter( 'widget_categories_dropdown_args', array( $this, 'orderby_count' ), 10 );
                add_filter( 'widget_categories_args', array( $this, 'orderby_count' ), 10 );
            }

            $this->orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
            $this->order = !empty($instance['order']) ? $instance['order'] : 'DESC';
            $this->exclude_cat = !empty($instance['exclude']) ? implode(',', $instance['exclude']) : '';

            add_filter( 'widget_categories_dropdown_args', array( $this, 'add_taxonomy_dropdown_args' ), 10 );
            add_filter( 'widget_categories_args', array( $this, 'other_args' ), 10 );
            add_filter( 'widget_categories_args', array( $this, 'add_taxonomy_dropdown_args' ), 10 );
            add_filter( 'widget_categories_args', array( $this, 'custom_walker' ), 10 );

            parent::widget( $args, $instance );
        }

        public function update( $new_instance, $old_instance ) {
            $instance = parent::update( $new_instance, $old_instance );
            $taxonomies = $this->get_taxonomies();
            $instance['taxonomy'] = 'category';
            if ( in_array( $new_instance['taxonomy'], $taxonomies ) ) {
                $instance['taxonomy'] = $new_instance['taxonomy'];
            }
            $instance['orderby'] = stripslashes($new_instance['orderby']);
            $instance['order'] = stripslashes($new_instance['order']);
            $instance['exclude'] = explode(',',$new_instance['exclude']);
            return $instance;
        }

        public function form( $instance ) {
            parent::form( $instance );
            $taxonomy = 'category';
            if ( !empty( $instance['taxonomy'] ) ) {
                $taxonomy = $instance['taxonomy'];
            }
            $taxonomies = $this->get_taxonomies();

            $orderby_name = $this->get_field_name('orderby');
            $orderby_id = $this->get_field_id('orderby');
            $current_orderby = $instance['orderby'];
            $order_name = $this->get_field_name('order');
            $order_id   = $this->get_field_id('order');
            $current_order = $instance['order'];
            $exclude_name = $this->get_field_name('exclude');
            $exclude_id   = $this->get_field_id('exclude');
            if (is_array($instance['exclude'])){
                $exclude = implode(',', $instance['exclude']);
            } else {
                $exclude = $instance['exclude'];
            }
            $exclude = strtr($exclude, array(' ' => ''));

            $arr_orderby = array(
                'ID' => __('Order by ID', 'DigiPress'),
                'name' => __('Order by name', 'DigiPress'),
                'slug' => __('Order by slug', 'DigiPress'),
                'count' => __('Order by count', 'DigiPress')
                );
            $arr_order = array(
                'DESC' => __('Descending', 'DigiPress'),
                'ASC' => __('Ascending', 'DigiPress')
                );
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e( 'Taxonomy:', 'DigiPress' ); ?></label>
                <select id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>">
                    <?php foreach ( $taxonomies as $value ) : ?>
                    <option value="<?php echo esc_attr( $value ); ?>"<?php selected( $taxonomy, $value ); ?>><?php echo esc_attr( __($value, 'DigiPress') ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <?php
            // Order by
            printf(
                '<p><label for="%1$s">%2$s</label><select class="widefat" id="%1$s" name="%3$s">',
                $orderby_id,
                __( 'Order By:','DigiPress' ),
                $orderby_name
            );
            foreach ($arr_orderby as $key => $val) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr( $key ),
                    selected( $key, $current_orderby, false ),
                    $val
                );
            }
            echo '</select></p>';

            // Order
            printf(
                '<p><label for="%1$s">%2$s</label><select class="widefat" id="%1$s" name="%3$s">',
                $order_id,
                __( 'Order:','DigiPress' ),
                $order_name
            );
            foreach ($arr_order as $key => $val) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr( $key ),
                    selected( $key, $current_order, false ),
                    $val
                );
            }
            echo '</select></p>';
            // Exclude
            printf(
                '<p><label for="%1$s">%2$s</label><br /><input type="text" class="widefat" id="%1$s" name="%3$s" value="%4$s" /><br /><span style="font-size:11px;">'.__('*Note: Use comma-separated to exclude one or more categories from the results.', 'DigiPress').'</span></p>',
                $exclude_id,
                __( 'Exclude Category ID:','DigiPress' ),
                $exclude_name,
                $exclude
            );
        }

        public function custom_walker( $cat_args ) {
            $walker = new DP_Category_Walker();
            $cat_args = array_merge($cat_args, array('walker' => $walker));
            return $cat_args;
        }

        public function orderby_count( $cat_args ) {
            $cat_args['orderby'] = 'count';
            $cat_args['order'] = 'DESC';
            return $cat_args;
        }

        public function other_args( $cat_args ) {
            $cat_args['order'] = $this->order;
            $cat_args['orderby'] = $this->orderby;
            $cat_args['exclude'] = $this->exclude_cat;
            return $cat_args;
        }

        public function add_taxonomy_dropdown_args( $cat_args ) {
            $cat_args['taxonomy'] = $this->taxonomy;
            return $cat_args;
        }

        private function get_taxonomies() {
            $taxonomies = get_taxonomies( array(
                'public' => true,
            ) );
            return $taxonomies;
        }
    }
    unregister_widget( 'WP_Widget_Categories' );
    register_widget( 'DP_Widget_Categories' );
}
add_action( 'widgets_init', 'dp_override_widget_categories' );