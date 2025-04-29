<?php
if (!class_exists('JobFilterShortcode')) {
  class JobFilterShortcode
  {
    private static $instance = null;

    public static function get_instance()
    {
      if (self::$instance === null) {
        self::$instance = new JobFilterShortcode();
      }
      return self::$instance;
    }

    public function __construct()
    {
      add_action('init', [$this, 'registerShortcode']);
    }

    public function registerShortcode()
    {
      add_shortcode('job_filter', [$this, 'renderJobFilterShortcode']);
    }
    public function renderJobFilterShortcode($atts = [])
    {
      extract(shortcode_atts([
        'job_search' => '',
        'job_team' => '',
        'job_location' => '',
      ], $atts, 'job_filter'));
      $jobFilterTemplateLoader = JobFilterTemplateLoader::get_instance();
      ob_start();
      echo '<div class="job-filter-block" id="job_results">';
      if (empty($job_search) && empty($job_team) && empty($job_location)) {
        $jobFilterTemplateLoader->get_template_part('partials/content');
      } else {
        $jobFilterTemplateLoader->get_template_part('partials/content', [
          'job_search' => $job_search,
          'job_team' => $job_team,
          'job_location' => $job_location,
        ]);
      }
      echo '</div>';
      $output = ob_get_clean();
      return $output;
    }
  }
  JobFilterShortcode::get_instance();
}
