<?php
/**
 * Boilerplate functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, boilerplate_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'boilerplate_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

if ( ! function_exists( 'boilerplate_setup' ) ):
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override boilerplate_setup() in a child theme, add your own boilerplate_setup to your child theme's
	 * functions.php file.
	 *
	 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
	 * @uses register_nav_menus() To add support for navigation menus.
	 * @uses add_custom_background() To add support for a custom background.
	 * @uses add_editor_style() To style the visual editor.
	 * @uses load_theme_textdomain() For translation/localization support.
	 * @uses add_theme_support()/add_custom_image_header() To add support for a custom header.
	 * @uses register_default_headers() To register the default custom header images provided with the theme.
	 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_setup() {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Uncomment if you choose to use post thumbnails; add the_post_thumbnail() wherever thumbnail should appear
		//add_theme_support( 'post-thumbnails' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'boilerplate', get_template_directory() . '/languages' );

		$locale = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'boilerplate' ),
		) );

		// This theme allows users to set a custom background
		// add_custom_background was deprecated as of 3.4, so testing for existence, but keeping add_custom_background for backward-compatibility
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'custom-background' );
		} else {
			add_custom_background();
		}

		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '' );
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to boilerplate_header_image_width and boilerplate_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'boilerplate_header_image_width', 940 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'boilerplate_header_image_height', 198 ) );

		// We'll be using post thumbnails for custom header images on posts and pages.
		// We want them to be 940 pixels wide by 198 pixels tall.
		// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', true );

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See boilerplate_admin_header_style(), below.
		// add_custom_image_header was deprecated as of 3.4, so testing for existence, but keeping add_custom_image_header for backward-compatibility
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'custom-header' );

		} else {
			add_custom_image_header( '', 'boilerplate_admin_header_style' );
		}

		// ... and thus ends the changeable header business.

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'berries' => array(
				'url' => '%s/images/headers/starkers.png',
				'thumbnail_url' => '%s/images/headers/starkers-thumbnail.png',
				/* translators: header image description */
				'description' => __( 'Boilerplate', 'boilerplate' )
			)
		) );
	}
endif;
add_action( 'after_setup_theme', 'boilerplate_setup' );

if ( ! function_exists( 'boilerplate_admin_header_style' ) ) :
	/**
	 * Styles the header image displayed on the Appearance > Header admin panel.
	 *
	 * Referenced via add_theme_support()/add_custom_image_header() in boilerplate_setup().
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_admin_header_style() {
	?>
	<style type="text/css">
	/* Shows the same border as on front end */
	#headimg {
		border-bottom: 1px solid #000;
		border-top: 4px solid #000;
	}
	/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
		#headimg #name { }
		#headimg #desc { }
	*/
	</style>
	<?php
	}
endif;

if ( ! function_exists( 'boilerplate_filter_wp_title' ) ) :
	/**
	 * Makes some changes to the <title> tag, by filtering the output of wp_title().
	 *
	 * If we have a site description and we're viewing the home page or a blog posts
	 * page (when using a static front page), then we will add the site description.
	 *
	 * If we're viewing a search result, then we're going to recreate the title entirely.
	 * We're going to add page numbers to all titles as well, to the middle of a search
	 * result title and the end of all other titles.
	 *
	 * The site title also gets added to all titles.
	 *
	 * @since Twenty Ten 1.0
	 *
	 * @param string $title Title generated by wp_title()
	 * @param string $separator The separator passed to wp_title(). Twenty Ten uses a
	 * 	vertical bar, "|", as a separator in header.php.
	 * @return string The new title, ready for the <title> tag.
	 */
	function boilerplate_filter_wp_title( $title, $separator ) {
		// Don't affect wp_title() calls in feeds.
		if ( is_feed() )
			return $title;

		// The $paged global variable contains the page number of a listing of posts.
		// The $page global variable contains the page number of a single post that is paged.
		// We'll display whichever one applies, if we're not looking at the first page.
		global $paged, $page;

		if ( is_search() ) {
			// If we're a search, let's start over:
			$title = sprintf( __( 'Search results for %s', 'boilerplate' ), '"' . get_search_query() . '"' );
			// Add a page number if we're on page 2 or more:
			if ( $paged >= 2 )
				$title .= " $separator " . sprintf( __( 'Page %s', 'boilerplate' ), $paged );
			// Add the site name to the end:
			$title .= " $separator " . get_bloginfo( 'name', 'display' );
			// We're done. Let's send the new title back to wp_title():
			return $title;
		}

		// Otherwise, let's start by adding the site name to the end:
		$title .= get_bloginfo( 'name', 'display' );

		// If we have a site description and we're on the home/front page, add the description:
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " $separator " . $site_description;

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $separator " . sprintf( __( 'Page %s', 'boilerplate' ), max( $paged, $page ) );

		// Return the new title to wp_title():
		return $title;
	}
endif;
add_filter( 'wp_title', 'boilerplate_filter_wp_title', 10, 2 );

if ( ! function_exists( 'boilerplate_page_menu_args' ) ) :
	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * To override this in a child theme, remove the filter and optionally add
	 * your own function tied to the wp_page_menu_args filter hook.
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
endif;
add_filter( 'wp_page_menu_args', 'boilerplate_page_menu_args' );

if ( ! function_exists( 'boilerplate_excerpt_length' ) ) :
	/**
	* Sets the post excerpt length to 40 characters.
	*
	* To override this length in a child theme, remove the filter and add your own
	* function tied to the excerpt_length filter hook.
	*
	* @since Twenty Ten 1.0
	* @return int
	*/
   function boilerplate_excerpt_length( $length ) {
	   return 40;
   }
endif;
add_filter( 'excerpt_length', 'boilerplate_excerpt_length' );

if ( ! function_exists( 'boilerplate_continue_reading_link' ) ) :
	/**
	 * Returns a "Continue Reading" link for excerpts
	 *
	 * @since Twenty Ten 1.0
	 * @return string "Continue Reading" link
	 */
	function boilerplate_continue_reading_link() {
		return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'boilerplate' ) . '</a>';
	}
endif;

if ( ! function_exists( 'boilerplate_auto_excerpt_more' ) ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and boilerplate_continue_reading_link().
	 *
	 * To override this in a child theme, remove the filter and add your own
	 * function tied to the excerpt_more filter hook.
	 *
	 * @since Twenty Ten 1.0
	 * @return string An ellipsis
	 */
	function boilerplate_auto_excerpt_more( $more ) {
		return ' &hellip;' . boilerplate_continue_reading_link();
	}
endif;
add_filter( 'excerpt_more', 'boilerplate_auto_excerpt_more' );

if ( ! function_exists( 'boilerplate_custom_excerpt_more' ) ) :
	/**
	 * Adds a pretty "Continue Reading" link to custom post excerpts.
	 *
	 * To override this link in a child theme, remove the filter and add your own
	 * function tied to the get_the_excerpt filter hook.
	 *
	 * @since Twenty Ten 1.0
	 * @return string Excerpt with a pretty "Continue Reading" link
	 */
	function boilerplate_custom_excerpt_more( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$output .= boilerplate_continue_reading_link();
		}
		return $output;
	}
endif;
add_filter( 'get_the_excerpt', 'boilerplate_custom_excerpt_more' );

if ( ! function_exists( 'boilerplate_remove_gallery_css' ) ) :/**
	/**
	 * Remove inline styles printed when the gallery shortcode is used.
	 *
	 * Galleries are styled by the theme in Twenty Ten's style.css.
	 *
	 * @since Twenty Ten 1.0
	 * @return string The gallery style filter, with the styles themselves removed.
	 */
	function boilerplate_remove_gallery_css( $css ) {
		return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
	}
endif;
add_filter( 'gallery_style', 'boilerplate_remove_gallery_css' );

if ( ! function_exists( 'boilerplate_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own boilerplate_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '' :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>">
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'boilerplate' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'boilerplate' ); ?></em>
					<br />
				<?php endif; ?>
				<footer class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'boilerplate' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'boilerplate' ), ' ' );
					?>
				</footer><!-- .comment-meta .commentmetadata -->
				<div class="comment-body"><?php comment_text(); ?></div>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
			</article><!-- #comment-##  -->
		<?php
				break;
			case 'pingback'  :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'boilerplate' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'boilerplate'), ' ' ); ?></p>
		<?php
				break;
		endswitch;
	}
endif;

if ( ! function_exists( 'boilerplate_widgets_init' ) ) :
	/**
	 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
	 *
	 * To override boilerplate_widgets_init() in a child theme, remove the action hook and add your own
	 * function tied to the init hook.
	 *
	 * @since Twenty Ten 1.0
	 * @uses register_sidebar
	 */
	function boilerplate_widgets_init() {
		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
			'name' => __( 'Primary Widget Area', 'boilerplate' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The primary widget area', 'boilerplate' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
		register_sidebar( array(
			'name' => __( 'Secondary Widget Area', 'boilerplate' ),
			'id' => 'secondary-widget-area',
			'description' => __( 'The secondary widget area', 'boilerplate' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 3, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'First Footer Widget Area', 'boilerplate' ),
			'id' => 'first-footer-widget-area',
			'description' => __( 'The first footer widget area', 'boilerplate' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 4, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Second Footer Widget Area', 'boilerplate' ),
			'id' => 'second-footer-widget-area',
			'description' => __( 'The second footer widget area', 'boilerplate' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 5, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Third Footer Widget Area', 'boilerplate' ),
			'id' => 'third-footer-widget-area',
			'description' => __( 'The third footer widget area', 'boilerplate' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		// Area 6, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Fourth Footer Widget Area', 'boilerplate' ),
			'id' => 'fourth-footer-widget-area',
			'description' => __( 'The fourth footer widget area', 'boilerplate' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}
endif;
add_action( 'widgets_init', 'boilerplate_widgets_init' );

if ( ! function_exists( 'boilerplate_remove_recent_comments_style' ) ) :
	/**
	 * Removes the default styles that are packaged with the Recent Comments widget.
	 *
	 * To override this in a child theme, remove the filter and optionally add your own
	 * function tied to the widgets_init action hook.
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_remove_recent_comments_style() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
endif;
add_action( 'widgets_init', 'boilerplate_remove_recent_comments_style' );

if ( ! function_exists( 'boilerplate_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post—date/time and author.
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_posted_on() {
		// BP: slight modification to Twenty Ten function, converting single permalink to multi-archival link
		// Y = 2012
		// F = September
		// m = 01–12
		// j = 1–31
		// d = 01–31
		printf( __( '<span class="%1$s">Posted on</span> <span class="entry-date">%2$s %3$s %4$s</span> <span class="meta-sep">by</span> %5$s', 'boilerplate' ),
			// %1$s = container class
			'meta-prep meta-prep-author',
			// %2$s = month: /yyyy/mm/
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
				home_url() . '/' . get_the_date( 'Y' ) . '/' . get_the_date( 'm' ) . '/',
				esc_attr( 'View Archives for ' . get_the_date( 'F' ) . ' ' . get_the_date( 'Y' ) ),
				get_the_date( 'F' )
			),
			// %3$s = day: /yyyy/mm/dd/
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
				home_url() . '/' . get_the_date( 'Y' ) . '/' . get_the_date( 'm' ) . '/' . get_the_date( 'd' ) . '/',
				esc_attr( 'View Archives for ' . get_the_date( 'F' ) . ' ' . get_the_date( 'j' ) . ' ' . get_the_date( 'Y' ) ),
				get_the_date( 'j' )
			),
			// %4$s = year: /yyyy/
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
				home_url() . '/' . get_the_date( 'Y' ) . '/',
				esc_attr( 'View Archives for ' . get_the_date( 'Y' ) ),
				get_the_date( 'Y' )
			),
			// %5$s = author vcard
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				sprintf( esc_attr__( 'View all posts by %s', 'boilerplate' ), get_the_author() ),
				get_the_author()
			)
		);
	}
endif;

if ( ! function_exists( 'boilerplate_posted_in' ) ) :
	/**
	 * Prints HTML with meta information for the current post (category, tags and permalink).
	 *
	 * @since Twenty Ten 1.0
	 */
	function boilerplate_posted_in() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list( '', ', ' );
		if ( $tag_list ) {
			$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'boilerplate' );
		} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
			$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'boilerplate' );
		} else {
			$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'boilerplate' );
		}
		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list( ', ' ),
			$tag_list,
			get_permalink(),
			the_title_attribute( 'echo=0' )
		);
	}
endif;
/*	End original TwentyTen functions (from Starkers Theme, renamed into this namespace) */

/*	Begin Boilerplate */
	// Add Admin
	require_once(get_template_directory() . '/boilerplate-admin/admin-menu.php');

	// remove version info from head and feeds (http://digwp.com/2009/07/remove-wordpress-version-number/)
	if ( ! function_exists( 'boilerplate_complete_version_removal' ) ) :
		function boilerplate_complete_version_removal() {
			return '';
		}
	endif;
	add_filter('the_generator', 'boilerplate_complete_version_removal');

	// add thumbnail support
	if ( function_exists( 'add_theme_support' ) ) :
		add_theme_support( 'post-thumbnails' );
	endif;

/**
 * Change default fields, add placeholder and change type attributes.
 * @param  array $fields
 * @return array
 * from: http://wordpress.stackexchange.com/questions/62742/add-placeholder-attribute-to-comment-form-fields
 */
	function boilerplate_comment_input_placeholders( $fields ) {
		$fields['author'] = str_replace(
			'<input',
			'<input placeholder="'
			/* Replace 'theme_text_domain' with your theme’s text domain.
			 * I use _x() here to make your translators life easier. :)
			 * See http://codex.wordpress.org/Function_Reference/_x
			 */
				. _x(
					'Your Name',
					'comment form placeholder',
					'boilerplate'
					)
				. '"',
			$fields['author']
		);
		$fields['email'] = str_replace(
			'<input id="email" name="email" type="text"',
			/* We use a proper type attribute to make use of the browser’s
			 * validation, and to get the matching keyboard on smartphones.
			 */
			'<input type="email" placeholder="contact@example.com"  id="email" name="email"',
			$fields['email']
		);
		$fields['url'] = str_replace(
			'<input id="url" name="url" type="text"',
			// Again: a better 'type' attribute value.
			'<input placeholder="http://example.com/" id="url" name="url" type="url"',
			$fields['url']
		);
		return $fields;
	}
	add_filter( 'comment_form_default_fields', 'boilerplate_comment_input_placeholders' );
	// ATG: added to customize <textarea> also
	function boilerplate_comment_field_placeholder( $fields ) {
		$fields = str_replace(
			'<textarea',
			'<textarea placeholder="'
			/* Replace 'theme_text_domain' with your theme’s text domain.
			 * I use _x() here to make your translators life easier. :)
			 * See http://codex.wordpress.org/Function_Reference/_x
			 */
				. _x(
					'Your Comment',
					'comment form placeholder',
					'boilerplate'
					)
				. '"',
			$fields
		);
		return $fields;
	}
	add_filter( 'comment_form_field_comment', 'boilerplate_comment_field_placeholder' );

/*	End Boilerplate */

/*--------------------------------------Change Excerpt Link------------------------------*/


function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">&hellip; <br>Read More</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );



/*--------------------------------------CUSTOM POST TYPES FOR BIZ HIGHTLIGHT AND MONTHLY SPECIAL------------------------------*/




/*--------------------------------------BIZ HIGHTLIGHT------------------------------*/
add_action( 'init', 'my_biz_highlight' );
function my_biz_highlight() {
	register_post_type( 'biz_highlight',
		array(
			'labels' => array(
				'name' => __( 'Biz Highlight' ),
				'singular_name' => __( 'Highlight' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'biz_highlight'),
		)
	);
	
	add_post_type_support( 'biz_highlight', 'thumbnail');
}

add_action("admin_init", "admin_init");

function admin_init() {
	add_meta_box("biz_info", "Basic Biz Info", "biz_info", "biz_highlight", "normal", "high");
	add_meta_box("digital_info", "Digital Info", "digital_info", "biz_highlight", "normal", "high");
	//add_meta_box("social_info", "Social Media Info", "social_info", "biz_highlight", "normal", "high");
}

function biz_info() {
	global $post;
	$custom = get_post_custom($post->ID);
	$hours = $custom["hours"][0];
	$address = $custom["address"][0];
	$phone = $custom["phone"][0];
	
	echo '<input type="text" value="' . $address . '" name="address" placeholder="Street Address" />';
	echo '<input type="tel" value="' . $phone . '" name="phone" placeholder="Phone Number" />';
	echo '<input type="text" value="' .$hours . '" name="hours" placeholder="Hours of Operation"/>';
}

function digital_info() {
	global $post;
	$custom = get_post_custom($post->ID);
	
	$url = $custom["url"][0];
	$email = $custom["email"][0];
	
	
	echo '<input type="email" value="' .$email . '" name="email" placeholder="E-mail" />';
	echo '<input type="text" value="' .$url . '" name="url" placeholder="Website" />';
}
/*
function social_info() {
	global $post;
	$custom = get_post_custom($post->ID);
	
	$fb = $custom["fb"][0];
	$tw = $custom["tw"][0];
	$google = $custom["google"][0];
	$ln = $custom["ln"][0];	
	
	echo '<input type="text" value="' .$fb . '" name="fb" placeholder="http://facebook.com/myPageName/" />';
	echo '<input type="text" value="' .$tw . '" name="tw" placeholder="http://twitter.com/myProfileName/" />';
	echo '<input type="text" value="' .$ln . '" name="ln" placeholder="http://linkedin.com/myProfile/" />';
	echo '<input type="text" value="' .$google . '" name="google" placeholder="http://google.com/myAccountNumber/" />';
}
*/
add_action('save_post', 'save_details');
function save_details() {
	global $post;
	
	update_post_meta($post->ID, "hours", $_POST["hours"]);
	update_post_meta($post->ID, "address", $_POST["address"]);
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "url", $_POST["url"]);
	update_post_meta($post->ID, "email", $_POST["email"]);
	update_post_meta($post->ID, "fb", $_POST["fb"]);
	update_post_meta($post->ID, "tw", $_POST["tw"]);
	update_post_meta($post->ID, "google", $_POST["google"]);
	update_post_meta($post->ID, "ln", $_POST["ln"]);
}



/*--------------------------------------Monthly Special------------------------------*/
add_action( 'init', 'my_monthly_special' );
function my_monthly_special() {
	register_post_type( 'monthly_special',
		array(
			'labels' => array(
				'name' => __( 'Monthly Special' ),
				'singular_name' => __( 'monthly' )
				),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array( 'slug' => 'monthly_special' ),
		)
	);
	add_post_type_support( 'monthly_special', 'thumbnail');
}

add_action( 'admin_init', 'special_init' );
function special_init() {
	add_meta_box("the_deal", "Your Deal", "the_deal", "monthly_special", "normal", "high");
	add_meta_box("biz_info", "Basic Biz Info", "biz_info", "monthly_special", "normal", "high");
	add_meta_box("digital_info", "Digital Info", "digital_info", "monthly_special", "normal", "high");
	//add_meta_box("social_info", "Social Media Info", "social_info", "monthly_special", "normal", "high");
}

function the_deal() {
	global $post;
	
	$monthly = get_post_custom($post->ID);
	$deal = $monthly["deal"][0];
	echo('<textarea rows="10" cols="73" name="deal">' . $deal . '</textarea>');
}

add_action('save_post', 'save_deal');
function save_deal() {
	global $post;
	
	update_post_meta($post->ID, "hours", $_POST["hours"]);
	update_post_meta($post->ID, "address", $_POST["address"]);
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "url", $_POST["url"]);
	update_post_meta($post->ID, "email", $_POST["email"]);
	update_post_meta($post->ID, "fb", $_POST["fb"]);
	update_post_meta($post->ID, "tw", $_POST["tw"]);
	update_post_meta($post->ID, "google", $_POST["google"]);
	update_post_meta($post->ID, "ln", $_POST["ln"]);
	update_post_meta($post->ID, "deal", $_POST["deal"]);
}


/*--------------------------------------Eat------------------------------*/

add_action( 'init', 'eat' );
function eat() {
	register_post_type ( 'eat_listing', 
		array(
			'labels' => array (
				'name' => __( 'Eat' ),
				'singular_name' => __( 'eat' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array ( 'slug' => 'eat' ),
		)
	);
	add_post_type_support( 'eat_listing', 'thumbnail' );
}

add_action( 'admin_init', 'the_listing' );
function the_listing() {
	add_meta_box("my_listing", "Store Details", "my_listing", "eat_listing", "normal", "high");
	add_meta_box("my_listing", "Store Details", "my_listing", "play_listing", "normal", "high");
	add_meta_box("my_listing", "Store Details", "my_listing", "shop_listing", "normal", "high");
	add_meta_box("my_listing", "Store Details", "my_listing", "stay_listing", "normal", "high");
	add_meta_box("my_listing", "Store Details", "my_listing", "live_listing", "normal", "high");
	add_meta_box("my_listing", "Store Details", "my_listing", "work_listing", "normal", "high");
	
}

function my_listing() {
	global $post;
	
	$listing = get_post_custom($post->ID);
	$phone = $listing['phone'][0];
	$street = $listing['street'][0];
	$site = $listing['site'][0];
	
	echo ('<label for"phone">Phone:</label><br><input type="text" value="'.$phone.'" name="phone" placeholder="Enter Phone" /><br>');
	echo ('<label for"street">Street:</label><br><input type="text" value="'.$street.'" name="street" placeholder="Enter Street" /><br>');
	echo ('<label for"site">Website:</label><br><input type="text" value="'.$site.'" name="site" placeholder="Enter Website" /><br>');
}

add_action('save_post', 'save_eat');
function save_eat() {
	global $post;
	
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "street", $_POST["street"]);
	update_post_meta($post->ID, "site", $_POST["site"]);
}

/*--------------------------------------Play------------------------------*/
add_action( 'init', 'play' );
function play() {
	register_post_type ( 'play_listing', 
		array(
			'labels' => array (
				'name' => __( 'Play' ),
				'singular_name' => __( 'play' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array ( 'slug' => 'play' ),
		)
	);
	add_post_type_support( 'play_listing', 'thumbnail' );
}

add_action('save_post', 'save_play');
function save_play() {
	global $post;
	
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "street", $_POST["street"]);
	update_post_meta($post->ID, "site", $_POST["site"]);
}
/*--------------------------------------Shop------------------------------*/
add_action( 'init', 'shop' );
function shop() {
	register_post_type ( 'shop_listing', 
		array(
			'labels' => array (
				'name' => __( 'Shop' ),
				'singular_name' => __( 'shop' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array ( 'slug' => 'shop' ),
		)
	);
	add_post_type_support( 'shop_listing', 'thumbnail' );
}

add_action('save_post', 'save_shop');
function save_shop() {
	global $post;
	
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "street", $_POST["street"]);
	update_post_meta($post->ID, "site", $_POST["site"]);
}
/*--------------------------------------Stay------------------------------*/
add_action( 'init', 'stay' );
function stay() {
	register_post_type ( 'stay_listing', 
		array(
			'labels' => array (
				'name' => __( 'Stay' ),
				'singular_name' => __( 'stay' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array ( 'slug' => 'stay' ),
		)
	);
	add_post_type_support( 'stay_listing', 'thumbnail' );
}

add_action('save_post', 'save_stay');
function save_stay() {
	global $post;
	
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "street", $_POST["street"]);
	update_post_meta($post->ID, "site", $_POST["site"]);
}
/*--------------------------------------Live------------------------------*/
add_action( 'init', 'live' );
function live() {
	register_post_type ( 'live_listing', 
		array(
			'labels' => array (
				'name' => __( 'Live' ),
				'singular_name' => __( 'live' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array ( 'slug' => 'live' ),
		)
	);
	add_post_type_support( 'live_listing', 'thumbnail' );
}

add_action('save_post', 'save_live');
function save_live() {
	global $post;
	
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "street", $_POST["street"]);
	update_post_meta($post->ID, "site", $_POST["site"]);
}

/*--------------------------------------Work------------------------------*/
add_action( 'init', 'work' );
function work() {
	register_post_type ( 'work_listing', 
		array(
			'labels' => array (
				'name' => __( 'Work' ),
				'singular_name' => __( 'work' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array ( 'slug' => 'work' ),
		)
	);
	add_post_type_support( 'work_listing', 'thumbnail' );
}

add_action('save_post', 'save_work');
function save_work() {
	global $post;
	
	update_post_meta($post->ID, "phone", $_POST["phone"]);
	update_post_meta($post->ID, "street", $_POST["street"]);
	update_post_meta($post->ID, "site", $_POST["site"]);
}

add_action( 'wp_print_scripts', 'de_script', 100 );
function de_script() {
    wp_dequeue_script( 'jquery' );
    wp_deregister_script( 'jquery' );
}

?>