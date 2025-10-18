$(function() {
  // Einzelblock-Toggle wie zuvor
  $('input[type="checkbox"][id^="toggleBlock"]').each(function() {
    var blockId = $(this).attr('id').replace('toggleBlock', 'block');
	console.log('BlockId ',blockId);
    $('#' + blockId).toggleClass('visible', this.checked);

    $(this).on('change', function() {
      $('#' + blockId).toggleClass('visible', this.checked);
    });
  });

  // Gruppen-Toggle
  $('input[type="checkbox"][id^="toggleGroup"]').each(function() {
    var groupName = $(this).attr('id').replace('toggleGroup', 'group');
	console.log('groupName ',groupName);
    var $groupBlocks = $('.toggle-group[data-group="' + groupName + '"] .toggle-block');
	console.log('groupBlocks ',$groupBlocks);
    $groupBlocks.toggleClass('visible', this.checked);

    $(this).on('change', function() {
      $groupBlocks.toggleClass('visible', this.checked);
    });
  });
});