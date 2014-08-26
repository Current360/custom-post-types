<?php
/**
 * Template Name: Monthly Special Single
 */

get_header(); ?>
<?php 
// GET ALL THE CUSTOM META DATA FROM THE DB AND SAVE THEM TO VARS


$args = array( 'post_type' => 'monthly_special', 'posts_per_page' => 1 );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post(); 
global $post;
$address = get_post_meta($post->ID, "address", true);
$phone = get_post_meta($post->ID, "phone", true);
$hours = get_post_meta($post->ID, "hours", true);

$url = get_post_meta($post->ID, "url", true);
$email = get_post_meta($post->ID, "email", true);


?>


	<div class="row">
		<section class="large-12 columns">
			<figure class="bizMainPic">
				<?php 
					if ( has_post_thumbnail() ) {
						$thumb_id = get_post_thumbnail_id();
						$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
					}
					else "No Thumbnail";
					
				?>
				<img 
					alt="<?php the_title();?> Featured Image" 
					class="bizHighlightFeatureImage" 
					src="<?php echo $thumb_url[0]; ?>" 
					title="<?php the_title();?> Featured Image"
				/>
				<figcaption>
					<span class="headline">Monthly Special</span>				
					<?php $date = date("F Y"); ?>
					<span class="date"> <?php echo($date);?> </span>
					<span class="bizHighlightCaptionTitle"><?php the_title(); ?></span>
				</figcaption>
			</figure>
		</section>
	</div>
	<div class="row">
		<aside class="small-12 large-4 columns bizHighlightSidebar">
			<ul class="bizHighlight411">
				<li id="address"><?php echo $address; ?></li>
				<li id="phone"><a href="tel:<?php echo $phone;?>"><?php echo $phone; ?></a></li>
				<li id="hours"><?php echo $hours; ?></li>
				<li><?php echo $fb; ?></li>
				<li><?php echo $tw; ?></li>
			</ul>
		</aside>
		
		<article id="post-<?php the_ID(); ?>" class="small-12 large-8 columns bizHighlightContent">
			<h1><?php the_title(); ?></h1>
			<section class="large-12 columns">
				<ul class="large-block-grid-2 media">
					<li><a href="<?php echo($url); ?>" target="blank"><?php echo($url); ?></a></li>
					<li><a id="mediaEmail" href="mailto:<?php echo $email;?>"><?php echo $email; ?></a></li>
				</ul>
			</section>
			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->
			
		</article><!-- #post-## -->
<?php endwhile; // end of the loop. ?>
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
