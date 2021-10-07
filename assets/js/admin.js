(function ($) {
  'use strict'
  $(function () {
    $(document).find('#ci-dollar').mask('#.##0,00', { reverse: true });

    var selected_file;
    var allowedExtensions = /(\.xml|\.xlsx)$/i;

    document.querySelector("#ci-file").addEventListener("change", function (event) {
      if (!allowedExtensions.exec(event.target.value)) {
        alert('Arquivo com formato inválido!');
        document.querySelector("#ci-file").value = null;
        return false;
      } else {
        selected_file = event.target.files[0];
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
          var json_string = JSON.stringify(normalizeKeys(res), 2, 2);
          document.getElementById('ci-file-json').value = btoa(json_string);
        };
        file_reader.readAsBinaryString(selected_file);
      }
    });

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

})(jQuery);