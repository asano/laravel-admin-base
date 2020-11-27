$(document).ready(function () {

  // 一括チェック
  $('input:checkbox[id^="check-all-"]').on('click', (e) => {
    const checkIdPrefix = ($(e.target).attr('id') ?? '').replace('check-all-', '')
    if (!!checkIdPrefix && $('[id^="'+checkIdPrefix+'"]').length) {
      $('[id^="'+checkIdPrefix+'"]').prop('checked', $(e.target).prop('checked'))
    }
  });

  // モーダル
  $('button[id^="btn-open-modal-"]').on('click', (e) => {
    const $el = $(e.target)
    const target = ($(e.target).attr('id') ?? '').replace('btn-open-modal-', '').split('_')[0]
    if (!target) {
      return
    }
    const $modal = $('#modal-' + target)
    const $form = $('#form-' + target)
    const $input = $('#form-' + target + '-input')

    const checkIdPrefix = $el.data('targetIdPrefix')
    const isBatch = $el.data('isBatch')
    const dataId = $el.data('id')

    if (!$modal.length) {
      // モーダルがない
      return
    }
    if (isBatch) {
      // 一括処理モーダル
      if ($('[id^="'+checkIdPrefix+'"]:checked').length) {
        $modal.modal()
        if ($form.length) {
          // チェックしたIDを送信用formにappend
          $form.find('input:not([name="_token"])').remove()
          let inputs = []
          $('[id^="'+checkIdPrefix+'"]:checked').each((i, t) => {
            inputs.push('<input type="hidden" name="ids[]" value="' + $(t).val() + '">')
          })
          $form.append(inputs.join(''))
        }
      }
    } else if (dataId) {
      // 単発モーダル
      $modal.modal()
      if ($form.length) {
        if ($input.length) {
          $input.val(dataId)
        } else {
          $form.find('input:not([name="_token"])').remove()
          $form.append('<input type="hidden" name="form-' + target + '-input" value="' + dataId + '">')
        }
      }
    }
  });
})
