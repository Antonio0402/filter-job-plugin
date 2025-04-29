class JobSearch {
  constructor() {
    this.form = document.getElementById('job_search_form');
    this.loader = document.getElementById('job_filter_loader');
    this.jobClearFilters = document.querySelector('.job_filter_section .job_filter_clear_button');
    this.jobSearchInput = document.getElementById('job_search');
    this.jobResults = document.getElementById('job_results');
    this.jobSection = document.getElementById('job_section');
    this.jobTeam = document.getElementById('job_team');
    this.jobLocation = document.getElementById('job_location');
    this.toggleSearchForm = document.querySelector('.job_filter_section .job_search_form_toggle')
    this.bindEvents();

    let isInit = false;

    // On page load, parse URL parameters and populate the form fields
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('job_search')) {
      this.jobSearchInput.value = urlParams.get('job_search');
    }
    if (urlParams.has('job_team')) {
      const jobTeamValue = urlParams.get('job_team');
      // capitalize the first letter of each word
      this.jobTeam.value = jobTeamValue.replace(/\b\w/g, l => l.toUpperCase());
    }
    if (urlParams.has('job_location')) {
      this.jobLocation.value = urlParams.get('job_location');
    }

    if (this.jobSearchInput?.value || this.jobTeam?.value || this.jobLocation?.value) {
      isInit = true;
      this.jobClearFilters.style.display = 'block';
    }

    if (isInit) {
      setTimeout(() => {
        const jobResultsPosition = this.jobSection.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({ top: jobResultsPosition, behavior: 'smooth' });
      }, 500);
    }
  }

  bindEvents() {
    if (this.form) {
      this.form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleSearch(e);
      });
    }
    if (this.jobSearchInput) {
      this.jobSearchInput.addEventListener('keydown', this.handleKeyDown.bind(this));
      // this.jobSearchInput.addEventListener('change', this.handleChange.bind(this));
    }
    if (this.jobClearFilters) {
      this.jobClearFilters.addEventListener('click', this.handleClearFilters.bind(this));
    }
    if (this.toggleSearchForm) {
      this.toggleSearchForm.addEventListener('click', this.handleToggleSearchForm.bind(this));
    }
  }
  handleKeyDown(e) {
    if ((e.key === 'Enter' || e.key === 'enter') && e.target.value) {
      e.preventDefault();
      this.handleSearch(e);
    }
  }
  handleChange(e) {
    e.stopPropagation();
  }
  handleClearFilters(e) {
    e.preventDefault();
    this.jobSearchInput.value = '';
    this.jobTeam.value = '';
    this.jobLocation.value = '';
    this.handleSearch(undefined, true);
    setTimeout(() => {
      this.jobResults.scrollIntoView({ behavior: 'smooth' });
    }, 500);
  }
  async handleSearch(e, isClear = false) {
    if (isClear) {
      this.jobSearchInput.value = '';
      this.jobTeam.value = '';
      this.jobLocation.value = '';
    }

    const searchKeyword = this.jobSearchInput.value;
    const selectedTeam = this.jobTeam.value;
    const selectedLocation = this.jobLocation.value;

    const paramOptions = {
      action: jobFilterAjaxData.action,
      job_search: searchKeyword,
      job_team: selectedTeam.replace(/\b\w/g, l => l.toUpperCase()),
      job_location: selectedLocation,
      nonce: jobFilterAjaxData.nonce
    }
    const params = new URLSearchParams(paramOptions);

    try {
      this.loader.classList.remove('is-hidden');
      this.jobResults.style.display = 'none';

      const response = await fetch(`${jobFilterAjaxData.ajax_url}?${params.toString()}`);
      const result = await response.text();
      this.jobResults.innerHTML = result;
      if (this.jobResults.firstChild.nextSibling) {
        this.jobResults.firstChild.nextSibling.remove();
      }

      this.jobClearFilters.style.display = isClear ? 'none' : 'block';
      const jobSortPriority = this.jobResults.querySelectorAll('.job_sort_priority_number');
      if (jobSortPriority.length) {
        jobSortPriority.forEach(item => {
          item.style.display = 'none';
        })
      }

      let newUrl = window.location.pathname;
      if (params) {
        newUrl = `${window.location.pathname}?${params.toString()}`;
      }
      window.history.pushState({ path: newUrl }, '', newUrl);
    } catch (error) {
      console.error('Error fetching job results:', error);
    } finally {
      this.loader.classList.add('is-hidden');
      this.jobResults.style.display = 'block';
    }
  }
  // Toggle search form visibility
  handleToggleSearchForm() {
    const isExpanded = this.toggleSearchForm.getAttribute('aria-expanded') === 'true';
    this.toggleSearchForm.setAttribute('aria-expanded', !isExpanded);

    this.toggleSearchForm.classList.toggle('job-filter-collapsed');
    this.form.classList.toggle('job-filter-collapsed');
  };
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    const jobSearch = new JobSearch();
  });
}