<div class="job-filter-section">
  <h1 class="job-filter-title has-text-black">
    <?php esc_html_e('Job Filter Setting', JOB_FILTER_TEXT_DOMAIN); ?>
  </h1>
  <?php
  $JOB_FILTER_CLASS = JOB_FILTER_CLASS;
  $colors = get_option('jobFilterColorOptions', []);
  if (isset($_POST['jobFilterSettingSubmitted'])) $this->handleForm();
  ?>
  <form method="POST" class="job-filter-form">
    <input type="hidden" name="jobFilterSettingSubmitted" value="true">
    <?php wp_nonce_field('filter-job-nonce', 'nonce') ?>
    <div class="job-filter-block">
      <div class="job-filter-block" style="--bulma-block-spacing: 2.5rem;">
        <div class="job-filter-field">
          <label class="job-filter-label has-text-grey-dark"><?php esc_html_e('Filter Section Title', JOB_FILTER_TEXT_DOMAIN) ?></label>
          <div class="job-filter-control">
            <input class="job-filter-input is-hovered" style="max-width: 500px;" type="text" placeholder="This is a title" name="<?php echo esc_attr(JOB_FILTER_PREFIX . 'title'); ?>" value="<?php echo esc_attr($JOB_FILTER_CLASS::class_get_option('title', 'Recent Job Openings')); ?>">
          </div>
        </div>
        <div class="job-filter-field">
          <label class="job-filter-label has-text-grey-dark"><?php esc_html_e('Filter Result Title', JOB_FILTER_TEXT_DOMAIN) ?></label>
          <div class="job-filter-control">
            <input class="job-filter-input is-hovered" style="max-width: 500px;" type="text" placeholder="This is a result title" name="<?php echo esc_attr(JOB_FILTER_PREFIX . 'result'); ?>" value="<?php echo esc_attr($JOB_FILTER_CLASS::class_get_option('result', 'Filter Jobs')); ?>">
          </div>
        </div>
      </div>
      <div class="job-filter-block">
        <h2 class="job-filter-subtitle has-text-black job-filter-pl-0"><?php esc_html_e('Filter Styling', JOB_FILTER_TEXT_DOMAIN) ?></h2>
        <div class="job-filter-field">
          <label class="job-filter-label has-text-grey-dark"><?php esc_html_e('Primary Color', JOB_FILTER_TEXT_DOMAIN) ?></label>
          <div class="job-filter-control">
            <input class="job-filter-input job-filter-color" name="jobFilterColorOptions[primary-color]" type="text" value="<?php echo esc_attr($colors['primary-color'] ?? '#00d1b2'); ?>" />
          </div>
        </div>
        <div class="job-filter-field">
          <label class="job-filter-label has-text-grey-dark"><?php esc_html_e('Secondary/Accent Color', JOB_FILTER_TEXT_DOMAIN) ?></label>
          <div class="job-filter-control">
            <input class="job-filter-input job-filter-color" name="jobFilterColorOptions[accent-color]" type="text" value="<?php echo esc_attr($colors['accent-color'] ?? '#dedede'); ?>" />
          </div>
        </div>
      </div>
    </div>
    <div class="job-filter-field">
      <div class="job-filter-control">
        <button type="submit" class="job-filter-button is-link"><?php esc_html_e('Save Settings', JOB_FILTER_TEXT_DOMAIN) ?></button>
      </div>
    </div>
  </form>
</div>