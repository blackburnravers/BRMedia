jQuery(document).ready(function ($) {
  const icons = [
    'fas fa-play', 'fas fa-pause', 'fas fa-forward', 'fas fa-backward',
    'fas fa-volume-up', 'fas fa-volume-mute', 'fas fa-random', 'fas fa-sync',
    'fas fa-music', 'fas fa-list', 'fas fa-expand', 'fas fa-compress',
    'fas fa-download', 'fas fa-cog', 'fas fa-heart', 'fas fa-share-alt',
    'fas fa-headphones', 'fas fa-video', 'fas fa-microphone'
  ];

  function renderIcons(grid, selected) {
    grid.empty();
    icons.forEach(icon => {
      const iconEl = $('<i class="' + icon + '"></i>');
      if (icon === selected) iconEl.addClass('active');
      iconEl.on('click', function () {
        grid.find('i').removeClass('active');
        $(this).addClass('active');
        const input = grid.closest('.brmedia-icon-picker-wrapper').find('.brmedia-icon-input');
        input.val(icon);
      });
      grid.append(iconEl);
    });
  }

  $('.brmedia-open-icon-picker').on('click', function () {
    const wrapper = $(this).closest('.brmedia-icon-picker-wrapper');
    const modal = wrapper.find('.brmedia-icon-modal');
    const input = wrapper.find('.brmedia-icon-input');
    const grid = wrapper.find('.brmedia-icon-grid');
    const selected = input.val();
    modal.toggle();
    renderIcons(grid, selected);
  });

  $('.brmedia-icon-search').on('keyup', function () {
    const query = $(this).val().toLowerCase();
    $(this).siblings('.brmedia-icon-grid').children('i').each(function () {
      const match = $(this).attr('class').toLowerCase().includes(query);
      $(this).toggle(match);
    });
  });
});