<?php
/* 
  Plugin Name: Job Filter for Job Postings
  Plugin URI: https://antonio-doan.tech/
  Description: A plugin to filter job postings based on various criteria.
  Version: 1.0.0
  Author: Antonio
  Author URI: https://antonio-doan.tech/
  Text Domain: job-filter-for-job-postings
  Domain Path: /languages/
  License: GPL2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
  exit; //Exit if accessed directly in url
}
define('JOB_FILTER', __FILE__);
define('JOB_FILTER_PLUGIN_DIR', plugin_dir_path(JOB_FILTER));
define('JOB_FILTER_PLUGIN_INC_DIR', JOB_FILTER_PLUGIN_DIR . 'inc/');
define('JOB_FILTER_PLUGIN_ADMIN_DIR', JOB_FILTER_PLUGIN_DIR . 'admin/');
define('JOB_FILTER_PLUGIN_ASSETS_DIR', JOB_FILTER_PLUGIN_DIR . 'assets/');
define('JOB_FILTER_PLUGIN_TEMPLATES_DIR', JOB_FILTER_PLUGIN_DIR . 'templates/');
define('JOB_FILTER_PLUGIN_LANG_DIR', JOB_FILTER_PLUGIN_DIR . 'languages/');
define('JOB_FILTER_PLUGIN_URL', plugin_dir_url(JOB_FILTER));
define('JOB_FILTER_PLUGIN_VERSION', '1.0.0');
define('JOB_FILTER_PLUGIN_NAME', 'Job Filter for Job Postings');
define('JOB_FILTER_PLUGIN_SLUG', 'job-filter-for-job-postings');

add_action('plugins_loaded', function () {

  // load_plugin_textdomain('job-filter-for-job-postings', false, basename(dirname(__FILE__)) . '/languages');

  if (!class_exists('Job_Postings')) {
    add_action('admin_notices', function () {
      echo '<div class="job-filter-notice job-filter-notice-error"><p>';
      // printf(
      //   esc_html__('Job Filter for Job Postings requires the %s plugin to be installed and activated.', 'job-filter-for-job-postings'),
      //   '<a href="https://wordpress.org/plugins/job-postings/" target="_blank">' . esc_html__('Job Postings', 'job-filter-for-job-postings') . '</a>'
      // );
      echo "Job Filter for Job Postings requires the %s plugin to be installed and activated. " . '<a href="https://wordpress.org/plugins/job-postings/" target="_blank">';
      echo '</p></div>';
    });

    return;
  }

  require_once JOB_FILTER_PLUGIN_INC_DIR . 'constants.php';
  require_once JOB_FILTER_PLUGIN_INC_DIR . 'class-helper.php';
  if (file_exists(JOB_FILTER_PLUGIN_INC_DIR . 'plugin-init.php')) {
    require JOB_FILTER_PLUGIN_INC_DIR . 'plugin-init.php';
  }
  if (!class_exists('JobFilterCustomPostType')) {
    require JOB_FILTER_PLUGIN_INC_DIR . 'class-job-filter-cpt.php';
  }
  if (!class_exists('Gamajo_Template_Loader')) {
    require JOB_FILTER_PLUGIN_INC_DIR . 'class-gamajo-template-loader.php';
  }
  require_once JOB_FILTER_PLUGIN_INC_DIR . 'class-job-filter-template-loader.php';
  require_once JOB_FILTER_PLUGIN_INC_DIR . 'class-job-filter-ajax.php';
  require_once JOB_FILTER_PLUGIN_INC_DIR . 'class-job-filter.php';
  require_once JOB_FILTER_PLUGIN_INC_DIR . 'class-job-filter-shortcode.php';
  require_once JOB_FILTER_PLUGIN_INC_DIR . 'class-job-filter-widget.php';
});
