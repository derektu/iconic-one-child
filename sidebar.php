<?php
/**
 * The sidebar containing the main widget area.
 * @package WordPress - Themonic Framework
 * @subpackage Iconic_One
 * @since Iconic One 1.0
 */
?>

<?php


function build_cat_menu() {
    $cat_id = get_query_var( 'cat' );
    $original_cat_id = $cat_id;
    
    /*
        category分成兩層: parent category & child category
        如果目前不是category page, 則顯示parent categories,
        如果目前是category page, 則顯示目前"同一個parent category"的child categories
    */

    if (empty($cat_id)) {
        $cat_id = 0;
    }
    else {
        $category = get_category($cat_id);
        if (empty($category)) {
            $cat_id = 0;
        }
        else {
            if ($category->parent) {
                $cat_id = $category->parent;
            }
        }
    }

    $categories = get_categories(
        array( 'parent' => $cat_id )
    );                    
    
    $output = '';

    $output = $output . '<ul>';

    if ($cat_id == 0) {
        foreach ($categories as $category ) {
            // _echo_log($category->name);    
            $output = $output . build_cat_parent_item($category);
        }    
    }
    else {
        $output = $output . build_cat_parent_item(get_category($cat_id));
        foreach ($categories as $category ) {
            // _echo_log($child_category->name);
            $output = $output . build_cat_child_item($category, $category->cat_ID == $original_cat_id);
        }
    }

    $output = $output . '</ul>';
    
    return $output;
}

function build_cat_parent_item($category) {
/*
    <li class="cat-parent-item cat-item cat-item-12"><a href="http://wp2.local/xsblogs/category/mind/">讀書心得</a></li>
*/
    return sprintf(
        '<li class="cat-item cat-item-%d cat-parent-item"><a href="%s/category/%s/">%s</a></li>',
            $category->cat_ID,
            get_home_url(),
            $category->slug,
            esc_html($category->name)
    );
}

function build_cat_child_item($category, $is_active) {
/*
    <li class="cat-child-item cat-item cat-item-12 cat-item-active"><a href="http://wp2.local/xsblogs/category/mind/">讀書心得</a></li>
*/
    return sprintf(
        '<li class="cat-child-item cat-item cat-item-%d%s"><a href="%s/category/%s/">%s</a></li>',
            $category->cat_ID,
            $is_active ? ' cat-item-active' : '',
            get_home_url(),
            $category->slug,
            esc_html($category->name)
    );
}

?>

	<?php if ( is_active_sidebar( 'themonic-sidebar' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php /*dynamic_sidebar( 'themonic-sidebar' );*/ ?>
			<div class="widget widget_search">
				<?php get_search_form(); ?>
			</div>
            <aside id="categories-2" class="widget widget_categories">
                <?php echo(build_cat_menu()); ?>
            </aside>
		</div><!-- #secondary -->
	<?php else : ?>	
        <div id="secondary" class="widget-area" role="complementary">
			<div class="widget widget_search">
				<?php get_search_form(); ?>
			</div>
			<div class="widget widget_pages">
			    <p class="widget-title"><?php _e( 'Pages', 'themonic' ); ?></p>
                <ul><?php wp_list_pages('title_li='); ?></ul>
            </div>
	        <div class="widget widget_tag_cloud">
                <p class="widget-title"><?php _e( 'Tag Cloud', 'themonic' ); ?></p>
                <?php wp_tag_cloud('smallest=10&largest=20&number=30&unit=px&format=flat&orderby=name'); ?>
			</div>
		</div><!-- #secondary -->
	<?php endif; ?>