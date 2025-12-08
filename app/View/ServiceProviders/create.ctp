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
                    <?php if ($this->Form->isFieldError('ServiceProvider.first_name')): ?>
                        <div class="error-message">
                            <?php echo $this->Form->error('ServiceProvider.first_name'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->Form->isFieldError('ServiceProvider.last_name')): ?>
                        <div class="error-message">
                            <?php echo $this->Form->error('ServiceProvider.last_name'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- E-mail com ícone -->
                <div class="input with-icon email-field">
                    <label for="ServiceProviderEmail">E-mail</label>
                    <div class="field-control">
                        <span class="input-icon email-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="15" viewBox="0 0 19 15" fill="none">
                                <path d="M17.5 2.50004C17.5 1.58337 16.75 0.833374 15.8334 0.833374H2.50004C1.58337 0.833374 0.833374 1.58337 0.833374 2.50004M17.5 2.50004V12.5C17.5 13.4167 16.75 14.1667 15.8334 14.1667H2.50004C1.58337 14.1667 0.833374 13.4167 0.833374 12.5V2.50004M17.5 2.50004L9.16671 8.33337L0.833374 2.50004" stroke="#717680" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input type="email" name="data[ServiceProvider][email]" id="ServiceProviderEmail" placeholder="seuemail@exemplo.com">
                    </div>
                    <?php if ($this->Form->isFieldError('ServiceProvider.email')): ?>
                        <div class="error-message">
                            <?php echo $this->Form->error('ServiceProvider.email'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-row photo-row">
                    <label>Sua foto <br><span class="photo-text">Ela aparecerá no seu perfil</span></label>
                    <div class="photo-input-container">
                        <div class="photo-preview" id="photo-preview">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <path d="M33.3333 35V31.6667C33.3333 29.8986 32.6309 28.2029 31.3807 26.9526C30.1305 25.7024 28.4348 25 26.6667 25H13.3333C11.5652 25 9.86949 25.7024 8.61925 26.9526C7.36901 28.2029 6.66666 29.8986 6.66666 31.6667V35M26.6667 11.6667C26.6667 15.3486 23.6819 18.3333 20 18.3333C16.3181 18.3333 13.3333 15.3486 13.3333 11.6667C13.3333 7.98477 16.3181 5 20 5C23.6819 5 26.6667 7.98477 26.6667 11.6667Z" stroke="#98A2B3" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="file-input-wrapper">
                            <?php 
                            echo $this->Form->file('photo', array(
                                'accept' => 'image/*',
                                'class' => 'file-input',
                                'id' => 'photo-input'
                            ));
                            ?>
                            <div class="import-info">
                                <div class="import-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_photo)">
                                            <path d="M13.3333 13.3334L10 10M10 10L6.66666 13.3334M10 10V17.5M16.9917 15.325C17.8044 14.8819 18.4465 14.1808 18.8166 13.3322C19.1866 12.4837 19.2635 11.5361 19.0352 10.6389C18.8068 9.74182 18.2862 8.94629 17.5556 8.3779C16.8249 7.80951 15.9257 7.50064 15 7.50003H13.95C13.6978 6.5244 13.2276 5.61864 12.575 4.85085C11.9223 4.08307 11.104 3.47324 10.1817 3.0672C9.25946 2.66116 8.25712 2.46949 7.25009 2.5066C6.24307 2.5437 5.25755 2.80861 4.36764 3.28142C3.47774 3.75422 2.70659 4.42261 2.11218 5.23635C1.51777 6.05008 1.11557 6.98797 0.935814 7.97952C0.756055 8.97107 0.803418 9.99047 1.07434 10.9611C1.34527 11.9317 1.8327 12.8282 2.5 13.5834" stroke="#535862" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                                <p class="import-description"><span class="import-description-bold">Clique para enviar</span> ou arraste e solte</p>
                                <p class="import-file-size">PNG, JPG (max. 5MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                    <?php if ($this->Form->isFieldError('ServiceProvider.service')): ?>
                        <div class="error-message">
                            <?php echo $this->Form->error('ServiceProvider.service'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php echo $this->Form->input('description', array('label' => 'Descrição', 'type' => 'textarea', 'placeholder' => 'Conte-nos mais sobre o serviço oferecido...')); ?>

                <!-- Preço com prefixo R$ -->
                <div class="input with-prefix price-field">
                    <label for="ServiceProviderPrice">Preço</label>
                    <div class="field-control">
                        <span class="input-prefix">R$</span>
                        <input type="number" step="0.01" name="data[ServiceProvider][price]" id="ServiceProviderPrice" placeholder="200,00">
                    </div>
                    <?php if ($this->Form->isFieldError('ServiceProvider.price')): ?>
                        <div class="error-message">
                            <?php echo $this->Form->error('ServiceProvider.price'); ?>
                        </div>
                    <?php endif; ?>
                </div>

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

        // Preview da foto
        document.getElementById('photo-input').addEventListener('change', function(e) {
            var file = e.target.files[0];
            var preview = document.getElementById('photo-preview');
            
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    preview.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M33.3333 35V31.6667C33.3333 29.8986 32.6309 28.2029 31.3807 26.9526C30.1305 25.7024 28.4348 25 26.6667 25H13.3333C11.5652 25 9.86949 25.7024 8.61925 26.9526C7.36901 28.2029 6.66666 29.8986 6.66666 31.6667V35M26.6667 11.6667C26.6667 15.3486 23.6819 18.3333 20 18.3333C16.3181 18.3333 13.3333 15.3486 13.3333 11.6667C13.3333 7.98477 16.3181 5 20 5C23.6819 5 26.6667 7.98477 26.6667 11.6667Z" stroke="#98A2B3" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                preview.classList.remove('has-image');
            }
        });
    })();
    </script>
</body>