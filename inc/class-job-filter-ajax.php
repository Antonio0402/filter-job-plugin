<?php
if (!class_exists('JobFilterAjax')) {
  class JobFilterAjax
  {
    public function __construct()
    {
      add_action('wp_enqueue_scripts', array($this, 'loadFrontAjaxScripts'));
      add_action('wp_ajax_job_filter', array($this, 'job_filter'));
      add_action('wp_ajax_nopriv_job_filter', array($this, 'job_filter'));
    }

    public function loadFrontAjaxScripts()
    {
      if (is_post_type_archive('jobs')) {
        wp_enqueue_script('job-filter-ajax-script', JOB_FILTER_PLUGIN_URL . 'assets/js/job-filter-ajax.js', array('jquery'), JOB_FILTER_PLUGIN_VERSION, true);
        wp_localize_script('job-filter-ajax-script', 'jobFilterAjaxData', array(
          'root_url' => get_site_url(),
          'ajax_url' => admin_url('admin-ajax.php'),
          'action' => 'job_filter',
          'nonce' => wp_create_nonce('wp_job_filter_nonce'),
        ));
      }
    }

    public function job_filter()
    {
      check_ajax_referer('wp_job_filter_nonce', 'nonce');
      $jobFilterTemplateLoader = JobFilterTemplateLoader::get_instance();
      ob_start();
      $jobFilterTemplateLoader->get_template_part('partials/content');
      $output = ob_get_clean();
      echo $output;
      die();
    }
  }
  new JobFilterAjax();
}
