<?php
if (!class_exists('JobFilterPlugin')) {
  class JobFilterPlugin
  {
    const FIELDS_GROUP = 'job-filter-fields-group';
    protected $slug;
    protected $option_group;
    public function __construct()
    {
      define('JOB_FILTER_CLASS', get_class($this));
      $this->slug = JOB_FILTER_SETTING_PAGE_SLUG;
      $this->option_group = self::FIELDS_GROUP;

      // add_action('wp_head', [$this, 'loadVendorScripts']);
      // add_action('admin_head', [$this, 'loadVendorScripts']);
      add_action('wp_enqueue_scripts', [$this, 'loadFrontScripts']);
      add_action('admin_enqueue_scripts', [$this, 'loadBackendScripts']);

      add_action('plugins_loaded', [$this, 'languages']);

      add_action('widgets_init', [$this, 'registerWidget']);

      add_action('admin_menu', [$this, 'addSettingPage']);
      add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'addPluginSettingLink']);
    }

    public function loadVendorScripts()
    {
      if (is_post_type_archive('jobs') || has_shortcode(get_post()->post_content, 'job_filter')): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css">
        </link>
      <?php endif;
    }

    public function loadFrontScripts()
    {
      if (!is_post_type_archive('jobs') && !has_shortcode(get_post()->post_content, 'job_filter')) {
        return;
      }
      wp_register_script('job-filter-script', JOB_FILTER_PLUGIN_URL . 'assets/js/job-filter.js', array('jquery'), JOB_FILTER_PLUGIN_VERSION, true);
      wp_register_style('job-filter-style', JOB_FILTER_PLUGIN_URL . 'assets/css/job-filter.css', array(), JOB_FILTER_PLUGIN_VERSION, 'all');
      wp_enqueue_style(
        'bulma-css',
        JOB_FILTER_PLUGIN_URL . 'vendor/job-filter-bulma.min.css',
        array(),
        '1.0.4'
      );
      wp_enqueue_script('job-filter-script');
      wp_enqueue_style('job-filter-style');
    }

    public function loadBackendScripts($hook_prefix)
    {
      if (strpos($hook_prefix, 'job-filter') !== false) {
        function add_styles_scripts()
        {
          //Access the global $wp_version variable to see which version of WordPress is installed.
          global $wp_version;

          //If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.

          if (3.5 <= $wp_version) {
            //Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
          }
          //If the WordPress version is less than 3.5 load the older farbtasic color picker. 
          else {
            //As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle. 
            wp_enqueue_style('farbtastic');
            wp_enqueue_script('farbtastic');
          }
          //Load our custom javascript file 
          wp_enqueue_script('wp-color-picker-settings', plugin_dir_url(__FILE__) . 'js/settings.js');
        }
        wp_register_script('job-filter-admin-script', JOB_FILTER_PLUGIN_URL . '/admin/js/admin.js', array('jquery'), JOB_FILTER_PLUGIN_VERSION, true);
        wp_register_style('job-filter-admin-style', JOB_FILTER_PLUGIN_URL . 'admin/css/admin.css', array(), JOB_FILTER_PLUGIN_VERSION, 'all');
        wp_enqueue_style(
          'bulma-css',
          JOB_FILTER_PLUGIN_URL . 'vendor/job-filter-bulma.min.css',
          array(),
          '1.0.4'
        );
        wp_enqueue_script('job-filter-admin-script');
        wp_enqueue_style('job-filter-admin-style');
      }
    }

    public function registerWidget()
    {
      register_widget('JobFilterWidget');
    }

    public function languages()
    {
      load_plugin_textdomain(JOB_FILTER_TEXT_DOMAIN, false, JOB_FILTER_PLUGIN_LANG_DIR);
    }

    public function addSettingPage()
    {
      add_options_page(
        'Job Filter Settings',
        __('Job Filter', JOB_FILTER_TEXT_DOMAIN),
        'manage_options',
        $this->slug,
        [$this, 'pageHTML']
      );
    }

    public function pageHTML()
    {
      if (! current_user_can('manage_options')) {
        return;
      }
      $template = JOB_FILTER_PLUGIN_ADMIN_DIR . 'job-filter-setting-page.php';
      if (file_exists($template)) {
        include_once $template;
      } else {
        echo '<div class="job-filter-error"><p>' . __('Template not found', JOB_FILTER_TEXT_DOMAIN) . '</p></div>';
      }
    }

    public function handleForm()
    {
      ob_start();
      if (
        !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'filter-job-nonce') ||
        !current_user_can('manage_options')
      ): ?>

        <div class="job-filter-notification is-danger">
          <button class="job-filter-delete" aria-label="delete"></button>
          <?php esc_html_e('You do not have permission to perform this action.', JOB_FILTER_TEXT_DOMAIN); ?>
        </div>

      <?php else:
        if (isset($_POST[JOB_FILTER_PREFIX . 'title'])) {
          update_option(JOB_FILTER_PREFIX . 'title', sanitize_text_field($_POST[JOB_FILTER_PREFIX . 'title']));
        }
        if (isset($_POST[JOB_FILTER_PREFIX . 'result'])) {
          update_option(JOB_FILTER_PREFIX . 'result', sanitize_text_field($_POST[JOB_FILTER_PREFIX . 'result']));
        }
        if (isset($_POST['jobFilterColorOptions'])) {
          update_option('jobFilterColorOptions', [
            'primary-color' => sanitize_hex_color($_POST['jobFilterColorOptions']['primary-color']),
            'accent-color' => sanitize_hex_color($_POST['jobFilterColorOptions']['accent-color']),
          ]);
        }
      ?>
        <div class="job-filter-notification is-success has-text-white">
          <button class="job-filter-delete"></button>
          <?php esc_html_e('Settings saved successfully.', JOB_FILTER_TEXT_DOMAIN); ?>
        </div>
      <?php endif; ?>
      <script type="text/javascript" defer>
        jQuery(document).ready(function($) {
          $('.job-filter-notification .job-filter-delete').on('click', function() {
            $(this).parent().fadeOut();
          });
        });
      </script>
<?php
      ob_end_flush();
    }

    public function addPluginSettingLink($links)
    {
      $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=' . $this->slug)) . '">' . __('Settings', JOB_FILTER_TEXT_DOMAIN) . '</a>';
      array_push($links, $settings_link);
      return $links;
    }

    static function class_get_option($field_name, $default = '')
    {
      $option = get_option(JOB_FILTER_PREFIX . $field_name, $default);

      if ($field_name === 'post_type' && !is_array($option)) {
        return array();
      }

      return $option;
    }

    static function activation()
    {
      // Flush rewrite rules on activation
      flush_rewrite_rules();
    }
    static function deactivation()
    {
      // Flush rewrite rules on deactivation
      flush_rewrite_rules();
    }
  }

  $jobFilterPlugin = new JobFilterPlugin();

  register_activation_hook(__FILE__, [$jobFilterPlugin, 'activation']);
  register_deactivation_hook(__FILE__, [$jobFilterPlugin, 'deactivation']);
}
