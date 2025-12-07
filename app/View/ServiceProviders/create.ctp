<?php
$this->assign('title', 'Novo Prestador de Serviço');
echo $this->Html->css('create');
?>

<body>
    <div class="container">
        <header>
            <div>
                <h1 class="title">Cadastro de Prestador de Serviço</h1>
                <p class="subtitle">Informações Pessoais</p>
                <p class="subtitle small">Cadastre suas informações e adicione uma foto.</p>
            </div>
        </header>

        <main>
            <?php echo $this->Flash->render(); ?>

            <div class="form-container">
                <?php echo $this->Form->create('ServiceProvider', array('type' => 'file', 'class' => 'form')); ?>

                <div class="form-row">
                    <label>Nome</label>
                    <div class="inputs-group">
                        <input type="text" name="data[ServiceProvider][first_name]" placeholder="Nome" id="ServiceProviderFirstName">
                        <input type="text" name="data[ServiceProvider][last_name]" placeholder="Sobrenome" id="ServiceProviderLastName">
                    </div>
                </div>

                <?php echo $this->Form->input('email', array('label' => 'E-mail', 'placeholder' => 'seuemail@exemplo.com')); ?>
                
                <?php echo $this->Form->input('photo', array('type' => 'file', 'label' => 'Sua foto <br> <span class="photo-text">Ela aparecerá no seu perfil</span>')); ?>
                
                <?php echo $this->Form->input('phone', array(
                    'label' => 'Telefone',
                    'id' => 'PhoneInput',
                    'placeholder' => '(__) _____-____',
                    'maxlength' => 15
                )); ?>

                <div class="input text">
                    <label for="ServiceProviderService">Quais serviço você vai prestar?</label>
                    <div class="autocomplete-wrapper">
                        <input type="text" name="data[ServiceProvider][service]" id="ServiceProviderService" placeholder="Digite ou selecione um serviço..." autocomplete="off">
                        <div id="ServiceDropdown" class="autocomplete-dropdown"></div>
                    </div>
                </div>

                <?php echo $this->Form->input('description', array('label' => 'Descrição', 'type' => 'textarea', 'placeholder' => 'Conte-nos mais sobre o serviço oferecido...')); ?>
                <?php echo $this->Form->input('price', array('label' => 'Preço', 'placeholder' => 'R$ 200,00')); ?>

                <div class="form-actions">
                    <?php echo $this->Html->link('Cancelar', array('controller' => 'ServiceProviders', 'action' => 'index'), array('class' => 'btn-cancel')); ?>
                    <?php echo $this->Form->end('Salvar'); ?>
                </div>
            </div>
        </main>
    </div>

    <script>
    (function() {
        var suggestions = <?php echo json_encode(array_values($serviceSuggestions)); ?>;
        var input = document.getElementById('ServiceProviderService');
        var dropdown = document.getElementById('ServiceDropdown');

        function renderDropdown(filter) {
            dropdown.innerHTML = '';
            var hasResults = false;
            suggestions.forEach(function(name) {
                if (name.toLowerCase().indexOf(filter.toLowerCase()) !== -1) {
                    hasResults = true;
                    var item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = name;
                    item.addEventListener('click', function() {
                        input.value = this.textContent;
                        dropdown.classList.remove('show');
                    });
                    dropdown.appendChild(item);
                }
            });
            if (hasResults && filter.length > 0) {
                dropdown.classList.add('show');
            } else {
                dropdown.classList.remove('show');
            }
        }

        input.addEventListener('focus', function() { if (this.value.length > 0) renderDropdown(this.value); });
        input.addEventListener('input', function() { renderDropdown(this.value); });
        document.addEventListener('click', function(e) { if (!e.target.closest('.autocomplete-wrapper')) dropdown.classList.remove('show'); });

        document.getElementById('PhoneInput').addEventListener('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.substring(0, 11);
            if (value.length > 0) value = '(' + value;
            if (value.length > 3) value = value.substring(0, 3) + ') ' + value.substring(3);
            if (value.length > 10) value = value.substring(0, 10) + '-' + value.substring(10);
            e.target.value = value;
        });
    })();
    </script>
</body>