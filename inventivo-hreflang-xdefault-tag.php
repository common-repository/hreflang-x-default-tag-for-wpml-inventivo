<?php /*
Contributors: inventivogermany
Plugin Name:  hreflang x-default Tag for WPML | inventivo
Plugin URI:   https://www.inventivo.de/wordpress-agentur/wordpress-plugins
Description:  Add WPML hreflang x-default Tag
Version:      1.0.6
Author:       Nils Harder
Author URI:   https://www.inventivo.de
Tags: scroll top
Requires at least: 3.0
Tested up to: 5.7.1
Stable tag: 1.0.6
Text Domain: inventivo-hreflang-xdefault-tag
Domain Path: /languages
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class InventivoHreflangXdefaultTag
{
    public function __construct()
    {
        add_action('wp_head',array($this,'x_default_hreflang'),1);
        add_action('admin_notices', array($this, 'admin_notice_get_pro'));
        add_action('admin_enqueue_scripts', array($this, 'inv_admin_style'));
        add_action( 'admin_init', array($this, 'notice_dismissed') );
        register_deactivation_hook( __FILE__, array($this, 'plugin_deactivate') );
    }

    public function x_default_hreflang()
    {
        $wpml_options = get_option( 'icl_sitepress_settings' );
        $default_lang = $wpml_options['default_language'];
        if (function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=1');
            if(!get_query_var('paged')) { // exclude pagination
                foreach($languages as $l){
                    if ( $l['language_code'] == $default_lang ) { // get WPML default language
                        $x_default_url = $l['url'];
                        $output = '<link rel="alternate" hreflang="x-default" href="' . $x_default_url . '" />'  . PHP_EOL;
                        echo $output;
                    }
                }
            }
        }
    }

    public function admin_notice_get_pro() {
        $user_id = get_current_user_id();
        if (!get_user_meta( $user_id, 'inv_notice_dismissed')) {
            echo '<div class="notice notice-success is-dismissible">
                <div class="hreflang-x-default-tag-for-wpml-inventivo-wrapper">
                    <div class="hreflang-x-default-tag-for-wpml-inventivo-element">
                        <a href="https://www.inventivo.de/en/the-x-default-tag-in-wpml-is-missing-what-now#pluginkaufen" target="_blank">
                            <img src="' . plugins_url() . '/hreflang-x-default-tag-for-wpml-inventivo/admin/images/icon-256x256-1.png" />
                        </a>
                    </div>
                    <div class="hreflang-x-default-tag-for-wpml-inventivo-element">
                        <h2>Hey Dude!</h2>
                        <p><strong>Do you like the hreflang x-default Tag Plugin?</strong> 
                        <br />Get the PREMIUM version with advanced fallback method to display the x-default tag even more securely.</p>
                        <p>
                            <a class="wp-core-ui button" href="https://www.inventivo.de/en/the-x-default-tag-in-wpml-is-missing-what-now#pluginkaufen" style="color: #FFFFFF; background: #A6CE38; border-color: #A6CE38">
                            Get it now!
                            </a>
                            <br /><br />
                            <a href="?inv-notice-dismissed" style="color: #AAAAAA;">Dismiss</a>
                        </p>
                    </div>
                </div>
            </div>';
        }
    }

    public function inv_admin_style() {
        wp_enqueue_style('admin-styles', plugins_url().'/hreflang-x-default-tag-for-wpml-inventivo/admin/css/admin-styles.css');
    }

    public function notice_dismissed()
    {
        $user_id = get_current_user_id();
        if (isset( $_GET['inv-notice-dismissed'])) {
            add_user_meta( $user_id, 'inv_notice_dismissed', 'true', true );
        }
    }

    public function plugin_deactivate()
    {
        $user_id = get_current_user_id();
        delete_user_meta($user_id, 'inv_notice_dismissed');
    }
}

$var = new InventivoHreflangXdefaultTag();