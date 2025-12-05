<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>Sistema de Gestão - Seu João</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <?php echo $this->Html->css('style'); ?>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 text-dark" href="<?php echo $this->Html->url('/'); ?>">
                Seu João LTDA
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <?php
                        $activeClass = ($this->params['controller'] == 'providers') ? 'text-danger fw-bold' : 'text-secondary';
                        echo $this->Html->link(
                            'Prestadores',
                            array('controller' => 'providers', 'action' => 'index'),
                            array('class' => 'nav-link ' . $activeClass)
                        );
                        ?>
                    </li>
                    <li class="nav-item">
                        <?php
                        $activeClass = ($this->params['controller'] == 'services') ? 'text-danger fw-bold' : 'text-secondary';
                        echo $this->Html->link(
                            'Catálogo de Serviços',
                            array('controller' => 'services', 'action' => 'index'),
                            array('class' => 'nav-link ' . $activeClass)
                        );
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <?php echo $this->Flash->render(); ?>
            </div>
        </div>

        <?php echo $this->fetch('content'); ?>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-body p-4 text-center">
                    <div class="mx-auto mb-3" style="width: 48px; height: 48px; background-color: #FEE4E2; border: 8px solid #FEF3F2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #D92D20;">
                        <i class="bi bi-exclamation-lg fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Tem certeza?</h5>
                    <p class="text-muted small mb-4" id="confirmationMessage">Essa ação não poderá ser desfeita.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light border w-50" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger w-50" id="btnConfirmAction">Sim, Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        var flashSelector = '.flash-message, .alert, .message, #flashMessage';
        if ($(flashSelector).length) {
            setTimeout(function() {
                $(flashSelector).fadeOut(500, function(){ $(this).remove(); });
            }, 5000);
        }

        // Close navbar on link click (mobile)
        $('.navbar-nav .nav-link').on('click', function() {
            var navbarCollapse = $('#navbarMain');
            if (navbarCollapse.hasClass('show')) {
                navbarCollapse.collapse('hide');
            }
        });

        var pendingFormName = null;

        $('a[onclick*="confirm"]').each(function() {
            var link = $(this);
            var originalOnclick = link.attr('onclick');
            var messageMatch = originalOnclick.match(/confirm\s*\(['"]([^'"]+)['"]\)/);
            var message = messageMatch ? messageMatch[1] : 'Tem certeza?';
            var formMatch = originalOnclick.match(/document\.([a-zA-Z0-9_]+)\.submit/);

            if (formMatch) {
                var formName = formMatch[1];
                link.removeAttr('onclick');
                link.data('confirm-message', message);
                link.data('form-name', formName);
                link.addClass('custom-confirm-trigger');
            }
        });

        $(document).on('click', '.custom-confirm-trigger', function(e) {
            e.preventDefault();
            var msg = $(this).data('confirm-message');
            var formName = $(this).data('form-name');
            $('#confirmationMessage').text(msg);
            pendingFormName = formName;
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();
        });

        $('#btnConfirmAction').click(function() {
            if (pendingFormName) {
                var form = document[pendingFormName];
                if (form) form.submit();
            }
            $('#confirmationModal').modal('hide');
        });
    });
    </script>

    <?php echo $this->fetch('script'); ?>
</body>
</html>

