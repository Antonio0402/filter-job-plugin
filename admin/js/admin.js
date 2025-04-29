//Set up the color pickers to work with our text input field
jQuery.noConflict();

jQuery(document).ready(function ($) {

  "use strict";

  //This if statement checks if the color picker widget exists within jQuery UI

  //If it does exist then we initialize the WordPress color picker on our text input field

  if (typeof $.wp === 'object' && typeof $.wp.wpColorPicker === 'function') {

    $('.job-filter-color').each(function () {
      $(this).wpColorPicker({
        change: function (event, ui) {
          $(this).val(ui.color.toString());
        }
      });
    });
  }

});