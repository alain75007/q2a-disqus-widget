<?php

/*
        Plugin Name: Disqus Widget
        Plugin URI: https://github.com/alain75007/q2a-disqus-widget
        Plugin Update Check URI: https://raw.github.com/alain75007/q2a-disqus-widget/master/qa-plugin.php
        Plugin Description: Adds disqus to choosen pages
        Plugin Version: 1.0
        Plugin Date: 2014-07-10
        Plugin Author: Alain Beauvois
        Plugin Author URI:  
        Plugin License: GPLv2
        Plugin Minimum Question2Answer Version: 1.3
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
			exit;
	}
	
	qa_register_plugin_module('widget', 'qa-disqus-widget.php', 'qa_disqus_widget', 'Disqus Widget');
    qa_register_plugin_module('module', 'qa-disqus-admin-form.php', 'qa_disqus_admin_form', 'Disqus');


/*
	Omit PHP closing tag to help avoid accidental output
*/
