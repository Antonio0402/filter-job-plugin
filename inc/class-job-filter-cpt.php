<?php
class JobFilterCustomPostType
{
  public function __construct()
  {
    add_filter('register_post_type_args', [$this, 'enableJobArchive'], 10, 2);
    add_action('add_meta_boxes', [$this, 'addMetaBoxJobs']);
    add_action('save_post', [$this, 'saveMetaBoxJobs'], 10, 2);
  }

  public function enableJobArchive($args, $post_type)
  {
    if ($post_type === 'jobs') {
      $args['has_archive'] = true;
      $args['rewrite']['slug'] = 'jobs-list';
    }
    return $args;
  }

  public function addMetaBoxJobs()
  {
    add_meta_box(
      'job-filter-priority-meta-box',
      __('Job Filter', JOB_FILTER_TEXT_DOMAIN),
      [$this, 'renderMetaBox'],
      'jobs',
      'side',
      'high'
    );
  }

  public function renderMetaBox($post)
  {
    $positionPriority = get_post_meta($post->ID, JOB_FILTER_PREFIX . 'position-priority', true) ?: 0;
    wp_nonce_field(
      JOB_FILTER_PREFIX . 'position-priority-nonce',
      '_' . JOB_FILTER_PREFIX . 'position-priority-nonce'
    );
    $fieldName = JOB_FILTER_PREFIX . 'position-priority';
?>
    <label for="<?php echo $fieldName ?>">Sort Priority:</label>
    <input type="number" id="<?php echo $fieldName ?>" name="<?php echo $fieldName ?>" value="<?php esc_attr($positionPriority); ?>" default="" />
<?php
  }

  public function saveMetaBoxJobs($post_id, $post)
  {
    $nonce = '_' . JOB_FILTER_PREFIX . 'position-priority-nonce';
    $action = JOB_FILTER_PREFIX . 'position-priority-nonce';
    if (!isset($_POST[$nonce]) || !wp_verify_nonce($_POST[$nonce], $action)) {
      return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }

    if (get_post_type($post_id) === 'jobs') {
      $fieldName = JOB_FILTER_PREFIX . 'position-priority';
      if (isset($_POST[$fieldName])) {
        update_post_meta(
          $post_id,
          $fieldName,
          sanitize_text_field($_POST[$fieldName])
        );
      }
    }
    return $post_id;
  }
}

$jobFilterCPT = new JobFilterCustomPostType();
