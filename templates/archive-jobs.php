<?php
$jobFilterTemplateLoader = JobFilterTemplateLoader::get_instance();
$color = get_option('jobFilterColorOptions');
get_header();
?>
<style>
  .job-filter-divider {
    border-top: 5px solid <?php echo $color['primary-color'] ?: 'var(--bulma-primary-on-scheme)' ?>;
    margin-top: 1.5rem;
    margin-left: -200px;
    max-width: max(calc(100vw / 3), 300px);
  }
</style>
<section id="job_section" class="job-filter-section">
  <div class="job-filter-container">
    <div class="job-filter-block is-flex is-align-items-center is-gap-3">
      <div class="job-filter-block">
        <h2 class="job-filter-title has-text-black">
          <?php esc_html_e(JobFilterPlugin::class_get_option('title')) ?>
        </h2>
        <div class="job-filter-divider"></div>
      </div>
      <figure class="job-filter-image is-128x128">
        <?php
        if (function_exists('the_custom_logo') && has_custom_logo()) {
          the_custom_logo();
        } else {
          // Optional: fallback image or site name
          echo '<img src="' . esc_url(get_site_icon_url(128)) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
        }
        ?>
      </figure>
    </div>
    <div class="job-filter-container is-max-widescreen job-filter-py-6">
      <?php
      $jobFilterTemplateLoader->get_template_part('partials/filter');
      ?>
      <div class="job-filter-job_results_wrapper | job-filter-block job-filter-px-4">
        <div class="job-filter-block">
          <h3 class="job-filter-subtitle has-text-black has-text-weight-bold">
            <?php esc_html_e(JobFilterPlugin::class_get_option('result')) ?>
          </h3>
        </div>
        <div class="job-filter-block is-hidden has-text-centered" id="job_filter_loader">
          <div class="job-filter-spinning-loader" style="border-color: <?php echo $color['primary-color'] ?>; border-bottom-color: transparent;">
          </div>
        </div>
        <div class="job-filter-block" id="job_results">
          <?php $jobFilterTemplateLoader->get_template_part('partials/content') ?>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
<?php wp_footer(); ?>