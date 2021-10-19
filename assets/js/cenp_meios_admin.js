jQuery(document).ready(function ($) {
  $('#meta_box_cm').find('.postbox-header').remove();
  $('#meta_box_cm-hide').prop('disabled', true);

  $(document).on('click', '.show_json', function (e) {
    e.preventDefault();
    var json = sessionStorage.getItem('error_json');
    $('#show_json_error').html(json);
    $('#show_json_error').fadeIn();
  });

  var allowedExtensions = /(\.xml|\.xlsx)$/i;
  $(".custom-file-input").on("change", function () {
    $('.alert').not('.alert-fixed').remove();
    if (!allowedExtensions.exec($(this).val())) {
      $(this).siblings(".custom-file-label").removeClass("selected").html('Selecione');
      create_alert(`
            <strong>Ops!</strong> A planilha selecionada não é compativel com a matriz, por favor informe um arquivo válido! 
          `, 'danger', true, true);
      $(this).val(null);
    } else {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
      create_alert('<strong>Aguarde!</strong> Estamos avaliando a estrutura da planilha, isso pode levar um tempo!', 'primary', false);
      create_json($(this)[0].files[0]);
    }
  });

  if (!String.prototype.slugify) {
    String.prototype.slugify = function () {

      return this.toString().toLowerCase().trim()
        .replace(/[àÀáÁâÂãäÄÅåª]+/g, 'a')       // Special Characters #1
        .replace(/[èÈéÉêÊëË]+/g, 'e')       	// Special Characters #2
        .replace(/[ìÌíÍîÎïÏ]+/g, 'i')       	// Special Characters #3
        .replace(/[òÒóÓôÔõÕöÖº]+/g, 'o')       	// Special Characters #4
        .replace(/[ùÙúÚûÛüÜ]+/g, 'u')       	// Special Characters #5
        .replace(/[ýÝÿŸ]+/g, 'y')       		// Special Characters #6
        .replace(/[ñÑ]+/g, 'n')       			// Special Characters #7
        .replace(/[çÇ]+/g, 'c')       			// Special Characters #8
        .replace(/[ß]+/g, 'ss')       			// Special Characters #9
        .replace(/[Ææ]+/g, 'ae')       			// Special Characters #10
        .replace(/[Øøœ]+/g, 'oe')       		// Special Characters #11
        .replace(/[%]+/g, 'pct')       			// Special Characters #12
        .replace(/\s+/g, '_')           		// Replace spaces with -
        .replace(/[^\w\-]+/g, '')       		// Remove all non-word chars
        .replace(/[-]/gmi, '_')         		// Replace multiple - with single -
        .replace(/^-+/, '')             		// Trim - from start of text
        .replace(/-+$/, '');            		// Trim - from end of text
    };
  }

  const normalizeKeys = (obj) => {
    const isObject = o => Object.prototype.toString.apply(o) === '[object Object]'
    const isArray = o => Object.prototype.toString.apply(o) === '[object Array]'

    let transformedObj = isArray(obj) ? [] : {}

    for (let key in obj) {
      // replace the following with any transform function
      const transformedKey = key.slugify();

      if (isObject(obj[key]) || isArray(obj[key])) {
        transformedObj[transformedKey] = normalizeKeys(obj[key])
      } else {
        transformedObj[transformedKey] = obj[key]
      }
    }
    return transformedObj
  }

  const json_validate = (str) => {
    try {
      JSON.parse(str);
    } catch (e) {
      create_alert(`
        <strong>Ops!</strong> JSON inválido ou dados inconsistentes!<br><b>Error</b>: ${e.message}<br> 
        <a href="javascript:void(0);" class="show_json">Ver JSON</a>
        <pre style="display: none;" id="show_json_error"></pre>
      `, 'danger', false, true);
      $('.custom-file-input').val(null);
      $('#cm_json').val(null);
      $('.custom-file-input').siblings(".custom-file-label").removeClass("selected").html('Selecione');
      sessionStorage.setItem('error_json', str);
      return false;
    }
    return true;
  }

  const create_alert = (alert_html, type, autoclose = false, remove = false) => {
    if (remove) {
      $('.alert').not('.alert-fixed').remove();
    }

    var alert_time = new Date().getTime();

    $('#cm_alerts').append(`
      <div class="alert alert-${type}" id="${alert_time}" role="alert" style="display: none;">
        ${alert_html}
      </div>
    `);
    $(`#${alert_time}`).fadeIn("show");
    if (autoclose) {
      $(`#${alert_time}`).fadeIn("show");
      window.setTimeout(function () {
        $(`#${alert_time}`).fadeTo(500, 0).slideUp(500, function () {
          $(this).remove();
        });
      }, 15000);
    }
  }

  const create_json = (file) => {
    var file_reader = new FileReader();

    file_reader.onload = function (event) {
      var data = event.target.result;
      var xlsx_reader = XLSX.read(data, {
        type: "binary"
      });

      var res = xlsx_reader.SheetNames.reduce(function (value, key) {
        var pages = xlsx_reader.Sheets[key];
        return value[key] = XLSX.utils.sheet_to_json(pages),
          value
      }, {});
      var json_string = JSON.stringify(normalizeKeys(res), undefined, 2);
      if (json_validate(json_string)) {
        $('#cm_json').val(btoa(json_string));
        var json_obj = JSON.parse(json_string);

        switch ($('#cm_spreadsheet_type').find('option:selected').val()) {
          case '1':
            if (json_obj.hasOwnProperty('matriz')) {
              create_alert(`<strong>Sucesso!</strong> Foram encontrados <strong>${json_obj.matriz.length}</strong> registros em sua matriz!`, 'success', true, true);
            } else {
              create_alert(`
                <strong>Ops!</strong> A planilha selecionada não é compativel com a matriz, por favor informe um arquivo válido! 
              `, 'danger', true, true);
              $('.custom-file-input').val(null);
              $('#cm_json').val(null);
              $('.custom-file-input').siblings(".custom-file-label").removeClass("selected").html('Selecione');
            }
            break;
          case '2':
            if (
              json_obj.hasOwnProperty('meios') &&
              json_obj.hasOwnProperty('regioes') &&
              json_obj.hasOwnProperty('meios_regioes') &&
              json_obj.hasOwnProperty('estados')
            ) {
              create_alert(`
                <strong>Sucesso!</strong> Foram encontrados <strong>${json_obj.meios.length}</strong> registros por meios de comunicação!<br>
                <strong>Sucesso!</strong> Foram encontrados <strong>${json_obj.regioes.length}</strong> registros por regiões!<br>
                <strong>Sucesso!</strong> Foram encontrados <strong>${json_obj.meios_regioes.length}</strong> registros por meios e regiões!<br>
                <strong>Sucesso!</strong> Foram encontrados <strong>${json_obj.estados.length}</strong> registros por estado!
              `, 'success', true, true);
            } else {
              create_alert(`
                <strong>Ops!</strong> A planilha selecionada não é compativel com a matriz, por favor informe um arquivo válido! 
              `, 'danger', true, true);
              $('.custom-file-input').val(null);
              $('#cm_json').val(null);
              $('.custom-file-input').siblings(".custom-file-label").removeClass("selected").html('Selecione');
            }
            break;
        }
      }
    };

    file_reader.onprogress = function (event) {
      create_alert('<strong>Aguarde!</strong> Estamos efetuando a leitura dos dados, isso pode levar um tempo!', 'primary', false, true);
    };

    file_reader.readAsBinaryString(file);
  }
});