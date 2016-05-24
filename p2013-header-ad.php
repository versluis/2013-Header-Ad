<?php
/**
 * Plugin Name: 2013 Header Ad
 * Plugin URI: http://wpguru.co.uk
 * Description: inserts a block of ad code into the TwentyThirteen Theme's Header and after Posts
 * Version: 1.0
 * Author: Jay Versluis
 * Author URI: http://wpguru.co.uk
 * License: GPL2
 * Text Domain: p2013-header-ad
 * Domain Path: /languages
 */
 
/*  Copyright 2016  Jay Versluis (email support@wpguru.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Add a new submenu under DASHBOARD
function p2013HeaderAd_menu() {
	
	// using a wrapper function (easy, but not good for adding JS later - hence not used)
	add_theme_page('2013 Header Ad', '2013 Header Ad', 'administrator', 'p2013-header-ad', 'p2013_header_ad_main');
}
add_action('admin_menu', 'p2013HeaderAd_menu');

// add a text domain - http://codex.wordpress.org/I18n_for_WordPress_Developers#I18n_for_theme_and_plugin_developers
function p2013HeaderAd_textdomain()
{
	load_plugin_textdomain('p2013-header-ad', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	// load_plugin_textdomain('domain', false, dirname(plugin_basename(__FILE__)));
}
add_action('plugins_loaded', 'p2013HeaderAd_textdomain');


////////////////////////////////////////////
// here's the code for the actual admin page
function p2013_header_ad_main  () {

	// link some styles to the admin page
	// $p2013headeradstyles = plugins_url ('p2013-header-ad-styles.css', __FILE__);
	// wp_enqueue_style ('p2013headeradstyles', $p2013headeradstyles );
	
	// check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient privileges to access this page. Sorry!') );
    }	
	
	// check if we're actually using TwentyThirteen
	if (!function_exists('twentythirteen_setup')) {
		p2013_header_ad_warning();
	}
	
	// if we've not used this before, populate the database
	if (get_option ('p2013HeaderCode') == '') {
		p2013_header_ad_sample_data ();
	}
	if (get_option ('p2013HeaderAdDisplayOption') == '') {
	   p2013_header_ad_display_option ();
	   }
	
	/////////////////////////////////////////////////////////////////////////////////////
	// SAVING CHANGES
	/////////////////////////////////////////////////////////////////////////////////////
	
	if (isset($_POST['SaveChanges'])) {
		// save content of text box
		update_option ('p2013HeaderCode', stripslashes ($_POST['p2013HeaderCode']));
		
		// save option to display ad for logged in users
		if (isset($_POST['p2013HeaderAdDisplayOption'])) {
			update_option ('p2013HeaderAdDisplayOption', 'yes');
		} else {
			update_option ('p2013HeaderAdDisplayOption', 'no');
		}
		
		// @since 1.5
		// save option for ad after content
		if (isset($_POST['p2013HeaderShowAfterContent'])) {
			update_option ('p2013HeaderShowAfterContent', 'yes');
		} else {
			update_option ('p2013HeaderShowAfterContent', 'no');
		}
		
		// save option for ad on front page
		if (isset($_POST['p2013HeaderShowOnFrontPage'])) {
			update_option ('p2013HeaderShowOnFrontPage', 'yes');
		} else {
			update_option ('p2013HeaderShowOnFrontPage', 'no');
		}
		
		// save priority for ad after content
		if (isset($_POST['p2013HeaderPriority'])) {
			update_option ('p2013HeaderPriority', 'yes');
		} else {
			update_option ('p2013HeaderPriority', '10');
		}
		
		// display settings saved message
		p2013_header_ad_settings_saved();
	}
	
	if (isset ($_POST['SampleData'])) {
		// populate with sample data
		p2013_header_ad_sample_data ();
		
		// display settings saved message
		p2013_header_ad_settings_saved();
	}
	
	
	//////////////////////////////////
	// READ IN DATABASE OPTION
	//////////////////////////////////
	
	$p2013HeaderCode = get_option ('p2013HeaderCode');
	$p2013HeaderAdDisplayOption = get_option ('p2013HeaderAdDisplayOption');
	$p2013HeaderShowAfterContent = get_option('p2013HeaderShowAfterContent');
	$p2013HeaderShowOnFrontPage = get_option('p2013HeaderShowOnFrontPage');
	$p2013HeaderPriority = get_option('p2013HeaderPriority');
	
	///////////////////////////////////////
	// MAIN AMDIN CONTENT SECTION
	///////////////////////////////////////
	
	
	// display heading with icon WP style
	?>
    <form name="p2013HeaderAdForm" method="post" action="">
    <div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2><?php _e('2013 Header Advertising', 'p2013-header-ad'); ?></h2>
    
    <p><strong><?php _e('Enter some HTML in the box, and it will be displayed inside the TwentyThirteen header.', 'p2013-header-ad'); ?> </strong></p>
    <p><em><?php _e('Optimised for a 468x60 pixel advert. Other sizes may need a small CSS adjustment.', 'p2013-header-ad'); ?></em></p>
    
    <pre>
    <textarea name="p2013HeaderCode" cols="80" rows="10" class="p2013CodeBox"><?php echo trim($p2013HeaderCode); ?></textarea></pre>
    
    <?php 
    // option to display ad for logged in users
    // @since 1.0
    ?>
    <p><strong><?php _e('Would you like to display the ad for users who are logged in?', 'p2013-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2013HeaderAdDisplayOption; ?>" name="p2013HeaderAdDisplayOption" <?php if ($p2013HeaderAdDisplayOption == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Untick the box to show ads only to visitors.', 'p2013-header-ad'); ?></em></p>

     <?php 
    // option to display ads after content
    // @since 1.0
    ?>
    <br><p><strong><?php _e('Display the same ad after the post content?', 'p2013-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2013HeaderShowAfterContent; ?>" name="p2013HeaderShowAfterContent" <?php if ($p2013HeaderShowAfterContent == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('', 'p2013-header-ad'); ?></em></p>
    
    <?php 
    // display ads after content on front page
    // @since 1.0
    ?>
    <p><strong><?php _e('Display after-content-ad on the front page?', 'p2013-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2013HeaderShowOnFrontPage; ?>" name="p2013HeaderShowOnFrontPage" <?php if ($p2013HeaderShowOnFrontPage == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Works best with longer posts, but looks cluttered with short posts and status updates.', 'p2013-header-ad'); ?></em></p>
    
    <br>
    <p class="save-button-wrap">
    <input type="submit" name="SaveChanges" class="button-primary" value="<?php _e('Save Changes', 'p2013-header-ad'); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="SampleData" class="button-secondary" value="<?php _e('Use Sample Data', 'p2013-header-ad'); ?>" />
    
    </form>
    <p>&nbsp;</p>
<h2><?php _e('Check it out', 'p2013-header-ad'); ?></h2>
<p><?php _e('This is what your advert will look like:', 'p2013-header-ad'); ?></p>
    <p>
  <?php	
	
	///////////////////
	// DISPLAY PREVIEW
	//////////////////
	
	echo get_option ('p2013HeaderCode');

	////////////////////////////////////////////////////////
	// ADMIN FOOTER CONTENT
	////////////////////////////////////////////////////////
?>
    <br><br>
    <hr width="90%">
    <br>    
    <p><em><?php _e('This plugin was brought to you by', 'p2013-header-ad'); ?></em><br />
    <a href="http://wpguru.co.uk" target="_blank"><img src="
    <?php 
    echo plugins_url('images/guru-header-2013.png', __FILE__);
    ?>" width="300"></a>
    </p>
    <p><a href="http://wpguru.co.uk/2013/10/p2-header-advert/" target="_blank">Plugin by Jay Versluis</a> | <a href="https://github.com/versluis/P2-Header-Ad" target="_blank">Fork me on GitHub</a> | <a href="http://wphosting.tv" target="_blank">WP Hosting</a></p>
	<?php
} // end of main function


// populate database with sample code
function p2013_header_ad_sample_data () {
	update_option ('p2013HeaderCode', '<a href="http://wordpress.org" target="_blank"><img style="border:0px" src="' . plugins_url('images/Header-Advert.png', __FILE__) . '" width="468" height="60" alt=""></a>');
}

// populate database with default value for 'display to logged in users'
function p2013_header_ad_display_option () {
    update_option ('p2013HeaderAdDisplayOption', 'yes');
}

// Put a "settings updated" message on the screen 
function p2013_header_ad_settings_saved () {
	?>
    <div class="updated">
    <p><strong><?php _e('Your settings have been saved.', 'p2013-header-ad'); ?></strong></p>
    </div>
	<?php
} // end of settings saved

// Put a warning message on the screen 
function p2013_header_ad_warning () {
	?>
    <div class="error">
    <p><strong><?php _e('You are not using the TwentyThirteen Theme.', 'p2013-header-ad'); ?><br>
    <?php _e('Please activate it first, otherwise results are unpredictable!', 'p2013-header-ad'); ?><br><br>
    
	<?php _e ('You can <a href="https://wordpress.org/themes/twentythirteen/" target="_blank">download TwentyThirteen here</a>. Or if you have already installed it,', 'p2013-header-ad'); ?> <a href="<?php echo admin_url( 'themes.php'); ?>"><?php _e('activate it here', 'p2013-header-ad'); ?></a>.</strong></p>
    </div>
	<?php
} // end of settings saved


// display the advert
function p2013DisplayAdvert () {
	
	// get our scripts ready
	wp_enqueue_script ('jquery');
	$p2013HeaderScript = plugins_url ('p2013-header-ad-script.js', __FILE__);
	wp_enqueue_script ('p2013-header-ad-script', $p2013HeaderScript, '', '', true);
	
	$p2013HeaderCode = get_option ('p2013HeaderCode');
	$p2013HeaderLoggedIn = get_option ('p2013HeaderAdDisplayOption');
	
	// use different top style depending on custom header
	if (get_header_image() == '') {
		// if no header image is present
		// $p2013HeaderCode = '<div id="p2013HeaderAd" style="top: 45px">' . $p2013HeaderCode . '</div>';
	} else {
		// if we have a header image
		// $p2013HeaderCode = '<div id="p2013HeaderAd" style="top: 30px">' . $p2013HeaderCode . '</div>';
	}
	
	// don't display if we're in the admin interface
	// since @1.0
	if (is_admin()) {
		$p2013HeaderCode = '';
	}
	
	// show ads to logged in users?
	// since @1.0
	if (is_user_logged_in () && $p2013HeaderLoggedIn == 'no') {
		$p2013HeaderCode = '';
	}
	
	// don't display code for logged in eMember users
	// since @1.0
	if (function_exists('wp_emember_is_member_logged_in')) {
		if (wp_emember_is_member_logged_in() && $p2013HeaderLoggedIn == 'no') {
			$p2013HeaderCode = '';
		}
	}
	
	// check if we're actually using TwentyThirteen, then display the code
	if (function_exists('twentythirteen_setup')) {
		echo $p2013HeaderCode;
	}
}
add_action ('get_header', 'p2013DisplayAdvert');

// adds the same advert underneath a single post
// @since 1.0
function p2013Header_ads_after_posts($content) {
	
	// we can either return $content (no advert) or $ad_content (with advert)
	$ad_content = $content . '<br><br>' . get_option('p2013HeaderCode') . '<br><br>';
	
	// do we want this option?
	if (!get_option('p2013HeaderShowAfterContent') || get_option('p2013HeaderShowAfterContent') == 'no') {
		return $content;
	}
	
	// when user is logged in, do not display the ad
	if (is_user_logged_in () && get_option('p2013HeaderAdDisplayOption') == 'no') {
		return $content;
	}
	
	// the same goes for eMeber users
	if (function_exists(wp_emember_is_member_logged_in)) {
		if (wp_emember_is_member_logged_in() && get_option('p2013HeaderAdDisplayOption') == 'no') {
			return $content;
		} 
	}
	
	// do we want ads on the front page?
	if (is_home() && get_option('p2013HeaderShowOnFrontPage') == 'yes') {
		return $ad_content;
	} 
	
	// show ad after content?
	if (get_option('p2013HeaderShowAfterContent') == 'yes' && !is_home() && !is_page()) {
		return $ad_content;
	}
	
	// DEFAULT:
	// none of the above were true - just return the content
	return $content;
}

// add filter to the_content
add_filter ('the_content', 'p2013Header_ads_after_posts', 10);

// link some styles to the admin page
// added hook since @1.6
function p2013HeaderEnqueueStyles () {
	$p2013headeradstyles = plugins_url ('p2013-header-ad-styles.css', __FILE__);
	wp_enqueue_style ('p2013headeradstyles', $p2013headeradstyles );
}
add_action('wp_enqueue_scripts', 'p2013HeaderEnqueueStyles');

?>