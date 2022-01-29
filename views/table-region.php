<div class="cm-section cm-section--white">
  <div class="cm-container">
    <div class="cm-description">
      <h4 class="cm-center cm-uppercase"><?php echo $post_meta['cm_description']; ?> por regiões</h4>
    </div>
    <div class="cm-table-responsive">
      <table class="cm-table" id="table_region">
        <thead>
          <tr>
            <th style="background: transparent;border: none;"></th>
            <th colspan="5" style="text-transform: uppercase;">[TABLE_TITLE]</th>
          </tr>
          <tr>
            <th style="vertical-align:middle;text-transform: uppercase;">REGIÃO</th>
            <th colspan="2">Valor Faturado<sup><?php echo $post_meta['cm_source_real']; ?></sup><br>(000)</th>
            <th colspan="2" style="background:#00936c;vertical-align: middle;">USD<sup><?php echo $post_meta['cm_source_dollar']; ?></sup> (000)</th>
            <th style="vertical-align:middle;">Share (%)</th>
          </tr>
        </thead>
        <tbody>
          <!-- Traz todos os dados referente ao meio -->
          <tr>
            <!-- SEM GRUPO -->
            <td style="text-transform: uppercase;">CENTRO-OESTE</td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['centro_oeste']['real']; ?></td>
            <td style="border-right-color:transparent;text-align: left;">$</td>
            <td class="text-right"><?php echo $data['centro_oeste']['dollar']; ?></td>
            <td class="text-right"><?php echo $data['centro_oeste']['share']; ?>%</td>
            <!-- COM GRUPO -->
          </tr>
          <tr>
            <td style="text-transform: uppercase;">NORDESTE</td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['nordeste']['real']; ?></td>
            <td style="border-right-color:transparent;text-align: left;">$</td>
            <td class="text-right"><?php echo $data['nordeste']['dollar']; ?></td>
            <td class="text-right"><?php echo $data['nordeste']['share']; ?>%</td>
          </tr>
          <tr>
            <td style="text-transform: uppercase;">NORTE</td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['norte']['real']; ?></td>
            <td style="border-right-color:transparent;text-align: left;">$</td>
            <td class="text-right"><?php echo $data['norte']['dollar']; ?></td>
            <td class="text-right"><?php echo $data['norte']['share']; ?>%</td>
          </tr>
          <tr>
            <td style="text-transform: uppercase;">SUDESTE</td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['sudeste']['real']; ?></td>
            <td style="border-right-color:transparent;text-align: left;">$</td>
            <td class="text-right"><?php echo $data['sudeste']['dollar']; ?></td>
            <td class="text-right"><?php echo $data['sudeste']['share']; ?>%</td>
          </tr>
          <tr>
            <td style="text-transform: uppercase;">SUL</td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['sul']['real']; ?></td>
            <td style="border-right-color:transparent;text-align: left;">$</td>
            <td class="text-right"><?php echo $data['sul']['dollar']; ?></td>
            <td class="text-right"><?php echo $data['sul']['share']; ?>%</td>
          </tr>
          <tr>
            <td style="text-transform: uppercase;">MERC. NACIONAL<sup>[SOURCE_MERCADO]</sup></td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['merc_nascional']['real']; ?></td>
            <td style="border-right-color:transparent;text-align: left;">$</td>
            <td class="text-right"><?php echo $data['merc_nascional']['dollar']; ?></td>
            <td class="text-right"><?php echo $data['merc_nascional']['share']; ?>%</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td>Total</td>
            <td style="border-right-color: transparent;">R$</td>
            <td class="text-right"><?php echo $data['total']['real']; ?></td>
            <td class="text-left" style="background:#00936c;border-right-color: transparent;text-align: left;">$</td>
            <td class="text-right" style="background:#00936c;text-align: right"><?php echo $data['total']['dollar']; ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
<div class="cm-section cm-section--white">
  <div class="cm-container">
    <div class="cm-chart" id="cenp-chart-region"></div>
    <script defer>
			(function($) {
				$(document).ready(function(){
					setTimeout(function(){
			  			google.charts.load("current", {
							packages: ["corechart"]
						  });
					  google.charts.setOnLoadCallback(drawChart);

					  function drawChart() {
						var data = google.visualization.arrayToDataTable([
						  ['Task', 'Hours per Day'],
						  ['CENTRO-OESTE', <?php echo str_replace('.', '', $data['centro_oeste']['real']); ?>],
						  ['NORDESTE', <?php echo str_replace('.', '', $data['nordeste']['real']); ?>],
						  ['NORTE', <?php echo str_replace('.', '', $data['norte']['real']); ?>],
						  ['SUDESTE', <?php echo str_replace('.', '', $data['sudeste']['real']); ?>],
						  ['SUL', <?php echo str_replace('.', '', $data['sul']['real']); ?>],
						  ['MERC. NACIONAL', <?php echo str_replace('.', '', $data['merc_nascional']['real']); ?>]
						]);

						var options = {
						  pieSliceTextStyle: {
							fontSize: 8
						  },
						  sliceVisibilityThreshold: 0,
						  title: "",
						  is3D: true,
						  pieSliceText: 'none',
						  enableInteractivity: false,
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

						var chart = new google.visualization.PieChart(document.getElementById("cenp-chart-region"));
						chart.draw(data, options);

						jQuery("#cenp-chart-region > div > div:nth-child(1) > div > svg > g:nth-child(9) > g:nth-child(11) > g:nth-child(1) > text").html(`<tspan>MERC. NACIONAL<tspan dy ="-10" font-size="8">[SOURCE_MERCADO]</tspan></tspan>`);

						$(window).resize(function() {
						  chart.draw(data, options);
						  jQuery("#cenp-chart-region > div > div:nth-child(1) > div > svg > g:nth-child(9) > g:nth-child(11) > g:nth-child(1) > text").html(`<tspan>MERC. NACIONAL<tspan dy ="-10" font-size="8">[SOURCE_MERCADO]</tspan></tspan>`);
						});
					  }
					},500);
				});
		  })(jQuery);
    </script>
  </div>
</div>