<?php
/**
 * Template Name: Add Constellation Created Form
 
*/


// enqueue jquery for this form
// add_action( 'wp_enqueue_scripts', 'constellection_enqueue_add_constellation_scripts' );


get_header(); 

// ----- set defaults ---------------------

$submitterName  = (isset ($_COOKIE["constellection_name"]) ) ? $_COOKIE["constellection_name"] : '';
$submitterEmail = (isset ( $_COOKIE["constellection_email"]) ) ? $_COOKIE["constellection_email"] : '';

$constellationTitle = $starList = $constellationDescription = $constellationCat = $constellationTags = ''; 		// start it empty, baby

$constellationCategory = array('nada');
$errors = array(); 			// holder for bad form entry warnings

$feedback_msg = '<div class="alert alert-info" role="alert">Create a new Constellation  to add to this site! You will need first to collect all the Star IDs you wish to add. Enter all required information below and <a href="#prettybuttons" style="text-decoration: underline">use the buttons below</a> to <a href="#" class="btn btn-primary btn-xs disabled">update</a> your information to verify it. Then you can modify and <a href="#" class="btn btn-warning btn-xs disabled">preview</a> as much as necessary to make it look beautiful (get it right, you will not be able to edit it later). Once you are happy with it, <a href="#" class="btn btn-success btn-xs disabled">submit</a> the form for the last time and it will be saved to this site.';

$feedback_msg .=  '</div>'; // close dat div

$previewBtnState = ' disabled';
$submitBtnState = ' disabled';

// a little mojo to get current page ID so we can build a link back here
$post = $wp_query->post;
$current_ID = $post->ID;


// for taxonomy terms (categories for stars)
$constellationCatTerms = get_terms( array(
	'taxonomy' => 'constellation-cat',
	'hide_empty' => false,
) );


if ( isset( $_POST['constellection_form_add_star_submitted'] ) && wp_verify_nonce( $_POST['constellection_form_add_star_submitted'], 'constellection_form_add_star' ) ) {

	// grab the variables from the form
	$constellationTitle = 		stripslashes(sanitize_text_field( $_POST['constellationTitle'] ));		
	$submitterName = 			stripslashes(sanitize_text_field( $_POST['submitterName'] )); 
	$constellationTags = 			cleanTags( sanitize_text_field( $_POST['constellationTags'] ) );	
	$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
	$constellationDescription = 	$_POST['constellationDescription'];
	$starList =  stripslashes(sanitize_text_field( $_POST['starList'] ));
	if (isset( $_POST['constellationCat'] )) $constellationCategory = $_POST['constellationCat'];
		

	// make cookies
	//setcookie( "constellection_name", $submitterName, strtotime( '+14 days' ), '/' );  
	//setcookie( "constellection_email", $submitterEmail, strtotime( '+14 days' ),  '/' ); 

		if ( $constellationTitle == '' ) $errors['constellationTitle'] = '<span class="label label-danger">Star Title Missing</span> - please enter a descriptive title for this star otem';
		if ( $submitterName == '' ) $errors['submitterName'] = '<span class="label label-danger">Name Missing</span>- enter your name so we can give you credit';
		if ( $submitterEmail == '' ) {
			$errors['submitterEmail'] = '<span class="label label-danger">Email Address Missing</span>- Enter your email in case we have to contact you.';
		} elseif ( !is_email( $submitterEmail ) )  {
			$errors['submitterEmail'] = '<span class="label label-danger">Invalid Email Address</span>- "' . $submitterEmail . '" is not a valid email address, please try again.';
		}

	 // arbitrary and puny string length to be considered a reasonable descriptions
	if ( strlen( $constellationDescription ) < 10 )  $errors['constellationDescription'] = '<span class="label label-danger">Description Missing or Too Short</span>- please provide a full description that will help someone undestand this constellation. You might need a sentence or two.';

	// category check
	if ( empty( $constellationCat ) ) $errors['constellationCat'] = '<span class="label label-danger">Constellation Category not Selected</span>- select the type that best categorizes this star';
	
	
	$feedback_msg = '<div class="alert alert-success" role="alert">When this form works, you will see a confirmation of the constellation been acepted for review here, and an invitation to add another.</div>';  

}
	
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'add-star' ); ?>

			<?php endwhile; // End of the loop. ?>
			
			<article id="make-star" <?php post_class(); ?>>
				<div class="entry-content">
					<?php echo $feedback_msg?>	
			
						<form action="" id="starform" class="starform" method="post" action="">
						
						
						<div class="form-group<?php if (array_key_exists("submitterName",$errors)) echo ' has-error ';?>">
									<label for="submitterName"><?php _e( 'Your name (required)', 'rebalance' ) ?></label>
									<span id="nameHelpBlock" class="help-block">Enter your name or however you wish to be credited for sharing this Constellation</span>
									<input type="text" name="submitterName" class="form-control" id="submitterName" value="<?php echo $submitterName; ?>" tabindex="5" placeholder="Your Name" aria-describedby="nameHelpBlock"  />
									
								</div>
				
								<div class="form-group<?php if (array_key_exists("submitterEmail",$errors)) echo ' has-error ';?>">
									<label for="submitterEmail"><?php _e( 'Your email address  (required)', 'rebalance' ) ?> </label>
									<span id="emailHelpBlock" class="help-block">Enter your email address; it is never displayed publicly, and is only used if we need to contact you to fix your entry.</span>
									<input type="email" name="submitterEmail" id="submitterEmail" class="form-control" value="<?php echo $submitterEmail; ?>" tabindex="6" placeholder="you@somewhere.org" aria-describedby="emailHelpBlock" />
									
								</div>				
	
	
							<div class="form-group<?php if (array_key_exists("constellationTitle",$errors)) echo ' has-error ';?>">
								<label for="constellationTitle"><?php _e( 'Title for this Constellation', 'rebalance' ) ?></label>
								<span id="titleHelpBlock" class="help-block">Enter a title that describes this Constellation so that it might make a curious visitor want to read more about it.</span>
								<input type="text" name="constellationTitle" id="constellationTitle" value="<?php  echo $constellationTitle; ?>" class="form-control" tabindex="1" placeholder="Enter a title" aria-describedby="titleHelpBlock" />
						
							</div>
							
							<div class="form-group<?php if (array_key_exists("starList",$errors)) echo ' has-error ';?>">
								<label for="starList"><?php _e( 'Star List', 'rebalance' ) ?></label>
								<span id="titleHelpBlock" class="help-block">Enter all star IDs that should be included in  this constellation; separated by commas (note this will be a more elegant selector in the future).</span>
								<input type="text" name="starList" id="starList" value="<?php  echo $starList; ?>" class="form-control" tabindex="1" placeholder="19,23,25" aria-describedby="titleHelpBlock" />
						
							</div>
			
							<div class="form-group<?php if ( array_key_exists( "constellationDescription", $errors ) ) echo ' has-error ';?>">
									<label for="constellationDescription"><?php _e( 'Full Description for this Constellation');?></label>
									<span id="constellationHelpBlock" class="help-block">Use the rich text editor to compose the information about the constellation you are adding. See  <a href="https://make.wordpress.org/support/user-manual/content/editors/visual-editor/" target="_blank">documentation for using the editing tools</a> (link will open in a new tab/window). To embed media from YouTube, vimeo, instagram, SoundCloud, Twitter, flickr, just put the URL for its source page as plain text on a blank line. When your Constellation is published the link will be replaced by a media embed.</span>
									<?php
										// set up for inserting the WP post editor
										$settings = array( 'textarea_name' => 'constellationDescription',  'tabindex'  => "3", 'media_buttons' => false, 'textarea_rows' => 8);

										wp_editor(  stripslashes( $constellationDescription ), 'constellationDescriptionHTML', $settings );
									?>
							</div>
		
							<div class="form-group<?php if (array_key_exists("constellationCat",$errors)) echo ' has-error ';?>" id="constellatione"">
								<label for="constellationCat">Constellation Category</label>
								<span id="constellationCatHelpBlock" class="help-block">Choose the most appropriate one, eh?</span>
					
								<?php 
									// build options based on star types
									// yes this might have been done with wp_dropdown_categories
					
									foreach ($constellationCatTerms as $theTerm) {
										$checked = ( $theTerm->slug == $constellationCategory[0] ) ? 'checked="checked"' : ''; 
										echo '<div class="radio"><label><input type="radio" name="constellationCat[]" value="' . $theTerm->slug . '" ' . $checked .'> ' . $theTerm->name . '</label></div>';
									}					
									?>	
							</div>

							<div class="form-group<?php if (array_key_exists("constellationTags", $errors)) echo ' has-error ';?>">
								<label for="constellationTags"><?php _e( 'Tags that describe this Constellation (optional)', 'rebalance' ) ?></label>

								<input type="text" name="constellationTags" class="form-control" id="constellationTags" value="<?php echo $constellationTags; ?>" tabindex="4" placeholder="tag1, tag2, tag3"  aria-describedby="tagHelpBlock" />
								<span id="tagHelpBlock" class="help-block">All tags must be a single word; separate each tag with a comma or a space.</span>
							</div>
				
	
					

								
							
						<!-- gotta nonce -->
						<?php wp_nonce_field( 'constellection_form_add_star', 'constellection_form_add_star_submitted' ); ?>
			
						<div class="form-group" id="prettybuttons">
							<label for="submitconstellation"><?php _e( 'Review and Submit this Constellation ' , 'rebalance' )?></label>
					
							<div class="constellationbuttons">
								<button type="submit" class="btn btn-primary" id="updateconstellation" name="updateconstellation"> <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Update</button>
								<span class="help-block">Update your entered information and let us verify that it is is entered correctly.</span>
							</div>
							
							<div class="constellationbuttons">
								<a href="#preview" class="fancybox btn btn-warning <?php echo $previewBtnState?>" title="Preview of your Constellation; it has not yet been saved. Urls for embeddable media will render when saved."><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview</a>
						
									<span class="help-block">Generate a preview of your submission. </span>						</div>
									
							<div class="constellationbuttons">
									<button type="submit" class="btn btn-success <?php echo $submitBtnState?>" id="submitconstellation" name="submitconstellation">
										<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit
									</button>
									<span class="help-block">Once every thing looks good, submit this Constellation to the site.</span>
							</div>

							
				</form>		
			
			</div> <!-- entry-content -->
		</article>
			

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
