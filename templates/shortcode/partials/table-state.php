<div class="cm-description">
  <h4><?php echo $post_meta['cm_description']; ?>, por estado</h4>
</div>
<div class="cm-table-responsive">
  <table class="cm-table" id="table-state">
    <thead>
      <tr>
        <th class="transparent"></th>
        <th colspan="5" style="text-transform: uppercase;">[TABLE_TITLE]</th>
      </tr>
      <tr>
        <th style="vertical-align:middle;text-transform: uppercase;">ESTADO</th>
        <th colspan="2">Valor Faturado<sup>[SOURCE_FONT]</sup><br>(000)</th>
        <th colspan="2" style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th style="vertical-align:middle;">Share (%)</th>
      </tr>
    </thead>
    <tbody>

      <tr>
        <td style="text-transform: uppercase;">ACRE</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['acre']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['acre']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['acre']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">ALAGOAS</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['alagoas']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['alagoas']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['alagoas']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">AMAPÁ</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['amapa']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['amapa']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['amapa']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">AMAZONAS</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['amazonas']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['amazonas']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['amazonas']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">BAHIA</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['bahia']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['bahia']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['bahia']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">CEARÁ</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['ceara']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['ceara']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['ceara']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">DISTRITO FEDERAL</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['distrito_federal']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['distrito_federal']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['distrito_federal']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">ESPÍRITO SANTO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['espirito_santo']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['espirito_santo']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['espirito_santo']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">GOIÁS</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['goias']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['goias']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['goias']['share']; ?>%</td>
      </tr>


      <tr>
        <td style="text-transform: uppercase;">MARANHÃO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['maranhao']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['maranhao']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['maranhao']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">MATO GROSSO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['mato_grosso']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['mato_grosso']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['mato_grosso']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">MATO GROSSO DO SUL</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['mato_grosso_do_sul']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['mato_grosso_do_sul']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['mato_grosso_do_sul']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">MINAS GERAIS</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['minas_gerais']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['minas_gerais']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['minas_gerais']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">PARÁ</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['para']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['para']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['para']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">PARAÍBA</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['paraiba']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['paraiba']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['paraiba']['share']; ?>%</td>
      </tr>


      <tr>
        <td style="text-transform: uppercase;">PARANÁ</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['parana']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['parana']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['parana']['share']; ?>%</td>
      </tr>


      <tr>
        <td style="text-transform: uppercase;">PERNAMBUCO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['pernambuco']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['pernambuco']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['pernambuco']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">PIAUÍ</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['piaui']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['piaui']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['piaui']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">RIO DE JANEIRO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['rio_de_janeiro']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['rio_de_janeiro']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['rio_de_janeiro']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">RIO GRANDE DO NORTE</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['rio_grande_do_norte']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['rio_grande_do_norte']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['rio_grande_do_norte']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">RIO GRANDE DO SUL</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['rio_grande_do_sul']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['rio_grande_do_sul']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['rio_grande_do_sul']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">RONDÔNIA</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['rondonia']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['rondonia']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['rondonia']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">RORAIMA</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['roraima']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['roraima']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['roraima']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">SANTA CATARINA</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['santa_catarina']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['santa_catarina']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['santa_catarina']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">SÃO PAULO</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['sao_paulo']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['sao_paulo']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['sao_paulo']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">SERGIPE</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['sergipe']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['sergipe']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['sergipe']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">TOCANTINS</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['tocantins']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['tocantins']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['tocantins']['share']; ?>%</td>
      </tr>

      <tr>
        <td style="text-transform: uppercase;">MERCADO NACIONAL</td>
        <td style="border-right-color: transparent;">R$</td>
        <td><?php echo $data['brasil']['real']; ?></td>
        <td style="border-right-color:transparent;text-align: left;">$</td>
        <td class="text-right"><?php echo $data['brasil']['dollar']; ?></td>
        <td class="text-center"><?php echo $data['brasil']['share']; ?>%</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Total</td>
        <td style="text-align: left;border-right-color: transparent;">R$</td>
        <td><?php echo $data['total']['real']; ?></td>
        <td style="background:#00936c;border-right-color:transparent;text-align: left">$</td>
        <td style="background:#00936c;text-align: right;"><?php echo $data['total']['dollar']; ?></td>
      </tr>
    </tfoot>

  </table>
</div>