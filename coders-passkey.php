<?php defined('ABSPATH') or die;
/* * *****************************************************************************
 * Plugin Name: Coders PassKey
 * Description: Tier subscription access and plugin content extensions
 * Version: 0.5
 * Author: Coder01
 * License: GPLv2 or later
 * Text Domain: coders_passkey
 * Domain Path: lang
 * Class: PassKey
 * **************************************************************************** */

define('CODERS_PASSKEY_DIR', plugin_dir_path(__FILE__));
define('CODERS_PASSKEY_URL', plugin_dir_url(__FILE__));

require_once sprintf('%s/classes.php',CODERS_PASSKEY_DIR);

add_action('init', function(){
    add_rewrite_rule(
        '^passkey/([a-zA-Z0-9_-]+)/?$',
        'index.php?passkey=$matches[1]',
        'top'
    );
    add_filter('query_vars', function($vars ){
        $vars[] = 'passkey';
        return $vars;        
    });

    
    

    //test roles
    add_filter('coders_acl', function($roles){
        $db = new PasskeyDB('role');
        foreach( $db->list() as $content ){
            $roles[$content['role']] = $content['title'];
        }
        return $roles;
    });
    
    if(is_admin()){
        passkey_admin_setup();
    }
    
});
add_action('template_redirect', function(){
    $request = get_query_var('passkey');
    if (!empty($request)) {
        $action = explode('-', $request);
        PassKey::run('user', $action[0] , count($action) > 1 ? $action[1] : '' );
        exit;
    }    
});


// Activation Hook
register_activation_hook(__FILE__, function() {
    PasskeyDB::install();
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});


function passkey_admin_setup(){
    
    add_action('admin_menu', function () {
        add_menu_page(
            'Passkey Dashboard',       // Page title
            'Passkey',                 // Menu title
            'manage_options',          // Capability
            'coders_passkey',          // Menu slug
            function(){
                PassKey::run('Dashboard');
            },// Function callback
            'dashicons-shield-alt',    // Icon
            100                         // Position
        );

        add_submenu_page(
            'coders_passkey',
            __('Accounts','coders_passkey'),
            __('Accounts','coders_passkey'),
            'manage_options',
            'coders_passkey_accounts',
            function(){
                PassKey::run('AccountMan');
            },// Function callback
        );

        add_submenu_page(
            'coders_passkey',
            __('Roles','coders_passkey'),
            __('Roles','coders_passkey'),
            'manage_options',
            'coders_passkey_roles',
            function(){
                PassKey::run('RoleMan');
            },// Function callback
        );

        add_submenu_page(
            'coders_passkey',
            __('Tiers','coders_passkey'),
            __('Tiers','coders_passkey'),
            'manage_options',
            'coders_passkey_tiers',
            function(){
                PassKey::run('TierMan');
            },// Function callback
        );

        add_submenu_page(
            'coders_passkey',
            __('Logs','coders_passkey'),
            __('Logs','coders_passkey'),
            'manage_options',
            'coders_passkey_logs',                
            function(){
                PassKey::run('LogMan');            
            },// Function callback
        );

        add_submenu_page(
            'coders_passkey',
            __('Settings','coders_passkey'),
            __('Settings','coders_passkey'),
            'manage_options',
            'coders_passkey_settings',                
            function(){
                PassKey::run('Settings');
            },// Function callback
        );
    });

}







