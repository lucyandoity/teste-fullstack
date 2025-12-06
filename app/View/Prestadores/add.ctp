<section class="p-6 md:p-10 lg:p-12">
  <div class="max-w-4xl mx-auto bg-white p-8 border border-border-light rounded-xl shadow-custom">
    <h1 class="text-3xl font-semibold text-text-dark mb-2">Cadastro de Prestador de Serviço</h1>
    <p class="text-text-secondary mb-8">Cadastre suas informações e adicione uma foto.</p>

    <?php echo $this->Form->create('Prestador', array('type' => 'file', 'id' => 'register-form')); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <!-- Informações Pessoais -->
    <h2 class="text-xl font-medium text-text-dark mb-4">Informações pessoais</h2>

    <!-- Nome -->
    <div class="mb-6">
      <label for="nome" class="block text-sm font-medium text-text-dark mb-1">Nome Completo</label>
      <?php echo $this->Form->input('nome', array(
        'label' => false,
        'class' => 'w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150',
        'required' => true,
        'div' => false
      )); ?>
    </div>

    <!-- Email -->
    <div class="mb-6">
      <label for="email" class="block text-sm font-medium text-text-dark mb-1">Email</label>
      <?php echo $this->Form->input('email', array(
        'label' => false,
        'class' => 'w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150',
        'required' => true,
        'div' => false
      )); ?>
    </div>

    <!-- Foto e Telefone -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 items-end">
      <div class="sm:col-span-1">
        <label class="block text-sm font-medium text-text-dark mb-1">Sua foto</label>
        <p class="text-xs text-text-secondary mb-2">Ela aparecerá no seu perfil.</p>
        <div class="flex items-center space-x-4">
          <div id="photo-preview-container"
            class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
            <?php
            $fotoPath = '';
            if (!empty($this->request->data['Prestador']['foto']) && is_string($this->request->data['Prestador']['foto'])) {
              $fotoPath = $this->webroot . 'img/' . $this->request->data['Prestador']['foto'];
            }
            ?>
            <?php if (!empty($fotoPath)): ?>
              <img src="<?php echo $fotoPath; ?>" class="w-full h-full object-cover" />
            <?php else: ?>
              <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd">
                </path>
              </svg>
            <?php endif; ?>
          </div>
          <div class="flex-grow">
            <label
              class="flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-border-light rounded-lg cursor-pointer bg-soft-gray hover:bg-gray-100 transition duration-150">
              <div class="flex flex-col items-center justify-center pt-2">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <p class="text-xs text-text-secondary mt-1">Upload Foto</p>
              </div>
              <?php echo $this->Form->file('foto', array('class' => 'hidden', 'accept' => 'image/*')); ?>
            </label>
          </div>
        </div>
      </div>

      <!-- Telefone -->
      <div class="sm:col-span-2">
        <label for="telefone" class="block text-sm font-medium text-text-dark mb-1">Telefone</label>
        <?php echo $this->Form->input('telefone', array(
          'label' => false,
          'class' => 'w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150',
          'placeholder' => '(__) _____-____',
          'div' => false
        )); ?>
      </div>
    </div>

    <hr class="mb-6 border-border-light">

    <!-- Serviço Inicial -->
    <!-- Serviço -->
    <h2 class="text-xl font-medium text-text-dark mb-4">Quais serviço você vai prestar?</h2>

    <div class="flex flex-col md:flex-row gap-4 mb-6 items-end">
      <div class="flex-grow w-full relative">
        <label class="block text-sm font-medium text-text-dark mb-1">Selecione o serviço</label>
        <?php
        $options = array();
        if (isset($servicosDisponiveis)) {
          foreach ($servicosDisponiveis as $servico) {
            $options[$servico['Servico']['nome']] = $servico['Servico']['nome'];
          }
        }
        $selectedService = isset($this->request->data['Servico'][0]['nome']) ? $this->request->data['Servico'][0]['nome'] : null;
        echo $this->Form->input('servico_select', array(
          'options' => $options,
          'empty' => 'Selecione o serviço',
          'default' => $selectedService,
          'label' => false,
          'class' => 'w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150 appearance-none bg-white',
          'id' => 'service-select'
        ));
        ?>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 mt-6">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
          </svg>
        </div>
      </div>
      <button type="button" id="btn-open-modal"
        class="px-4 py-2 bg-primary-red text-white rounded-lg shadow-md hover:bg-red-600 transition duration-150 text-sm font-medium h-[42px] whitespace-nowrap">
        Cadastrar serviço
      </button>
    </div>

    <?php echo $this->Form->input('Servico.0.id', array('type' => 'hidden')); ?>
    <?php echo $this->Form->input('Servico.0.nome', array('type' => 'hidden', 'id' => 'hidden-nome')); ?>
    <div class="mb-4">
      <label class="block text-sm font-medium text-text-dark mb-1">Descrição</label>
      <?php echo $this->Form->textarea('Servico.0.descricao', array(
        'label' => false,
        'class' => 'w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150 bg-gray-50',
        'rows' => 3,
        'id' => 'hidden-descricao',
      )); ?>
    </div>
    <div class="mb-8">
      <label class="block text-sm font-medium text-text-dark mb-1">Valor (R$)</label>
      <?php echo $this->Form->input('Servico.0.valor', array(
        'label' => false,
        'class' => 'w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150',
        'type' => 'number',
        'step' => '0.01',
        'div' => false
      )); ?>
    </div>


    <!-- Botões de Ação -->
    <div class="flex justify-end space-x-3 border-t border-border-light pt-6">
      <?php echo $this->Html->link('Cancelar', array('action' => 'index'), array('class' => 'px-4 py-2 border border-border-light text-text-dark rounded-lg hover:bg-gray-50 transition duration-150 text-sm font-medium')); ?>
      <button type="submit"
        class="px-6 py-2 bg-primary-red text-white rounded-lg shadow-md hover:bg-red-600 transition duration-150 text-sm font-medium">
        Salvar
      </button>
    </div>
    <?php echo $this->Form->end(); ?>
  </div>
</section>

<script>
  document.querySelector('input[type="file"]').addEventListener('change', function (e) {
    if (e.target.files && e.target.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var container = document.getElementById('photo-preview-container');
        container.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" />';
      }

      reader.readAsDataURL(e.target.files[0]);
    }
  });
</script>

<!-- Modal de Cadastro Serviço -->
<div id="service-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
  aria-modal="true">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modal-overlay"></div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <div
      class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
              Cadastre um serviço
            </h3>
            <div class="mt-2">
              <div class="mb-4">
                <label class="block text-sm font-medium text-text-dark mb-1">Nome do Serviço</label>
                <input type="text" id="modal-service-name"
                  class="w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150"
                  placeholder="">
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-text-dark mb-1">Descrição</label>
                <textarea id="modal-service-desc" rows="3"
                  class="w-full px-3 py-2 border border-border-light rounded-lg focus:ring-primary-red focus:border-primary-red transition duration-150"
                  placeholder="Adicione uma descrição"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="button" id="btn-save-service"
          class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-red text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
          Cadastrar
        </button>
        <button type="button" id="btn-cancel-modal"
          class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
          Cancelar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  var availableServices = <?php echo json_encode(isset($servicosDisponiveis) ? $servicosDisponiveis : []); ?>;

  document.getElementById('service-select').addEventListener('change', function (e) {
    var selectedName = e.target.value;
    var descInput = document.getElementById('hidden-descricao');
    var nameInput = document.getElementById('hidden-nome');

    nameInput.value = selectedName;

    var service = availableServices.find(function (s) { return s.Servico.nome == selectedName; });
    if (service) {
      descInput.value = service.Servico.descricao;
    } else {
      descInput.value = '';
    }
  });

  var modal = document.getElementById('service-modal');
  var btnOpen = document.getElementById('btn-open-modal');
  var btnCancel = document.getElementById('btn-cancel-modal');
  var btnSave = document.getElementById('btn-save-service');
  var overlay = document.getElementById('modal-overlay');

  function openModal() { modal.classList.remove('hidden'); }
  function closeModal() { modal.classList.add('hidden'); }

  btnOpen.addEventListener('click', openModal);
  btnCancel.addEventListener('click', closeModal);
  overlay.addEventListener('click', closeModal);

  btnSave.addEventListener('click', function () {
    var name = document.getElementById('modal-service-name').value;
    var desc = document.getElementById('modal-service-desc').value;

    if (!name) { alert('Nome do serviço é obrigatório'); return; }

    var exists = availableServices.some(function (s) {
      return s.Servico && s.Servico.nome && s.Servico.nome.toLowerCase() === name.toLowerCase();
    });

    if (exists) {
      alert('Este serviço já existe! Por favor selecione-o na lista.');
      return;
    }

    document.getElementById('hidden-nome').value = name;
    document.getElementById('hidden-descricao').value = desc;

    var select = document.getElementById('service-select');
    var option = document.createElement('option');
    option.value = name;
    option.text = name;
    option.selected = true;
    select.add(option);

    closeModal();
    document.getElementById('modal-service-name').value = '';
    document.getElementById('modal-service-desc').value = '';
  });

  var phoneInput = document.getElementById('PrestadorTelefone');
  if (phoneInput) {
    phoneInput.addEventListener('input', function (e) {
      var value = e.target.value.replace(/\D/g, '');
      var formatted = '';

      if (value.length > 0) {
        formatted = '(' + value.substring(0, 2);
      }
      if (value.length > 2) {
        formatted += ') ' + value.substring(2, 7);
      }
      if (value.length > 7) {
        formatted += '-' + value.substring(7, 11);
      }

      e.target.value = formatted;
    });
  }
</script>