<?php


namespace Doula_Course\App\Tmpl;

if ( ! defined( 'ABSPATH' ) ) { exit; }

//	Custom Template: Course Section
// 	This is not a standard Wordpress template, but is just being called via a php includes statement located in the doula-course plugin.

global $post, $page_title;

?>
	
		<div class="course-nav top">
			<?php course_nav_bar();?>
		</div><!-- end .course-nav top -->
		
		<div <?php post_class(); ?> > 
			<div class="post-entry">
				
				<?php 
				if( empty( $page_title ) || strcmp( $post->post_title, $page_title ) !== 0  ){
					echo"<h2>{$post->post_title}</h2>"; 
				}
				?>
				<?php 
				if( !empty( $post->post_content ) )	
					the_content(__('Read more &#8250;', 'responsive')); ?>
			
				<ul id="section-list">
					<?php wp_list_pages($list_args); ?>
				</ul>  
			</div><!-- end of .post-entry -->
		</div><!-- end of #post-<?php the_ID(); ?> -->   
		

		<div class="course-nav btm">
			<?php course_nav_bar();?>
		</div><!-- end .course-nav btm -->
