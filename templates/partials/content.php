<?php

$search_keyword = isset($_GET['job_search'])
  ? sanitize_text_field($_GET['job_search'])
  : (isset($args['job_search'])
    ? sanitize_text_field($args['job_search'])
    : '');
$selected_team = isset($_GET['job_team'])
  ? sanitize_text_field($_GET['job_team'])
  : (isset($args['job_team'])
    ? sanitize_text_field($args['job_team'])
    : '');
$selected_location = isset($_GET['job_location'])
  ? sanitize_text_field($_GET['job_location'])
  : (isset($args['job_location'])
    ? sanitize_text_field($args['job_location'])
    : '');
$args = [
  'post_type' => 'jobs',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  's' => $search_keyword,
  'meta_query' => [
    'relation' => 'AND',
  ],
  'tax_query' => [
    'relation' => 'AND',
  ],
];

if (!empty($selected_location)) {
  array_push($args['meta_query'], array(
    'key' => 'position_job_location',
    'value' => $selected_location,
    'compare' => 'LIKE'
  ));
}

if (function_exists('pll_current_language')) {
  $current_lang = pll_current_language('slug');
  $terms = $current_lang === 'en' ? 'en' : 'vi';
  array_push($args['tax_query'], array(
    'taxonomy' => 'language',
    'field' => 'slug',
    'terms' => $terms,
  ));
}

array_push($args['tax_query'],  array(
  'taxonomy' => 'jobs_category',
  'field' => 'slug',
  'terms' => !empty($selected_team)
    ? $selected_team
    : get_terms(
      array(
        'taxonomy' => 'jobs_category',
        'fields' => 'slugs',
        'parent' => 0,
      )
    ),
  'operator' => 'IN'
));

$jobs = new WP_Query($args);


if ($jobs->have_posts()) {
  echo '<div role="list" class="is-flex is-flex-direction-column is-align-items-center is-gap-4">';
  while ($jobs->have_posts()) {
    $jobs->the_post();
    global $jobFilterTemplateLoader;
    echo $jobFilterTemplateLoader->display_job_listing($lang);
    //only display priority for admin and publisher
    if (is_admin() || current_user_can('publish_posts')) {
      echo '<p class="job_sort_priority_number">' . esc_html__('Priority: ', JOB_FILTER_TEXT_DOMAIN) . esc_html(get_post_meta(get_the_ID(), JOB_FILTER_PREFIX . 'position-priority', true)) . '</p>';
    }
  }
  echo '</div>';
  wp_reset_postdata();
} else {
  echo '<p class="job-filter-notification is-info is-light">';
  echo esc_html__('There is no vacancy for this job right now.', JOB_FILTER_TEXT_DOMAIN);
  echo '</p>';
}
