<?php
// 2013 Header Ad uninstall script
// deletes all database options when plugin is removed
// @since 1.0
// 
// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 

if ( !function_exists('add_action') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

if ( !current_user_can('manage_options') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
// if uninstall is not called from WordPress then exit
if (!defined('WP_UNINSTALL_PLUGIN')) exit();

// delete all options
    delete_option ('p2013HeaderCode');
	delete_option ('p2013HeaderAdDisplayOption');
	delete_option ('p2013HeaderShowAfterContent');
	delete_option ('p2013HeaderShowOnFrontpage');
	delete_option ('p2013HeaderPriority');
	delete_option ('p2013HeaderShowOnHomePage');

// Thanks for using this plugin
// If you'd like to try again someday check out http://wpguru.co.uk where it lives and grows

?>