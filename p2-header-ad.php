<?php
/**
 * Plugin Name: P2 Header Ad
 * Plugin URI: http://wpguru.co.uk
 * Description: inserts a block of ad code into the P2 Theme's Header
 * Version: 1.5
 * Author: Jay Versluis
 * Author URI: http://wpguru.co.uk
 * License: GPL2
 * Text Domain: p2-header-ad
 * Domain Path: /languages
 */
 
/*  Copyright 2013  Jay Versluis (email support@wpguru.co.uk)

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
function p2HeaderAd_menu() {
	
	// using a wrapper function (easy, but not good for adding JS later - hence not used)
	add_theme_page('P2 Header Ad', 'P2 Header Ad', 'administrator', 'p2-header-ad', 'p2_header_ad_main');
}
add_action('admin_menu', 'p2HeaderAd_menu');

// add a text domain - http://codex.wordpress.org/I18n_for_WordPress_Developers#I18n_for_theme_and_plugin_developers
function p2HeaderAd_textdomain()
{
	load_plugin_textdomain('p2-header-ad', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	// load_plugin_textdomain('domain', false, dirname(plugin_basename(__FILE__)));
}
add_action('plugins_loaded', 'p2HeaderAd_textdomain');

// link some styles to the admin page
$p2headeradstyles = plugins_url ('p2-header-ad-styles.css', __FILE__);
wp_enqueue_style ('p2headeradstyles', $p2headeradstyles );


////////////////////////////////////////////
// here's the code for the actual admin page
function p2_header_ad_main  () {
	
	// check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient privileges to access this page. Sorry!') );
    }	
	
	// check if we're actually using P2
	if (!function_exists('p2_title')) {
		p2_header_ad_warning();
	}
	
	// if we've not used this before, populate the database
	if (get_option ('p2HeaderCode') == '') {
		p2_header_ad_sample_data ();
	}
	if (get_option ('p2HeaderAdDisplayOption') == '') {
	   p2_header_ad_display_option ();
	   }
	
	/////////////////////////////////////////////////////////////////////////////////////
	// SAVING CHANGES
	/////////////////////////////////////////////////////////////////////////////////////
	
	if (isset($_POST['SaveChanges'])) {
		// save content of text box
		update_option ('p2HeaderCode', stripslashes ($_POST['p2HeaderCode']));
		
		// save option to display ad for logged in users
		if (isset($_POST['p2HeaderAdDisplayOption'])) {
			update_option ('p2HeaderAdDisplayOption', 'yes');
		} else {
			update_option ('p2HeaderAdDisplayOption', 'no');
		}
		
		// @since 1.5
		// save option for ad after content
		if (isset($_POST['p2HeaderShowAfterContent'])) {
			update_option ('p2HeaderShowAfterContent', 'yes');
		} else {
			update_option ('p2HeaderShowAfterContent', 'no');
		}
		
		// save option for ad on front page
		if (isset($_POST['p2HeaderShowOnFrontPage'])) {
			update_option ('p2HeaderShowOnFrontPage', 'yes');
		} else {
			update_option ('p2HeaderShowOnFrontPage', 'no');
		}
		
		// save priority for ad after content
		if (isset($_POST['p2HeaderPriority'])) {
			update_option ('p2HeaderPriority', 'yes');
		} else {
			update_option ('p2HeaderPriority', '10');
		}
		
		// display settings saved message
		p2_header_ad_settings_saved();
	}
	
	if (isset ($_POST['SampleData'])) {
		// populate with sample data
		p2_header_ad_sample_data ();
		
		// display settings saved message
		p2_header_ad_settings_saved();
	}
	
	
	//////////////////////////////////
	// READ IN DATABASE OPTION
	//////////////////////////////////
	
	$p2HeaderCode = get_option ('p2HeaderCode');
	$p2HeaderAdDisplayOption = get_option ('p2HeaderAdDisplayOption');
	$p2HeaderShowAfterContent = get_option('p2HeaderShowAfterContent');
	$p2HeaderShowOnFrontPage = get_option('p2HeaderShowOnFrontPage');
	$p2HeaderPriority = get_option('p2HeaderPriority');
	
	///////////////////////////////////////
	// MAIN AMDIN CONTENT SECTION
	///////////////////////////////////////
	
	
	// display heading with icon WP style
	?>
    <form name="p2HeaderAdForm" method="post" action="">
    <div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2><?php _e('P2 Header Advertising', 'p2-header-ad'); ?></h2>
    
    <p><strong><?php _e('Enter some HTML in the box, and it will be displayed inside the P2 header.', 'p2-header-ad'); ?> </strong></p>
    <p><em><?php _e('Optimised for a 468x60 pixel advert. Other sizes may need a small CSS adjustment.', 'p2-header-ad'); ?></em></p>
    
    <pre>
    <textarea name="p2HeaderCode" cols="80" rows="10" class="p2CodeBox"><?php echo trim($p2HeaderCode); ?></textarea></pre>
    
    <?php 
    // option to display ad for logged in users
    // @since 1.1
    ?>
    <p><strong><?php _e('Would you like to display the ad for users who are logged in?', 'p2-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2HeaderAdDisplayOption; ?>" name="p2HeaderAdDisplayOption" <?php if ($p2HeaderAdDisplayOption == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Untick the box to show ads only to visitors.', 'p2-header-ad'); ?></em></p>

     <?php 
    // option to display ads after content
    // @since 1.5
    ?>
    <br><p><strong><?php _e('Display the same ad after the post content?', 'p2-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2HeaderShowAfterContent; ?>" name="p2HeaderShowAfterContent" <?php if ($p2HeaderShowAfterContent == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('', 'p2-header-ad'); ?></em></p>
    
    <?php 
    // display ads after content on front page
    // @since 1.5
    ?>
    <p><strong><?php _e('Display after-content-ad on the front page?', 'p2-header-ad'); ?></strong>&nbsp; 
    <input type="checkbox" value="<?php $p2HeaderShowOnFrontPage; ?>" name="p2HeaderShowOnFrontPage" <?php if ($p2HeaderShowOnFrontPage == 'yes') echo 'checked'; ?>/>
    </p>
    <p><em><?php _e('Works best with longer posts, but looks cluttered with short posts and status updates.', 'p2-header-ad'); ?></em></p>
    
    <br>
    <p class="save-button-wrap">
    <input type="submit" name="SaveChanges" class="button-primary" value="<?php _e('Save Changes', 'p2-header-ad'); ?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="SampleData" class="button-secondary" value="<?php _e('Use Sample Data', 'p2-header-ad'); ?>" />
    
    </form>
    <p>&nbsp;</p>
<h2><?php _e('Check it out', 'p2-header-ad'); ?></h2>
<p><?php _e('This is what your advert will look like:', 'p2-header-ad'); ?></p>
    <p>
  <?php	
	
	///////////////////
	// DISPLAY PREVIEW
	//////////////////
	
	echo get_option ('p2HeaderCode');

	////////////////////////////////////////////////////////
	// ADMIN FOOTER CONTENT
	////////////////////////////////////////////////////////
?>
    <br><br>
    <hr width="90%">
    <br>    
    <p><em><?php _e('This plugin was brought to you by', 'p2-header-ad'); ?></em><br />
    <a href="http://wpguru.co.uk" target="_blank"><img src="
    <?php 
    echo plugins_url('images/guru-header-2013.png', __FILE__);
    ?>" width="300"></a>
    </p>
    <p><a href="http://wpguru.co.uk/2013/10/p2-header-advert/" target="_blank">Plugin by Jay Versluis</a> | <a href="https://github.com/versluis/P2-Header-Ad" target="_blank">Fork me on GitHub</a> | <a href="http://wphosting.tv" target="_blank">WP Hosting</a></p>
	<?php
} // end of main function


// populate database with sample code
function p2_header_ad_sample_data () {
	update_option ('p2HeaderCode', '<a href="http://wordpress.org" target="_blank"><img style="border:0px" src="' . plugins_url('images/Header-Advert.png', __FILE__) . '" width="468" height="60" alt=""></a>');
}

// populate database with default value for 'display to logged in users'
function p2_header_ad_display_option () {
    update_option ('p2HeaderAdDisplayOption', 'yes');
}

// Put a "settings updated" message on the screen 
function p2_header_ad_settings_saved () {
	?>
    <div class="updated">
    <p><strong><?php _e('Your settings have been saved.', 'p2-header-ad'); ?></strong></p>
    </div>
	<?php
} // end of settings saved

// Put a warning message on the screen 
function p2_header_ad_warning () {
	?>
    <div class="error">
    <p><strong><?php _e('You are not using the P2 Theme.', 'p2-header-ad'); ?><br>
    <?php _e('Please activate it first, otherwise results are unpredictable!', 'p2-header-ad'); ?><br><br>
    
	<?php _e ('You can <a href="http://wordpress.org/themes/p2" target="_blank">download P2 here</a>. Or if you have already installed it,', 'p2-header-ad'); ?> <a href="<?php echo admin_url( 'themes.php'); ?>"><?php _e('activate it here', 'p2-header-ad'); ?></a>.</strong></p>
    </div>
	<?php
} // end of settings saved


// display the advert
function p2DisplayAdvert () {
	
	// get our scripts ready
	wp_enqueue_script ('jquery');
	$p2HeaderScript = plugins_url ('p2-header-ad-script.js', __FILE__);
	wp_enqueue_script ('p2-header-ad-script', $p2HeaderScript, '', '', true);
	
	$p2HeaderCode = get_option ('p2HeaderCode');
	$p2HeaderLoggedIn = get_option ('p2HeaderAdDisplayOption');
	
	// use different top style depending on custom header
	if (get_header_image() == '') {
		// if no header image is present
		$p2HeaderCode = '<div id="p2HeaderAd" style="top: 45px">' . $p2HeaderCode . '</div>';
	} else {
		// if we have a header image
		$p2HeaderCode = '<div id="p2HeaderAd" style="top: 30px">' . $p2HeaderCode . '</div>';
	}
	
	// don't display if we're in the admin interface
	// since @1.2
	if (is_admin()) {
		$p2HeaderCode = '';
	}
	
	// show ads to logged in users?
	// since @1.1
	if (is_user_logged_in () && $p2HeaderLoggedIn == 'no') {
		$p2HeaderCode = '';
	}
	
	// don't display code for logged in eMember users
	// since @1.4
	if (function_exists(wp_emember_is_member_logged_in)) {
		if (wp_emember_is_member_logged_in() && $p2HeaderLoggedIn == 'no') {
			$p2HeaderCode = '';
		}
	}
	
	// check if we're actually using P2, then display the code
	if (function_exists('p2_title')) {
		echo $p2HeaderCode;
	}
}
add_action ('get_footer', 'p2DisplayAdvert');

// @since 1.5
// adds the same advert underneath a single post
function p2Header_ads_after_posts($content) {
	
	// we can either return $content (no advert) or $ad_content (with advert)
	$ad_content = $content . '<br><br>' . get_option('p2HeaderCode') . '<br><br>';
	
	// do we want this option?
	if (!get_option('p2HeaderShowAfterContent') || get_option('p2HeaderShowAfterContent') == 'no') {
		return $content;
	}
	
	// when user is logged in, do not display the ad
	if (is_user_logged_in () && get_option('p2HeaderAdDisplayOption') == 'no') {
		return $content;
	}
	
	// the same goes for eMeber users
	if (function_exists(wp_emember_is_member_logged_in)) {
		if (wp_emember_is_member_logged_in() && get_option('p2HeaderAdDisplayOption') == 'no') {
			return $content;
		} 
	}
	
	// do we want ads on the front page?
	if (is_home() && get_option('p2HeaderShowOnFrontPage') == 'yes') {
		return $ad_content;
	} 
	
	// show ad after content?
	if (get_option('p2HeaderShowAfterContent') == 'yes' && !is_home() && !is_page()) {
		return $ad_content;
	}
	
	// DEFAULT:
	// none of the above were true - just return the content
	return $content;
}

// add filter to the_content
add_filter ('the_content', 'p2Header_ads_after_posts', 10);

?>
