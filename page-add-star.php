<?php
/**
 * Template Name: Add Star Form
 
*/


// enqueue jquery for this form
add_action( 'wp_enqueue_scripts', 'constellection_enqueue_add_star_scripts' );


get_header(); 

// ----- set defaults ---------------------

// mmm cookies
$submitterName  = (isset ($_COOKIE["constellection_name"]) ) ? $_COOKIE["constellection_name"] : '';
$submitterEmail = (isset ( $_COOKIE["constellection_email"]) ) ? $_COOKIE["constellection_email"] : '';

$starURL = $starTitle = $starTime =  $starDescription =   $starTags = ''; 		// start it empty, baby
$errors = array(); 			// holder for bad form entry warnings

$starMedia = $starType = ['nada'];

$feedback_msg = '<div class="alert alert-info" role="alert">Create a new Star item to add to this site! Enter all required information below and <a href="#prettybuttons" style="text-decoration: underline">use the buttons below</a> to <a href="#" class="btn btn-primary btn-xs disabled">update</a> your information to verify it. Then you can modify and <a href="#" class="btn btn-warning btn-xs disabled">preview</a> as much as necessary to make it look beautiful (get it right, you will not be able to edit it later). Once you are happy with it, <a href="#" class="btn btn-success btn-xs disabled">submit</a> the form for the last time and it will be saved to this site.';

$feedback_msg .=  '</div>'; // close dat div

$previewBtnState = ' disabled';
$submitBtnState = ' disabled';

// for taxonomy terms (categories for stars)
$starTypeTerms = get_terms( array(
	'taxonomy' => 'star-type',
	'hide_empty' => false,
) );

// for taxonomy terms (media for stars) 
$starMediaTerms = get_terms( array(
	'taxonomy' => 'star-media',
	'hide_empty' => false,
) );

if ( isset( $_POST['constellection_form_add_star_submitted'] ) && wp_verify_nonce( $_POST['constellection_form_add_star_submitted'], 'constellection_form_add_star' ) ) {

	// grab the variables from the form
	$starTitle = 			stripslashes(sanitize_text_field( $_POST['starTitle'] ));		
	$submitterName = 		stripslashes(sanitize_text_field( $_POST['submitterName'] )); 
	$starTags = 			cleanTags( sanitize_text_field( $_POST['starTags'] ) );	
	$submitterEmail = 		sanitize_email( $_POST['submitterEmail'] ); 
	$starDescription = 		$_POST['starDescription'];

	if (isset( $_POST['starTime'] )) $starTime = 			$_POST['starTime'];
	if (isset( $_POST['starType'] )) $starType = 			$_POST['starType'];
	if ( isset( $_POST['starMedia'] )) $starMedia = 			$_POST['starMedia'];			
	$starURL = 				esc_url( trim($_POST['starURL']), array('http', 'https') ); 


	// make cookies
	//setcookie( "constellection_name", $submitterName, strtotime( '+14 days' ), '/' );  
	//setcookie( "constellection_email", $submitterEmail, strtotime( '+14 days' ),  '/' ); 

		if ( $starTitle == '' ) $errors['starTitle'] = '<span class="label label-danger">Star Title Missing</span> - please enter a descriptive title for this star item';
		if ( $submitterName == '' ) $errors['submitterName'] = '<span class="label label-danger">Name Missing</span>- enter your name so we can give you credit';
		if ( $submitterEmail == '' ) {
			$errors['submitterEmail'] = '<span class="label label-danger">Email Address Missing</span>- Enter your email in case we have to contact you.';
		} elseif ( !is_email( $submitterEmail ) )  {
			$errors['submitterEmail'] = '<span class="label label-danger">Invalid Email Address</span>- "' . $submitterEmail . '" is not a valid email address, please try again.';
		}
		
		if ( $starTime == '' ) $errors['starTime'] = '<span class="label label-danger">Estimated Time Missing</span> - select how long a person might expect to complete this star item';

	 // arbitrary and puny string length to be considered a reasonable descriptions
	if ( strlen( $starDescription ) < 10 )  $errors['starDescription'] = '<span class="label label-danger">Description Missing or Too Short</span>- please provide a full description that will help someone undestand this star. You might need a sentence or two.';

	// category check
	if ( empty( $starType ) ) $errors['starType'] = '<span class="label label-danger">Star Type Not Selected</span>- select the type that best categorizes this star';

	// category check
	if ( empty( $starMedia) ) $errors['starMedia'] = '<span class="label label-danger">Star Media Not Selected</span>- select the medium for this star';

	if ($starURL == '') {
			$errors['starURL'] = '<span class="label label-danger">URL Missing or not Entered Correctly</span>-  please enter the full URL where the content for this star be found- it must start with "http://"';	 
	} // end url CHECK 	

 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = '<div class="alert alert-danger" role="alert">Sorry, but there are a few errors in your entry. Please correct the following items and try again.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul></div>';
 			
 		} else {
	
	
			$feedback_msg = '<div class="alert alert-success" role="alert">When this form works, you will see a confirmation of the star item been acepted for review here, and an invitation to add another.</div>';  
		}

}
						
	
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'add-constellation' ); ?>

			<?php endwhile; // End of the loop. ?>
			
			<article id="make-star" <?php post_class(); ?>>
				<div class="entry-content">
					<?php echo $feedback_msg?>	
			
						<form action="" id="starform" class="starform" method="post" action="">
						
						
						<div class="form-group<?php if (array_key_exists("submitterName",$errors)) echo ' has-error ';?>">
									<label for="submitterName"><?php _e( 'Your name (required)', 'rebalance' ) ?></label>
									<span id="nameHelpBlock" class="help-block">Enter your name or however you wish to be credited for sharing this Star Item</span>
									<input type="text" name="submitterName" class="form-control" id="submitterName" value="<?php echo $submitterName; ?>" tabindex="5" placeholder="Your Name" aria-describedby="nameHelpBlock"  />
									
								</div>
				
								<div class="form-group<?php if (array_key_exists("submitterEmail",$errors)) echo ' has-error ';?>">
									<label for="submitterEmail"><?php _e( 'Your email address  (required)', 'rebalance' ) ?> </label>
									<span id="emailHelpBlock" class="help-block">Enter your email address; it is never displayed publicly, and is only used if we need to contact you to fix your entry.</span>
									<input type="email" name="submitterEmail" id="submitterEmail" class="form-control" value="<?php echo $submitterEmail; ?>" tabindex="6" placeholder="you@somewhere.org" aria-describedby="emailHelpBlock" />
									
								</div>				
	
	
							<div class="form-group<?php if (array_key_exists("starTitle",$errors)) echo ' has-error ';?>">
								<label for="starTitle"><?php _e( 'Title for this Star', 'rebalance' ) ?></label>
								<span id="titleHelpBlock" class="help-block">Enter a title that describes this Star Item so that it might make a curious visitor want to read more about it.</span>
								<input type="text" name="starTitle" id="starTitle" value="<?php  echo $starTitle; ?>" class="form-control" tabindex="1" placeholder="Enter a title" aria-describedby="titleHelpBlock" />
						
							</div>
			
							<div class="form-group<?php if ( array_key_exists( "starDescription", $errors ) ) echo ' has-error ';?>">
									<label for="starDescription"><?php _e( 'Full Description for this Star Item');?></label>
									<span id="starHelpBlock" class="help-block">Use the rich text editor to compose the information about the resource you are adding. See  <a href="https://make.wordpress.org/support/user-manual/content/editors/visual-editor/" target="_blank">documentation for using the editing tools</a> (link will open in a new tab/window). To embed media from YouTube, vimeo, instagram, SoundCloud, Twitter, flickr, just put the URL for its source page as plain text on a blank line. When your Star Item is published the link will be replaced by a media embed.</span>
									<?php
										// set up for inserting the WP post editor
										$settings = array( 'textarea_name' => 'starDescription',  'tabindex'  => "3", 'media_buttons' => false, 'textarea_rows' => 8);

										wp_editor(  stripslashes( $starDescription ), 'starDescriptionHTML', $settings );
									?>
							</div>
							
							<div class="form-group<?php if (array_key_exists("starTime",$errors)) echo ' has-error ';?>" id="starTime">
								<label for="starTime">Estimated Completion Time</label>
								<span id="starTimeHelpBlock" class="help-block">Indicate the estimate of how long this item will take to be completed</span>
					
								<?php 
								
									// get the array of times
									$allStarTimes = constellection_all_time_strings();
					
									for ( $i=1; $i<count( $allStarTimes ); $i++ ) {
										$checked = ( $starTime[0] == $i) ? 'checked="checked"' : ''; 
										echo '<div class="radio"><label><input type="radio" name="starTime[]" value="' . $i . '" ' . $checked .'> ' . $allStarTimes[$i] . '</label></div>';
									}					
									?>	
							</div>
 
		
							<div class="form-group<?php if (array_key_exists("starType",$errors)) echo ' has-error ';?>" id="starType">
								<label for="starType">Star Type</label>
								<span id="starTypeHelpBlock" class="help-block">Choose the most appropriate type</span>
					
								<?php 
									// build options based on star types
									// yes this might have been done with wp_dropdown_categories
					
									foreach ($starTypeTerms as $theTerm) {
										$checked = ( $theTerm->slug == $starType[0] ) ? 'checked="checked"' : ''; 
										echo '<div class="radio"><label><input type="radio" name="starType[]" value="' . $theTerm->slug . '" ' . $checked .'> ' . $theTerm->name . '</label></div>';
									}					
									?>	
							</div>
 
					
							<div class="form-group<?php if (array_key_exists("starMedia",$errors)) echo ' has-error ';?>">
								<label for="starMedia">Star Media</label>
								<span id="starMediaHelpBlock" class="help-block">Choose the medium for this item</span>
					
								<?php 
					
									foreach ($starMediaTerms as $theTerm) {
										$checked = ( $theTerm->slug == $starMedia[0] ) ? 'checked="checked"' : ''; 
										echo '<div class="radio"><label><input type="radio" name="starMedia[]" value="' . $theTerm->slug . '" ' . $checked .'> ' . $theTerm->name . '</label></div>';
									}					
									?>	
							</div>

 
							<div class="form-group<?php if (array_key_exists("starTags", $errors)) echo ' has-error ';?>">
								<label for="starTags"><?php _e( 'Tags that describe this Star Item (optional)', 'rebalance' ) ?></label>

								<input type="text" name="starTags" class="form-control" id="starTags" value="<?php echo $starTags; ?>" tabindex="4" placeholder="tag1, tag2, tag3"  aria-describedby="tagHelpBlock" />
								<span id="tagHelpBlock" class="help-block">All tags must be a single word; separate each tag with a comma or a space.</span>
							</div>
				


								<div class="form-group<?php if (array_key_exists("starURL",$errors)) echo ' has-error ';?>">
										<label for="starURL"><?php _e( 'Web address for the content of thie Star Item', 'rebalance' )?> <a href="<?php echo $starURL?>" class="btn btn-xs btn-warning" id="testURL" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Test Link</a></label>
										<input type="url" name="starURL" id="starURL" class="form-control" value="<?php echo $starURL; ?>" tabindex="13" placeholder="http://" aria-describedby="urlHelpBlock" /> 
										<span id="urlHelpBlock" class="help-block">Enter the URL where this item can be found. Please test the link to make sure it works.</span>
								</div>		
					

								
							
						<!-- gotta nonce -->
						<?php wp_nonce_field( 'constellection_form_add_star', 'constellection_form_add_star_submitted' ); ?>
			
						<div class="form-group" id="prettybuttons">
							<label for="submitstar"><?php _e( 'Review and Submit this Star Item ' , 'rebalance' )?></label>
					
							<div class="starbuttons">
								<button type="submit" class="btn btn-primary" id="updatestar" name="updatestar"> <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Update</button>
								<span class="help-block">Update your entered information and let us verify that it is is entered correctly.</span>
							</div>
							
							<div class="starbuttons">
								<a href="#preview" class="fancybox btn btn-warning <?php echo $previewBtnState?>" title="Preview of your Star Item; it has not yet been saved. Urls for embeddable media will render when saved."><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview</a>
						
									<span class="help-block">Generate a preview of your submission. </span>						</div>
									
							<div class="starbuttons">
									<button type="submit" class="btn btn-success <?php echo $submitBtnState?>" id="submitstar" name="submitstar">
										<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit
									</button>
									<span class="help-block">Once every thing looks good, submit this Star Item to the site.</span>
							</div>

							
				</form>		
			
			</div> <!-- entry-content -->
		</article>
			

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
