$(document).ready(function() {
    $('#uploadBox').click(function(e) {
        if (e.target.id !== 'fileInput') {
            $('#fileInput').click();
        }
    });

    $('#fileInput').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#avatarPreview').html('<img src="' + event.target.result + '" alt="Avatar">');
            };
            reader.readAsDataURL(file);
        }
    });

    const uploadBox = $('#uploadBox');
    uploadBox.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.css('border-color', '#3b82f6');
    });

    uploadBox.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.css('border-color', '#ddd');
    });

    uploadBox.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.css('border-color', '#ddd');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#fileInput').prop('files', files);
            $('#fileInput').change(); 
        }
    });

    // ==================================================================
    // MÁSCARA PARA O CAMPO DE TELEFONE
    // ==================================================================
    
    $('#phoneNumber').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        $(this).val(value.substring(0, 15));
    });

});