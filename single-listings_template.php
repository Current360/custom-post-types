<?php
/**
 * Template Name: Eat, Play, Shop, Stay, Live, & Work listings
 */

 	get_header();

	// GET ALL THE CUSTOM META DATA FROM THE DB AND SAVE THEM TO VARS
	global $post;

	if ( is_page('eat') ) { $myid ="eatHero"; $postType = "eat_listing"; }
	else if ( is_page('play') ) { $myid ="playHero"; $postType = "play_listing"; }
	else if ( is_page('shop') ) { $myid ="shopHero"; $postType = "shop_listing"; }
	else if ( is_page('stay') ) { $myid ="stayHero"; $postType = "stay_listing"; }
	else if ( is_page('live') ) { $myid ="liveHero"; $postType = "live_listing"; }
	else if ( is_page('work') ) { $myid ="workHero"; $postType = "work_listing"; }

?>
<div class="row">
	<section class="intContent small-12 large-9 columns">

		<section class="intOverview" id="<?php echo $myid; ?>">
			<?php
				global $post;
				if( $post->post_parent ) { ?>
				  <h1 class="intOverviewHeading">
				  	<a href="<?php get_permalink( $post->post_parent ); ?>"><?php echo get_the_title( $post->post_parent ); ?>:</a>
				  	<a href="<?php the_permalink();?>"><?php the_title(); ?></a>
				<?php } else { ?>
				  <h1 class="intOverviewHeading"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h1>
				<?php } ?>
		</section>
			<?php
				//WordPress loop for custom post type
				 $my_query = new WP_Query(array('post_type'=>$postType, 'posts_per_page' => -1, 'orderby'=> 'title', 'order' => 'ASC'));
				 while ($my_query->have_posts()) : $my_query->the_post();



				 //Set the values here instead of in the header
				 //Because this way it is associated to the queried post and not the parent page
				 $site = get_post_meta($post->ID, "site", true);
				 $phone = get_post_meta($post->ID, "phone", true);
				 $street = get_post_meta($post->ID, "street", true);
				 ?>

				 <section class="large-12 columns attraction">
				        <figure>

				        	<?php if ( has_post_thumbnail() ) {
								echo get_the_post_thumbnail($post->ID, array(173,173), array('class' => 'listingThumb'));
							} else {
								echo ('<img src="http://placehold.it/173x173" />');
							} ?>
				        </figure>
				        <h3><a href="http://<?php echo $site; ?>" target="_BLANK"><?php the_title(); ?></a></h3>
				        <p><?php the_content(); ?></p>
				        <sub><a href="tel: <?php echo $phone; ?>"><?php echo $phone; ?></a> | <?php echo $street; ?> | <a target="_blank" href="http://<?php echo $site; ?>"><?php echo $site; ?></a></sub>
				 </section>
		<?php endwhile;  wp_reset_query(); ?>



	</section>
	<aside class="intSidebar large-3 columns">
		<?php get_sidebar(); ?>
	</aside>
</div>
<div class="darkGrey intFooter">
		<span></span>
		<section class="row">
			<ul class="large-block-grid-3 threeCols">
				<li><a class="whatToDo" href="<?php bloginfo(siteurl);?>/what-to-do/eat/">What to Do</a></li>
				<li><a class="aboutNulu" href="<?php bloginfo(siteurl);?>/about-nulu/what-is-nulu/">About Nulu</a></li>
				<li><a class="sEvents" href="<?php bloginfo(siteurl);?>/events/nulu-fest/">Special Events</a></li>
			</ul>

			<div class="colsContent">
				<span id="leftWing"></span>
				<span id="rightWing"></span>
				<p class="switchContent">Hover over each badge to get a preview. <br />Click the badge to learn more!</p>
				<p class="whatContent hide" id="whatToDo">Art, fashion, food &amp; lofts. Come, eat, be merry &hellip; then stay!</p>
				<p class="aboutContent hide" id="aboutNulu">The term NuLu is a portmanteau meaning New Louisville. As home to the greenest commercial building in Kentucky, many historic restoration projects, as well as &hellip;</p>
				<p class="eventsContent hide" id="sEvents">First Friday Trolley Hop, NuLu Fest, Pop Up Friday's &hellip; What more could you want? How about Special Events that are fun for all ages!</p>
			</div>

		</section>
	</div>
<?php get_footer(); ?>
