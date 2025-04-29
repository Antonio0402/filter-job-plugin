<?php

if (!class_exists('JobFilterHelper')) {
  class JobFilterHelper
  {
    public static function get_terms_hierarchical($tax_name, $current_term)
    {

      $taxonomy_terms = get_terms(
        $tax_name,
        [
          'taxonomy' => $tax_name,
          'hide_empty' => false,
          'lang' => 'en',
          'parent' => 0,
          'fields' => 'slugs',
        ]
      );

      $html = '';
      if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
        foreach ($taxonomy_terms as $term) {
          if ($current_term == $term->term_id) {
            $html .= '<option value="' . esc_attr($term->term_id) . '" selected >' . esc_html__($term->name, JOB_FILTER_TEXT_DOMAIN) . '</option>';
          } else {
            $html .= '<option value="' . esc_attr($term->term_id) . '" >' . esc_html__($term->name, JOB_FILTER_TEXT_DOMAIN) . '</option>';
          }

          $child_terms = get_terms($tax_name, ['hide_empty' => false, 'parent' => $term->term_id]);

          if (!empty($child_terms)) {
            foreach ($child_terms as $child) {
              if ($current_term == $child->term_id) {
                $html .= '<option value="' . esc_attr($child->term_id) . '" selected > - ' . esc_html__($child->name, JOB_FILTER_TEXT_DOMAIN) . '</option>';
              } else {
                $html .= '<option value="' . esc_attr($child->term_id) . '" > - ' . esc_html__($child->name, JOB_FILTER_TEXT_DOMAIN) . '</option>';
              }
            }
          }
        }
      }
      return $html;
    }

    public static function get_unique_fields($post_type, $meta_key, $current_field = '')
    {
      $job_locations = new WP_Query(array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'meta_key' => $meta_key,
        'orderby' => 'meta_value',
        'order' => 'ASC',
      ));
      $html = '';
      if ($job_locations->have_posts()) {
        // To store unique job locations
        $unique_locations = array();

        while ($job_locations->have_posts()) {
          $job_locations->the_post();

          // Get the 'position_job_location' field
          $location = get_post_meta(get_the_ID(), $meta_key, true);

          // Only add unique locations to the array
          if (!in_array($location, $unique_locations) && !empty($location)) {
            $unique_locations[] = $location;
          }
        }

        // Output unique job locations
        foreach ($unique_locations as $location) {
          if ($location == $current_field) {
            $html .= '<option value="' . esc_attr($location) . '" selected>' . esc_html($location, JOB_FILTER_TEXT_DOMAIN) . '</option>';
          } else {
            $html .= '<option value="' . esc_attr($location) . '">' . esc_html($location, JOB_FILTER_TEXT_DOMAIN) . '</option>';
          }
        }
        wp_reset_postdata();
      }

      return $html;
    }
  }
}
