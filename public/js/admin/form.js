$(document).ready(function () {

  // 送信
  $('button[id^="btn-submit-"]').on('click', (e) => {
    e.preventDefault();
    let formId = $(e.target).attr('id').replace('btn-submit-', 'form-')
    if ($('#'+formId).length) {
      $('#'+formId).submit()
    }
    return false
  });
  // フォームリセット
  $('button[id^="btn-reset-"]').on('click', (e) => {
    e.preventDefault();
    let formId = $(e.target).attr('id').replace('btn-reset-', 'form-')
    if ($('#'+formId).length) {
      $('#'+formId).get(0).reset()
    }
    return false
  });
  // フォームクリア
  $('button[id^="btn-clear-"]').on('click', (e) => {
    e.preventDefault();
    let formId = $(e.target).attr('id').replace('btn-clear-', 'form-')
    if ($('#'+formId).length) {
      $('#'+formId).find('input, select, textarea')
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .prop('checked', false)
        .prop('selected', false)
        // Select2用
        .trigger('change')
    }
    return false
  });

  // Select2
  $('.select2bs4-sm').select2({
    theme: 'bootstrap4',
    containerCssClass: 'select-sm'
  })

  // BsCustomFileInput
  if ($('input[type="file"]').length) {
    bsCustomFileInput.init()
  }
})
