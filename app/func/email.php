<?php 

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *
 *
 */




 
 
//Customize the emails being sent from the system. 
add_filter( 'wp_mail_from', function( $name ) {
	return 'office@trainingdoulas.com';
});

add_filter( 'wp_mail_from_name', function( $name ) {
	return 'New Beginnings';
});

/**
 *
 *
	//temp location for nbHtmlWrap
	//Change name to base_email_template
	//Wait to rename until you know who's calling it. 
 */




function nbHtmlWrap( $subject, $content ):string {
	
	/* $html = nbEmailHtmlHeader(); //header
	$html .= $content;
	$html .= nbEmailHtmlFooter();//Footer
	 */
	 
	$html = '<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>'. $subject.'</title>
	</head>
	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="ltr" style="background-color: #fcfbff; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tr>
<td align="center" valign="top">
						<div id="template_header_image">
							<p style="margin-top: 0;"><img src="https://www.trainingdoulas.com/wp-content/uploads/sites/4/2017/09/nb-doula-training-logo-small.png" alt="New Beginnings Doula Training" style="border: none; display: inline; font-size: 14px; font-weight: bold; height: auto; line-height: 100%; outline: none; text-decoration: none; text-transform: capitalize;"></p>						</div>
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background: #ffffff url(https://www.trainingdoulas.com/wp-content/uploads/2017/08/single_cluster_flowers.png) no-repeat 90% 90%; border: 1px solid #e3e2e5; border-radius: 3px !important;">
<tr>
<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="background-color: #ac1b5c; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: Helvetica, Roboto, Arial, sans-serif;"><tr>
<td id="header_wrapper" style="padding: 36px 48px; display: block;">
												<h1 style="color: #ffffff; font-family: Raleway,  Arial, sans-serif; font-size: 30px; font-weight: 400; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #bd497d; -webkit-font-smoothing: antialiased;">
												'; 											
	//Insert Subject Line
	$html .= $subject;
	$html .= '								</h1>
											</td>
										</tr></table>
<!-- End Header -->
</td>
							</tr>
<tr>
<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
										<tr>
											<td valign="top" id="body_content" style="background-color: transparent;">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top" style="padding: 48px;">
                                                            <div id="body_content_inner">
																<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: Raleway,  Arial, sans-serif; color: #747475; border: 1px solid #e5e5e5; background: #ffffff;" border="1">
																	<tbody>
																		<tr><td class="td" cellspacing="0" cellpadding="6"  style="width: 100%; font-family: Raleway,  Arial, sans-serif; color: #747475; border: 1px solid #e5e5e5;">
						'; //header
	$html .= $content;
	$html .= '														</td></tr>
																	</tbody>
																</table>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Footer -->
                                	<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer"><tr>
<td valign="top" style="padding: 0; -webkit-border-radius: 6px;">
												<table border="0" cellpadding="10" cellspacing="0" width="100%"><tr>
<td colspan="2" valign="middle" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #cd769d; font-family: Raleway,  Arial, sans-serif; font-size: 12px; line-height: 125%; text-align: center;">
                                                        	New Beginnings Childbirth Services, LLC<br />
                                                        	315 E. Porter Street<br />
                                                        	Marshall, MO 65340<br />
                                                        	U.S.A.<br />
                                                        	<br />
                                                        	Need help? <a  style="color:#AC1B5C;" href="https://www.trainingdoulas.com/doula-training/contact-information/">Contact us!</a><br />
															
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
					</td>
                </tr>
            </table>
        </div>
    </body>
</html>';//Footer
	
	return $html;
}


?>
