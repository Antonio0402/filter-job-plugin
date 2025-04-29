<?php
class JobFilterTemplateLoader extends Gamajo_Template_Loader
{
  /**
   * Prefix for filter names.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $filter_prefix = 'job-filter';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $theme_template_directory = 'job-filter';

  protected $plugin_directory = JOB_FILTER_PLUGIN_DIR;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * e.g. 'templates' or 'includes/templates', etc.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_template_director = 'templates';

  public $templates;
  private static $instance = null;
  public static function get_instance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
      self::$instance->register();
    }
    return self::$instance;
  }
  public function register()
  {
    add_filter('template_include', [$this, 'jobFilterTemplates']);
  }

  public function jobFilterTemplates($template)
  {
    if (is_post_type_archive('jobs')) {
      $theme_file = ['archive-jobs.php', 'job-filter/archive-jobs.php'];
      $exists = locate_template($theme_file, false);
      if (!empty($exists)) {
        return $exists;
      } else {
        return JOB_FILTER_TEMPLATES_DIR . 'archive-jobs.php';
      }
    }

    return $template;
  }

  function display_job_listing($lang)
  {
    ob_start();
    $post_id = get_the_ID();
    $job_location = get_post_meta($post_id, 'position_job_location', true);
    if (empty($job_location)) {
      $job_location = 'Ha Noi/Ho Chi Minh';
    }
    $job_team = wp_get_post_terms($post_id, 'jobs_category', array('fields' => 'names'));
?>
    <div class="job-filter-job_card_link | job-filter-box job-filter-fixed-grid job-filter-px-6 job-filter-py-5" role="listitem" data-id="<?php the_ID(); ?>" style="width: 100%;--bulma-scheme-main: hsl(0, 0%, 96%);">
      <div class="job-filter-grid is-align-items-center">
        <div class="job-filter-cell is-flex is-flex-direction-column is-gap-3">
          <h3 class="job-filter-subtitle is-size-5">
            <?php echo esc_html(get_the_title()) ?>
          </h3>
          <div class="job-filter-job_card_detail | is-flex is-align-items-center is-gap-4">
            <div class="job-filter-job_card_location | job-filter-level job-filter-mb-0">
              <span class="job-filter-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgb(47, 144, 79)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-filter-feather job-filter-feather-map-pin">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </span>
              <p class="job-filter-job_card_location__content"><?php echo esc_html($job_location) ?: 'Thành phố Hồ Chí Minh'; ?></p>
            </div>
            <div class="job-filter-job_card_team | job-filter-level">
              <span class="job-filter-icon">
                <!--?xml version="1.0" encoding="UTF-8"?-->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgb(47, 144, 79)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-filter-feather job-filter-feather-layers">
                  <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                  <polyline points="2 17 12 22 22 17"></polyline>
                  <polyline points="2 12 12 17 22 12"></polyline>
                </svg>
              </span>
              <p class="job-filter-job_card_team__content"><?php echo esc_html($job_team[0]) ?></p>
            </div>
          </div>
        </div>
        <div class="job-filter-cell" style="justify-self: flex-end;">
          <a
            href="<?php echo esc_url(get_the_permalink()) ?>"
            target="_self"
            class="job-filter-button is-light"
            aria-label="<?php echo esc_html__('View position', JOB_FILTER_TEXT_DOMAIN) ?>">
            <span class="job-filter-icon-text">
              <span><?php echo esc_html__('View position', JOB_FILTER_TEXT_DOMAIN) ?></span>
              <span class="job-filter-icon">
                <!--?xml version="1.0" encoding="UTF-8"?-->
                <svg data-bbox="1.75 0.954 27.999 22.094"
                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 24"
                  height="24" width="30" data-type="ugc">
                  <g>
                    <path fill="#7CDC91"
                      d="M29.31 13.06a1.5 1.5 0 0 0 0-2.12l-9.545-9.547a1.5 1.5 0 1 0-2.122 2.122L26.13 12l-8.486 8.485a1.5 1.5 0 1 0 2.122 2.122l9.546-9.546zm-22.06.44h21v-3h-21v3z">
                    </path>
                    <path stroke-linejoin="round" stroke-linecap="round"
                      stroke-width="3" stroke="#7CDC91" d="M1.75 12h8"
                      fill="none"></path>
                  </g>
                </svg>
              </span>
          </a>
        </div>
      </div>
    </div>
    </div>
<?php
    return ob_get_clean();
  }
}

$jobFilterTemplateLoader = new JobFilterTemplateLoader();
$jobFilterTemplateLoader->register();
