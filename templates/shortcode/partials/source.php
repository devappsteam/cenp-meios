<div class="cm-fonte">
  <div class="cm-title">
    <h4>Fonte</h4>
  </div>
  <div class="cm-font-content">
    [SOURCE]
    <br><br>
    <p style="border: 1px solid silver;padding:10px;[DISPLAY]">
      <strong class="h4" style="font-weight:bold">Nota Técnica do CTCM</strong>
      <br><br>
      [NOTE]
    </p>
  </div>
</div>
<div class="cm-footer">
  <div class="cm-accordion">
    <a href="javascript:void(0);" id="accordion" class="accordion cm-link">[AGENCY_TITLE]</a>
    <div class="panel">
      [AGENCY_TEXT]
    </div>
  </div>
  <a href="/cenp-meio/cenp-meios-faq/" class="cm-link">Perguntas e Respostas (FAQ)</a>
  <a href="/sobre-o-cenp/comite-tecnico-cenp-meios/" class="cm-link">Comitê Técnico Cenp-Meios</a>
</div>

<script>
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  }
</script>