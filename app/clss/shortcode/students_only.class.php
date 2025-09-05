<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Students-only Features Shortcodes Class
 *
 * 	
 *
 * 
 */

class Students_Only{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL, $handler ){
		
		ob_start();
		if( nb_role_is( 'student' ) )
		{
			echo '
				<div class="home_calltoaction home-margin clearfix home-padding">
					<div class="kt-home-call-to-action panel-row-style-wide-feature" style="padding-left: 184px; padding-right: 184px; margin-left: -184px; margin-right: -184px; visibility: visible;">
						<div class="call-container clearfix">
							<div class="kt-cta">
								<div class="col-md-10 kad-call-title-case">
									<h1 class="kad-call-title">New Student Guide</h1>				</div>
								<div class="col-md-2 kad-call-button-case">
									<a href="/getting-started/" class="kad-btn-primary kad-btn lg-kad-btn">Start!</a>   	
								</div>
							</div>
						</div><!--container-->
					</div><!--call class-->
				</div><!-- end HOME_CALLTOACTION -->
		
				<div class="home-margin home-padding">
					<div class="rowtight homepromo">
						<div class="tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12 home-iconmenu homeitemcount1">
							<a href="/one-on-one-coaching-current-students/" target="_self" title="Coaching" class="home-icon-item">
								<i class="icon-comments "></i>
								<h4>Coaching</h4>
								<p>Stuck in your training? Get live, personal coaching with an in-house trainer.</p> 
							</a>
						</div>
						<div class="tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12 home-iconmenu homeitemcount2">
							<a href="https://www.facebook.com/groups/233314480125622/" target="_blank" title="Support" class="home-icon-item">
								<i class="icon-facebook-sign "></i>
								<h4>Support</h4>
								<p>Ask questions, share stories on our private Facebook group for students &amp; alumni</p> 
							</a>
						</div>
					</div> <!--homepromo -->
				</div>
			'; 
		}
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>