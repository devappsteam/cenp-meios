<div class="cm-description">
  <h4><?php echo $post_meta['cm_description']; ?>, por meios e regiões<sup>[SOURCE_MEIOS_REGIOES]</sup></h4>
</div>
<div class="cm-table-responsive">
  <table class="cm-table" style="font-size:10px" >
    <thead>
      <tr>
        <th class="transparent"></th>
        <th colspan="9" style="text-transform: uppercase;">[TABLE_TITLE]</th>
      </tr>
      <tr>
        <th>REGIÕES</th>
        <th colspan="3">CENTRO-OESTE</th>
        <th colspan="3">NORDESTE</th>
        <th colspan="3">NORTE</th>
      </tr>
      <tr>
        <th>MEIOS</th>
        <th>Valor Faturado<sup>[SOURCE_FONT]</sup><br>(000)</th>
        <th style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th>Share (%)</th>
        <th>Valor Faturado<sup>[SOURCE_FONT]</sup><br>(000)</th>
        <th style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th>Share (%)</th>
        <th>Valor Faturado<sup>[SOURCE_FONT]</sup><br>(000)</th>
        <th style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th>Share (%)</th>
      </tr>
    </thead>
    <tbody>

      <!-- Traz todos os dados referente ao meio -->

      <tr>
        <td>CINEMA</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['cinema']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['cinema']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['cinema']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['cinema']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['cinema']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['cinema']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['cinema']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['cinema']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['cinema']['norte']['share']; ?>%</td>

      </tr>
      <tr>
        <td>INTERNET⁵</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['norte']['share']; ?>%</td>
      </tr>
      <tr class="cenpMeiosSub">
        <td>INTERNET<br>ÁUDIO</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['audio']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['audio']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['audio']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['audio']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['audio']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['audio']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['audio']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['audio']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['audio']['norte']['share']; ?>%</td>
      </tr>
      <tr class="cenpMeiosSub">
        <td>INTERNET<br>BUSCA</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['busca']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['busca']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['busca']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['busca']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['busca']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['busca']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['busca']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['busca']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['busca']['norte']['share']; ?>%</td>
      </tr>
      <tr class="cenpMeiosSub">
        <td>INTERNET<br>DISPLAY E<br>OUTROS</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['outros']['centro_oeste']['real']; ?></div>
        </td>

        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['outros']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['outros']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['outros']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['outros']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['outros']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['outros']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['outros']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['outros']['norte']['share']; ?>%</td>
      </tr>
      <tr class="cenpMeiosSub">
        <td>INTERNET<br>SOCIAL</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['social']['centro_oeste']['real']; ?></div>
        </td>

        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['social']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['social']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['social']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['social']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['social']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['social']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['social']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['social']['norte']['share']; ?>%</td>
      </tr>
      <tr class="cenpMeiosSub">
        <td>INTERNET<br>VÍDEO</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['video']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['video']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['video']['centro_oeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['video']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['video']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['video']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['video']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['internet']['meios']['video']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['meios']['video']['norte']['share']; ?>%</td>
      </tr>
      <tr>
        <td>JORNAL</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['jornal']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['jornal']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['jornal']['centro_oeste']['share']; ?>%</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['jornal']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['jornal']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['jornal']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['jornal']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['jornal']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['jornal']['norte']['share']; ?>%</td>
      </tr>
      <tr>
        <td>OOH/MÍDIA<br>EXTERIOR</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['midia_exterior']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['midia_exterior']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['midia_exterior']['centro_oeste']['share']; ?>%</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['midia_exterior']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['midia_exterior']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['midia_exterior']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['midia_exterior']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['midia_exterior']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['midia_exterior']['norte']['share']; ?>%</td>
      </tr>
      <tr>
        <td>RÁDIO</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['radio']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['radio']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['radio']['centro_oeste']['share']; ?>%</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['radio']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['radio']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['radio']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['radio']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['radio']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['radio']['norte']['share']; ?>%</td>
      </tr>
      <tr>
        <td>REVISTA⁵</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['revista']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['revista']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['revista']['centro_oeste']['share']; ?>%</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['revista']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['revista']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['revista']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['revista']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['revista']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['revista']['norte']['share']; ?>%</td>
      </tr>
      <tr>
        <td>TELEVISÃO<br>ABERTA</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['tv_aberta']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['tv_aberta']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_aberta']['centro_oeste']['share']; ?>%</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['tv_aberta']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['tv_aberta']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_aberta']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['tv_aberta']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['tv_aberta']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_aberta']['norte']['share']; ?>%</td>
      </tr>
      <tr>
        <td>TELEVISÃO<br>POR<br>ASSINATURA</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['tv_assinada']['centro_oeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['tv_assinada']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_assinada']['centro_oeste']['share']; ?>%</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['tv_assinada']['nordeste']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['tv_assinada']['nordeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_assinada']['nordeste']['share']; ?>%</td>

        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['tv_assinada']['norte']['real']; ?></div>
        </td>
        <td>
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['tv_assinada']['norte']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_assinada']['norte']['share']; ?>%</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Total</td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['total']['centro_oeste']['real']; ?></div>
        </td>
        <td style="background:#00936c;">
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['total']['centro_oeste']['dollar']; ?></div>
        </td>
        <td class="tfoot-total"></td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['total']['nordeste']['real']; ?></div>
        </td>
        <td style="background:#00936c;">
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['total']['nordeste']['dollar']; ?></div>
        </td>
        <td class="tfoot-total"></td>
        <td>
          <div class="v-left">R$</div>
          <div class="v-right"><?php echo $data['total']['norte']['real']; ?></div>
        </td>
        <td style="background:#00936c;">
          <div class="v-left">$</div>
          <div class="v-right"><?php echo $data['total']['norte']['dollar']; ?></div>
        </td>
        <td class="tfoot-total"></td>
      </tr>
    </tfoot>

  </table>
</div>
<div class="cm-table-responsive">
  <table class="cm-table" style="font-size:10px">
    <thead>
      <tr>
        <th class="transparent"></th>
        <th colspan="9" style="text-transform: uppercase;">[TABLE_TITLE]</th>
      </tr>
      <tr>
        <th>REGIÕES</th>
        <th colspan="3">SUDESTE</th>
        <th colspan="3">SUL</th>
        <th colspan="3">MERCADO NACIONAL<sup>6</sup></th>
      </tr>
      <tr>
        <th>MEIOS</th>
        <th>Valor Faturado⁴<br>(000)</th>
        <th style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th>Share (%)</th>
        <th>Valor Faturado⁴<br>(000)</th>
        <th style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th>Share (%)</th>
        <th>Valor Faturado⁴<br>(000)</th>
        <th style="background:#00936c;vertical-align: middle;">USD<sup>[SOURCE_DOLLAR]</sup> (000)</th>
        <th>Share (%)</th>
      </tr>
    </thead>
    <tbody>

      <!-- Traz todos os dados referente ao meio -->

      <tr>
        <td>CINEMA</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['cinema']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['cinema']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['cinema']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['cinema']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['cinema']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['cinema']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['cinema']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['cinema']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['cinema']['mer_nacional']['share']; ?>%</td>

      </tr>
      <tr>
        <td>INTERNET⁵</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['internet']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['internet']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['internet']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['internet']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['sul']['share']; ?>%</td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['internet']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['internet']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['internet']['mer_nacional']['share']; ?>%</td>
      </tr>
      <?php if (isset($data['internet']['meios'])) { ?>
        <tr class="cenpMeiosSub">
          <td>INTERNET<br>ÁUDIO</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['audio']['sudeste']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['audio']['sudeste']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['audio']['sudeste']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['audio']['sul']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['audio']['sul']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['audio']['sul']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['audio']['mer_nacional']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['audio']['mer_nacional']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['audio']['mer_nacional']['share']; ?>%</td>
        </tr>
        <tr class="cenpMeiosSub">
          <td>INTERNET<br>BUSCA</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['busca']['sudeste']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['busca']['sudeste']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['busca']['sudeste']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['busca']['sul']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['busca']['sul']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['busca']['sul']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['busca']['mer_nacional']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['busca']['mer_nacional']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['busca']['mer_nacional']['share']; ?>%</td>
        </tr>
        <tr class="cenpMeiosSub">
          <td>INTERNET<br>DISPLAY E<br>OUTROS</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['outros']['sudeste']['real']; ?></div>
          </td>

          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['outros']['sudeste']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['outros']['sudeste']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['outros']['sul']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['outros']['sul']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['outros']['sul']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['outros']['mer_nacional']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['outros']['mer_nacional']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['outros']['mer_nacional']['share']; ?>%</td>
        </tr>
        <tr class="cenpMeiosSub">
          <td>INTERNET<br>SOCIAL</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['social']['sudeste']['real']; ?></div>
          </td>

          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['social']['sudeste']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['social']['sudeste']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['social']['sul']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['social']['sul']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['social']['sul']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['social']['mer_nacional']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['social']['mer_nacional']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['social']['mer_nacional']['share']; ?>%</td>
        </tr>
        <tr class="cenpMeiosSub">
          <td>INTERNET<br>VÍDEO</td>
          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['video']['sudeste']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['video']['sudeste']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['video']['sudeste']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['video']['sul']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['video']['sul']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['video']['sul']['share']; ?>%</td>

          <td>
            <div class="v-left">R$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['video']['mer_nacional']['real']; ?></div>
          </td>
          <td>
            <div class="v-left">$</div>
            <div class="v-right"><?php echo $data['internet']['meios']['video']['mer_nacional']['dollar']; ?></div>
          </td>
          <td class="v-center"><?php echo $data['internet']['meios']['video']['mer_nacional']['share']; ?>%</td>
        </tr>
      <?php } ?>
      <tr>
        <td>JORNAL</td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['jornal']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['jornal']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['jornal']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['jornal']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['jornal']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['jornal']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['jornal']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['jornal']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['jornal']['mer_nacional']['share']; ?>%</td>
      </tr>
      <tr>
        <td>OOH/MÍDIA<br>EXTERIOR</td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['midia_exterior']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['midia_exterior']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['midia_exterior']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['midia_exterior']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['midia_exterior']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['midia_exterior']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['midia_exterior']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['midia_exterior']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['midia_exterior']['mer_nacional']['share']; ?>%</td>
      </tr>
      <tr>
        <td>RÁDIO</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['radio']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['radio']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['radio']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['radio']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['radio']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['radio']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['radio']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['radio']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['radio']['mer_nacional']['share']; ?>%</td>
      </tr>
      <tr>
        <td>REVISTA⁵</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['revista']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['revista']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['revista']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['revista']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['revista']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['revista']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['revista']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['revista']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['revista']['mer_nacional']['share']; ?>%</td>
      </tr>
      <tr>
        <td>TELEVISÃO<br>ABERTA</td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_aberta']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_aberta']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_aberta']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_aberta']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_aberta']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_aberta']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_aberta']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_aberta']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_aberta']['mer_nacional']['share']; ?>%</td>
      </tr>
      <tr>
        <td>TELEVISÃO<br>POR<br>ASSINATURA⁵</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_assinada']['sudeste']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_assinada']['sudeste']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_assinada']['sudeste']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_assinada']['sul']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_assinada']['sul']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_assinada']['sul']['share']; ?>%</td>

        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_assinada']['mer_nacional']['real']; ?></div>
        </td>
        <td>
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['tv_assinada']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="v-center"><?php echo $data['tv_assinada']['mer_nacional']['share']; ?>%</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>Total</td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['total']['sudeste']['real']; ?></div>
        </td>
        <td style="background:#00936c;">
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['total']['sudeste']['dollar']; ?></div>
        </td>
        <td class="tfoot-total"></td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['total']['sul']['real']; ?></div>
        </td>
        <td style="background:#00936c;">
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['total']['sul']['dollar']; ?></div>
        </td>
        <td class="tfoot-total"></td>
        <td>
          <div class="col-md-4 v-left">R$</div>
          <div class="col-md-8 v-right"><?php echo $data['total']['mer_nacional']['real']; ?></div>
        </td>
        <td style="background:#00936c;">
          <div class="col-md-4 v-left">$</div>
          <div class="col-md-8 v-right"><?php echo $data['total']['mer_nacional']['dollar']; ?></div>
        </td>
        <td class="tfoot-total"></td>
      </tr>
    </tfoot>

  </table>
</div>