<div class="cm-description">
  <h4><?php echo $post_meta['cm_description']; ?>, por meio de comunicação</h4>
  <span class="cm-modified">Atualizado em [UPDATEDAT]</span>
</div>
<div class="cm-table-responsive">
  <table class="cm-table">
    <thead>
      <tr>
        <th style="background: transparent;border: none;"></th>
        <th colspan="5" class="cm-period">[TABLE_TITLE]</th>
      </tr>
      <tr>
        <th style="vertical-align:middle;text-transform: uppercase;">Meio</th>
        <th colspan="2">Valor Faturado<sup>[SOURCE_FONT]</sup><br>(000)</th>
        <th colspan="2" style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup>(000)</th>
        <th style="vertical-align:middle;">Share (%)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!-- SEM GRUPO -->
        <td style="text-transform: uppercase;">Cinema</td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_cinema" class="text-right"><?php echo $data['cinema']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_cinema"><?php echo $data['cinema']['dollar']; ?></td>
        <td class="text-center" id="share_cinema"><?php echo $data['cinema']['share']; ?>%</td>
        <!-- COM GRUPO -->
      </tr>
      <tr>
        <!-- SEM GRUPO -->
        <td style="text-transform: uppercase;">Internet<sup>[SOURCE_MIDIA]</sup><br>
          <?php if (isset($data['internet']['meios'])) { ?>
            <table class="cm-meio-interna">
              <tbody>
                <tr>
                  <td style="border-top: 2px solid #fff; border-left: 2px solid #fff"></td>
                  <td style="font-size:12px;text-align: center;" colspan="2">VALOR FATURADO<sup>[SOURCE_FONT]</sup><br>(000)</td>
                  <td style="font-size:12px;text-align: center;background:#00936c;color:#FFF" colspan="2">USD<sup>[SOURCE_DOLLAR]</sup>(000)</td>
                  <td style="font-size:12px;text-align: center;">SHARE (%)</td>
                </tr>
                <tr>
                  <td style="font-size:12px;">ÁUDIO</td>
                  <td style="font-size:12px;border-right-color: transparent;">R$</td>
                  <td style="font-size:12px;" id="total_audio" class="text-right"><?php echo $data['internet']['meios']['audio']['real']; ?></td>
                  <td style="font-size:12px;text-align: left;border-right-color: transparent;">$</td>
                  <td style="font-size:12px;text-align: right;" id="dollar_audio"><?php echo $data['internet']['meios']['audio']['dollar']; ?></td>
                  <td style="font-size:12px;text-align: right;" id="share_audio"><?php echo $data['internet']['meios']['audio']['share']; ?>%</td>
                </tr>
                <tr>
                  <td style="font-size:12px;">BUSCA</td>
                  <td style="font-size:12px;border-right-color: transparent;">R$</td>
                  <td style="font-size:12px;" id="total_busca" class="text-right"><?php echo $data['internet']['meios']['busca']['real']; ?></td>
                  <td style="font-size:12px;text-align: left;border-right-color: transparent;">$</td>
                  <td style="font-size:12px;text-align: right;" id="dollar_busca"><?php echo $data['internet']['meios']['busca']['dollar']; ?></td>
                  <td style="font-size:12px;text-align: right;" id="share_busca"><?php echo $data['internet']['meios']['busca']['share']; ?>%</td>
                </tr>

                <!-- Outros -->
                <tr>
                  <td style="font-size:12px;">DISPLAY E OUTROS</td>
                  <td style="font-size:12px;border-right-color: transparent;">R$</td>
                  <td style="font-size:12px;" id="total_outros" class="text-right"><?php echo $data['internet']['meios']['outros']['real']; ?></td>
                  <td style="font-size:12px;text-align: left;border-right-color: transparent;">$</td>
                  <td style="font-size:12px;text-align: right;" id="dollar_outros"><?php echo $data['internet']['meios']['outros']['dollar']; ?></td>
                  <td style="font-size:12px;text-align: right;" id="share_outros"><?php echo $data['internet']['meios']['outros']['share']; ?>%</td>
                </tr>

                <!-- Social -->
                <tr>
                  <td style="font-size:12px;">SOCIAL</td>
                  <td style="font-size:12px;border-right-color: transparent;">R$</td>
                  <td style="font-size:12px;" id="total_social" class="text-right"><?php echo $data['internet']['meios']['social']['real']; ?></td>
                  <td style="font-size:12px;text-align: left;border-right-color: transparent;">$</td>
                  <td style="font-size:12px;text-align: right;" id="dollar_social"><?php echo $data['internet']['meios']['social']['dollar']; ?></td>
                  <td style="font-size:12px;text-align: right;" id="share_social"><?php echo $data['internet']['meios']['social']['share']; ?>%</td>
                </tr>

                <!-- Video -->
                <tr>
                  <td style="font-size:12px;">VÍDEO</td>
                  <td style="font-size:12px;border-right-color: transparent;">R$</td>
                  <td style="font-size:12px;" id="total_video" class="text-right"><?php echo $data['internet']['meios']['video']['real']; ?></td>
                  <td style="font-size:12px;text-align: left;border-right-color: transparent;">$</td>
                  <td style="font-size:12px;text-align: right;" id="dollar_video"><?php echo $data['internet']['meios']['video']['dollar']; ?></td>
                  <td style="font-size:12px;text-align: right;" id="share_video"><?php echo $data['internet']['meios']['video']['share']; ?>%</td>
                </tr>
              </tbody>
            </table>
          <?php } ?>
        </td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_internet" class="text-right"><?php echo $data['internet']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_internet"><?php echo $data['internet']['dollar']; ?></td>
        <td class="text-center" id="share_internet"><?php echo $data['internet']['share']; ?>%</td>
      </tr>
      <!-- Jornal -->
      <tr>
        <td style="text-transform: uppercase;">JORNAL<sup>[SOURCE_MIDIA]</sup></td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_jornal" class="text-right"><?php echo $data['jornal']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_jornal"><?php echo $data['jornal']['dollar']; ?></td>
        <td class="text-center" id="share_jornal"><?php echo $data['jornal']['share']; ?>%</td>
      </tr>
      <!-- Midia externa -->
      <tr>
        <td style="text-transform: uppercase;">OOH/MÍDIA EXTERIOR</td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_ext" class="text-right"><?php echo $data['midia_exterior']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_ext"><?php echo $data['midia_exterior']['dollar']; ?></td>
        <td class="text-center" id="share_ext"><?php echo $data['midia_exterior']['share']; ?>%</td>
      </tr>
      <!-- Radio -->
      <tr>
        <td style="text-transform: uppercase;">RÁDIO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_radio" class="text-right"><?php echo $data['radio']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_radio"><?php echo $data['radio']['dollar']; ?></td>
        <td class="text-center" id="share_radio"><?php echo $data['radio']['share']; ?>%</td>
      </tr>

      <!-- Revista -->
      <tr>
        <td style="text-transform: uppercase;">REVISTA<sup>[SOURCE_MIDIA]</sup></td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_revista" class="text-right"><?php echo $data['revista']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_revista"><?php echo $data['revista']['dollar']; ?></td>
        <td class="text-center" id="share_revista"><?php echo $data['revista']['share']; ?>%</td>
      </tr>

      <!-- Tv Aberta -->
      <tr>
        <td style="text-transform: uppercase;">TELEVISÃO ABERTA</td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_aberta" class="text-right"><?php echo $data['tv_aberta']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_aberta"><?php echo $data['tv_aberta']['dollar']; ?></td>
        <td class="text-center" id="share_aberta"><?php echo $data['tv_aberta']['share']; ?>%</td>
      </tr>

      <!-- TV Assinada -->
      <tr>
        <td style="text-transform: uppercase;">TELEVISÃO POR ASSINATURA<sup>[SOURCE_MIDIA]</sup></td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_assinada" class="text-right"><?php echo $data['tv_assinada']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right" id="dollar_assinada"><?php echo $data['tv_assinada']['dollar']; ?></td>
        <td class="text-center" id="share_assinada"><?php echo $data['tv_assinada']['share']; ?>%</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Total</td>
        <td style="border-right-color: transparent;">R$</td>
        <td id="total_geral" class="text-right"><?php echo $data['total']['real']; ?></td>
        <td class="text-left" style="background:#00936c;border-right-color: transparent;">$</td>
        <td class="text-right" style="background:#00936c;text-align: right" id="dollar_geral"><?php echo $data['total']['dollar']; ?></td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="cm-charts">
  <div class="cm-title">
    <h4>
      GRÁFICO TODOS OS MEIOS
    </h4>
  </div>
  <div class="cm-chart" id="cenp-chart-todos-meios"></div>
  <script defer>
    (function($) {
      $(function() {
        google.charts.load("current", {
          packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Cinema', <?php echo  str_replace(',', '.', $data['cinema']['share']); ?>],
            ['Internet', <?php echo str_replace(',', '.', $data['internet']['share']); ?>],
            ['Jornal', <?php echo  str_replace(',', '.', $data['jornal']['share']); ?>],
            ['OOH/MÍDIA exterior', <?php echo str_replace(',', '.', $data['midia_exterior']['share']); ?>],
            ['Rádio', <?php echo str_replace(',', '.', $data['radio']['share']); ?>],
            ['Revista', <?php echo str_replace(',', '.', $data['revista']['share']); ?>],
            ['Televisão aberta', <?php echo str_replace(',', '.', $data['tv_aberta']['share']); ?>],
            ['Televisão por assinatura', <?php echo str_replace(',', '.', $data['tv_assinada']['share']); ?>],
          ]);

          var options = {
            pieSliceTextStyle: {
              fontSize: 8
            },
            sliceVisibilityThreshold: 0,
            title: "",
            enableInteractivity: false,
            is3D: true,
            pieSliceText: 'none',
            chartArea: {
              width: '100%',
              height: '100%'
            },
            legend: {
              position: 'labeled',
              alignment: 'center',
              textStyle: {
                fontSize: 11,
                bold: true
              },
            },
            tooltip: {
              showColorCode: true,
              text: 'percentage',
            },
          };

          var chart = new google.visualization.PieChart(document.getElementById("cenp-chart-todos-meios"));
          chart.draw(data, options);

          jQuery(window).resize(function() {
            chart.draw(data, options);
          });

          jQuery('#cenp-chart-todos-meios svg > g:nth-child(11) > g:nth-child(3) > g:nth-child(1) > text').html(`<tspan>Internet<tspan dy ="-10" font-size="8">[SOURCE_MIDIA]</tspan></tspan>`);
          jQuery("#cenp-chart-todos-meios svg > g:nth-child(11) > g:nth-child(5) > g:nth-child(1) > text").html(`<tspan>Jornal<tspan dy ="-10" font-size="8">[SOURCE_MIDIA]</tspan></tspan>`);
          jQuery("#cenp-chart-todos-meios svg > g:nth-child(11) > g:nth-child(11) > g:nth-child(1) > text").html(`<tspan>Revista<tspan dy ="-10" font-size="8">[SOURCE_MIDIA]</tspan></tspan>`);
          jQuery("#cenp-chart-todos-meios svg > g:nth-child(11) > g:nth-child(15) > g:nth-child(1) > text").html(`<tspan>Televisão por Assinatura<tspan dy ="-10" font-size="8">[SOURCE_MIDIA]</tspan></tspan>`);

        }
      });
    })(jQuery);
  </script>
  <?php if (isset($data['internet']['meios'])) { ?>
    <div class="cm-title">
      <h4>
        GRÁFICO MEIO INTERNET
      </h4>
    </div>
    <div class="cm-chart" id="cenp-chart-internet"></div>
    <script defer>
      (function($) {
        $(function() {
          google.charts.load("current", {
            packages: ["corechart"]
          });
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Task', 'Hours per Day'],
              ['Áudio', <?php echo  str_replace(',', '.', $data['internet']['meios']['audio']['share']); ?>],
              ['Busca', <?php echo  str_replace(',', '.', $data['internet']['meios']['busca']['share']); ?>],
              ['Display e Outros', <?php echo  str_replace(',', '.', $data['internet']['meios']['outros']['share']); ?>],
              ['Social', <?php echo  str_replace(',', '.', $data['internet']['meios']['social']['share']); ?>],
              ['Vídeos', <?php echo  str_replace(',', '.', $data['internet']['meios']['video']['share']); ?>]
            ]);

            var options = {
              pieSliceTextStyle: {
                fontSize: 8
              },
              sliceVisibilityThreshold: 0,
              title: "",
              is3D: true,
              pieSliceText: 'none',
              chartArea: {
                width: '100%',
                height: '100%'
              },
              legend: {

                position: 'labeled',
                alignment: 'center',
                textStyle: {
                  fontSize: 11,
                  bold: true
                },
              },
              tooltip: {
                showColorCode: true,
                text: 'percentage'
              },
            };

            var chart = new google.visualization.PieChart(document.getElementById("cenp-chart-internet"));
            chart.draw(data, options);

            $(window).resize(function() {
              chart.draw(data, options);
            });
          }
        });
      })(jQuery);
    </script>
  <?php } ?>
</div>