<?php
$color = get_option('jobFilterColorOptions');
?>

<div class="job-filter-block job-filter-px-4">
  <h3 class="job-filter-subtitle has-text-black is-size-3-desktop is-size-4-touch has-text-weight-bold">
    <?php esc_html_e('Search Jobs', JOB_FILTER_TEXT_DOMAIN) ?>
  </h3>
  <div class="job_filter_section | job-filter-block">
    <div class="job-filter-job_filter_wrapper has-text-black">
      <button class="job_filter_clear_button | job-filter-button is-text" aria-label="Clear filter" style="display: none;"><?php esc_html_e('Clear filter', JOB_FILTER_TEXT_DOMAIN) ?></button>
      <form class="job-filter-js_job_search_form" id="job_search_form" method="GET" enctype="multipart/form-data">
        <fieldset class="job-filter-fixed-grid has-1-cols-mobile has-2-cols-tablet">
          <div class="job-filter-grid">
            <legend class="job-filter-cell is-col-span-2 has-text-weight-bold"><?php echo esc_html('Filter Jobs:', JOB_FILTER_TEXT_DOMAIN); ?></legend>
            <div class="job-filter-search_input_wrapper | job-filter-cell job-filter-field is-col-span-2">
              <label for="job_search" class="job-filter-search_filter_label visually-hidden">Keywords</label>
              <div class="job-filter-search_input_bar job-filter-control">
                <input
                  class="job-filter-job_search job-filter-input is-medium job-filter-px-4 has-background-white has-text-dark"
                  id="job_search"
                  type="text"
                  placeholder="Search by job's name, skills..."
                  value="<?php echo $_GET['job_search'] ?? '' ?>"
                  name="job_search"
                  style="--bulma-input-placeholder-color: hsl(0, 0%, 48%);">
              </div>
            </div>
            <div class="job-filter- job-filter-search_filter_wrapper | job-filter-cell is-col-span-2 job-filter-fixed-grid has-1-cols-mobile has-2-cols-tablet job-filter-mb-1">
              <div class="job-filter-grid">
                <div class="job-filter-search_filter_team | job-filter-cell job-filter-field">
                  <label for="job_team" class="job-filter-search_filter_label | job-filter-label visually-hidden job-filter-">Team</label>
                  <div class="job-filter-control">
                    <div class="job-filter-select is-medium is-hovered" style="width: 100%; --bulma-arrow-color: var(--bulma-primary-on-scheme);">
                      <select name="job_team" id="job_team" class="has-background-white has-text-dark" style="width: 100%;">
                        <option value="">Select Team</option>
                        <?php
                        JobFilterHelper::get_terms_hierarchical('jobs_category', $_GET['job_team'] ?? '');
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="job-filter-search_filer_location | job-filter-cell job-filter-field">
                  <label for="job_location" class="job-filter-search_filter_label | job-filter-label visually-hidden">Location</label>
                  <div class="job-filter-control">
                    <div class="job-filter-select is-medium is-hovered" style="width: 100%; --bulma-arrow-color: var(--bulma-primary-on-scheme);">
                      <select id="job_location" name="job_location" class="has-background-white has-text-dark" style="width: 100%;">
                        <option value="">Select Location</option>
                        <?php
                        JobFilterHelper::get_unique_fields('jobs', 'position_job_location', $_GET['job_location'] ?? '');
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="job-filter-cell job-filter-field is-col-span-2 is-hidden-tablet">
              <div class="job-filter-control">
                <button type="button" class="job_search_form_toggle | job-filter-button is-success is-dark job-filter-collapsed" aria-expanded="false" style="width: 100%;">
                  <?php esc_html_e('Filter Jobs', JOB_FILTER_TEXT_DOMAIN) ?>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-filter-feather job-filter-feather-chevron-down">
                    <polyline points="6 9 12 15 18 9"></polyline>
                  </svg>
                </button>
              </div>
            </div>
            <div class="job-filter-cell job-filter-field is-col-span-2">
              <div class="job-filter-control">
                <button
                  type="submit"
                  class="job-filter-job_search_form_submit job-filter-js_job_search_submit_button | job-filter-button is-success is-medium has-text-white-bis"
                  id="js_main_job_search"
                  rel="no-follow"
                  style="width: 100%; --bulma-button-background-color: <?php echo esc_attr($color['primary-color'] ?: 'var(--bulma-primary-on-scheme)') ?>; --bulma-button-background-color-hover: <?php echo esc_attr($color['accent-color'] ?: 'var(--bulma-primary-on-scheme)') ?>;">
                  <?php echo esc_html__('Search', JOB_FILTER_TEXT_DOMAIN) ?>
                </button>
              </div>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>