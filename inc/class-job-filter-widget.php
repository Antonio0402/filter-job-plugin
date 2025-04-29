<?php

/**
 * Widget API: JobFilterWidget class
 *
 * @package Job Filter for Job Postings
 * @subpackage Widgets
 * @since 1.0.0
 */

/**
 * Job Filter Widget
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
if (!class_exists('JobFilterWidget')) {
  class JobFilterWidget extends WP_Widget
  {
    public function __construct()
    {
      $widget_options = array(
        'classname' => 'job_filter_widget',
        'description' => __('A widget to filter job postings', JOB_FILTER_TEXT_DOMAIN),
        'customize_selective_refresh' => true,
        'show_instance_in_rest' => true,
      );
      parent::__construct(
        'job_filter_widget',
        __('Job Filter Widget', JOB_FILTER_TEXT_DOMAIN),
        $widget_options
      );
      $this->alt_option_name = 'job_filter_widget';
    }

    public function widget($args, $instance)
    {
      if (! isset($args['widget_id'])) {
        $args['widget_id'] = $this->id;
      }
      extract($args);
      $default_title = JobFilterPlugin::class_get_option('title');
      $title         = $instance['title'] ?: $default_title;
      $title = apply_filters('widget_title', $title, $instance, $this->id_base);
      echo $before_widget;
      if ($title) {
        echo $before_title . esc_html($title) . $after_title;
      }
      $jobFilterTemplateLoader = JobFilterTemplateLoader::get_instance();
      $jobFilterTemplateLoader->get_template_part('partials/filter');
?>
      <!-- Write a script to select the submit button and modify it into a anchor link which take the value from form and redirect to the archive page with params -->
      <script type="text/javascript" defer>
        document.addEventListener('DOMContentLoaded', function() {
          const form = document.getElementById('job_search_form');
          const submitButton = form.querySelector('button[type="submit"]');
          const searchInput = form.querySelector('input[name="job_search"]');
          const teamSelect = form.querySelector('select[name="job_team"]');
          const locationSelect = form.querySelector('select[name="job_location"]');

          submitButton.addEventListener('click', function(event) {
            event.preventDefault();
            const searchValue = searchInput.value;
            const teamValue = teamSelect.value;
            const locationValue = locationSelect.value;

            let url = '<?php echo esc_url(get_post_type_archive_link('jobs')); ?>';
            if (searchValue) {
              url += '&job_search=' + encodeURIComponent(searchValue);
            }
            if (teamValue) {
              url += '&job_team=' + encodeURIComponent(teamValue);
            }
            if (locationValue) {
              url += '&job_location=' + encodeURIComponent(locationValue);
            }

            window.location.href = url;
          });
        });
      </script>
    <?php
      echo $args['after_widget'];
    }

    public function form($instance)
    {
      $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat"
          id="<?php echo $this->get_field_id('title'); ?>"
          name="<?php echo $this->get_field_name('title'); ?>"
          type="text"
          value="<?php echo $title; ?>" />
      </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
      $instance              = $old_instance;
      $instance['title']     = sanitize_text_field($new_instance['title']);
      return $instance;
    }
  }
}
