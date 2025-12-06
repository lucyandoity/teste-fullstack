<section class="p-6 md:p-10 lg:p-12 flex items-center justify-center min-h-[500px]">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-border-light p-6">
    <h2 class="text-xl font-semibold text-text-dark mb-6">Importar Prestadores</h2>
    <p class="text-sm text-text-secondary mb-4">
      O arquivo deve estar no formato CSV, XLS ou XLSX (Máx. 25MB) com as colunas: <br>
      <code>Nome, Email, Telefone, Nome do Serviço, Descrição do Serviço, Valor</code>
    </p>

    <?php echo $this->Form->create('Importacao', array('type' => 'file', 'class' => 'space-y-6')); ?>

    <div
      class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-border-light rounded-lg cursor-pointer bg-soft-gray hover:bg-gray-100 transition duration-150 relative"
      id="dropzone">
      <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
      </svg>
      <p class="text-base text-text-dark font-medium mb-1">Upload Arquivo</p>
      <p class="text-sm text-text-secondary">Pressione para selecionar</p>
      <?php echo $this->Form->file('arquivo', array('class' => 'absolute inset-0 w-full h-full opacity-0 cursor-pointer', 'accept' => '.csv, .xls, .xlsx', 'id' => 'fileInput')); ?>
    </div>

    <!-- Card de Progresso -->
    <div id="progress-card" class="bg-white p-4 rounded-lg border border-red-100 shadow-sm hidden">
      <div class="flex items-center space-x-4">
        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-primary-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-1">
            <p class="text-sm font-medium text-text-dark truncate" id="filename-display">arquivo.csv</p>
            <span class="text-sm font-semibold text-primary-red" id="percentage-display">0%</span>
          </div>
          <p class="text-xs text-text-secondary mb-2" id="filesize-display">0 KB</p>
          <div class="w-full bg-gray-200 rounded-full h-1.5">
            <div class="bg-primary-red h-1.5 rounded-full" style="width: 0%" id="progress-bar"></div>
          </div>
        </div>
        <div class="flex-shrink-0 hidden" id="check-icon">
          <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
      </div>
    </div>

    <div class="flex justify-end space-x-3">
      <?php echo $this->Html->link('Cancelar', array('controller' => 'prestadores', 'action' => 'index'), array('class' => 'px-4 py-3 border border-border-light text-text-dark rounded-lg hover:bg-gray-50 transition duration-150 font-medium')); ?>
      <button type="submit"
        class="px-6 py-3 bg-primary-red text-white rounded-lg shadow-md hover:bg-red-600 transition duration-150 font-medium">
        Importar Arquivo
      </button>
    </div>

    <?php echo $this->Form->end(); ?>
  </div>
  </div>

  <!-- Modal de Sucesso (Client-side) -->
  <div id="flash-modal-overlay"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 hidden">
    <div
      class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center mx-4 transform transition-all scale-100">
      <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-50 mb-6">
        <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 mb-2">Lista enviada com sucesso!</h3>
      <p class="text-gray-500 mb-8 font-medium">Confira seus servidores na tabela abaixo</p>
      <button id="btn-modal-continue"
        class="w-full inline-flex justify-center items-center rounded-xl px-4 py-3 bg-primary-red text-white font-semibold text-base hover:opacity-90 transition duration-200">
        Continuar
      </button>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('fileInput');
    var form = document.querySelector('form');
    var progressCard = document.getElementById('progress-card');
    var dropzone = document.getElementById('dropzone');
    var filenameDisplay = document.getElementById('filename-display');
    var filesizeDisplay = document.getElementById('filesize-display');
    var progressBar = document.getElementById('progress-bar');
    var percentageDisplay = document.getElementById('percentage-display');
    var checkIcon = document.getElementById('check-icon');

    fileInput.addEventListener('change', function (e) {
      if (e.target.files && e.target.files[0]) {
        var file = e.target.files[0];
        filenameDisplay.textContent = file.name;
        filesizeDisplay.textContent = (file.size / 1024).toFixed(0) + ' KB';
        progressCard.classList.remove('hidden');
      }
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var file = fileInput.files[0];
      if (!file) return;

      var formData = new FormData(form);
      var xhr = new XMLHttpRequest();

      xhr.upload.addEventListener('progress', function (event) {
        if (event.lengthComputable) {
          var percentComplete = (event.loaded / event.total) * 100;
          progressBar.style.width = percentComplete + '%';
          percentageDisplay.textContent = Math.round(percentComplete) + '%';
        }
      });

      xhr.addEventListener('load', function () {
        if (xhr.status === 200) {
          progressBar.style.width = '100%';
          percentageDisplay.textContent = '100%';
          percentageDisplay.classList.add('text-green-500');
          percentageDisplay.classList.remove('text-primary-red');
          checkIcon.classList.remove('hidden');

          // Show Modal instead of redirecting immediately
          setTimeout(function () {
            document.getElementById('flash-modal-overlay').classList.remove('hidden');
          }, 500);
        } else {
          alert('Erro no upload. Tente novamente.');
        }
      });

      xhr.open('POST', form.action, true);
      xhr.onload = function () {
      };
      xhr.send(formData);
    });

    // Modal Continue Button
    document.getElementById('btn-modal-continue').addEventListener('click', function () {
      window.location.href = '<?php echo $this->Html->url(array('controller' => 'prestadores', 'action' => 'index')); ?>';
    });
  });
</script>