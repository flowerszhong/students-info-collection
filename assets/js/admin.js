$(function () {
  $('.show-edit-box').on('click',function () {
    $(this).parent().parent().next().find('.edit-box').toggle();
  });
});

