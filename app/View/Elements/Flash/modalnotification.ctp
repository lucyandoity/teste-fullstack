<!-- Flash Messages component :^) -->
<div id="<?php echo $key; ?>Message" class="modal <?php echo !empty($params['class']) ? $params['class'] : 'flash-success'; ?>">
        <div class="modal-content flash">
            <div class="modal-body flash-modal-body">
                <?php if (!empty($params['class']) && strpos($params['class'], 'error') !== false): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 9L9 15M9 9L15 15" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.709 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53446 21.3746C6.51168 20.6273 4.78465 19.2461 3.61095 17.4371C2.43726 15.628 1.87979 13.4881 2.02167 11.3363C2.16356 9.18455 2.9972 7.13631 4.39827 5.49706C5.79935 3.85781 7.69278 2.71537 9.79618 2.24013C11.8996 1.7649 14.1003 1.98232 16.07 2.85999M22 3.99999L12 14.01L9 11.01" stroke="#039855" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                <?php endif; ?>
                    <div class="flash-body-text">
                    <div class="flash-title">
                        <?php echo $message; ?>
                    </div>
                    <div class="flash-subtitle">
                        <?php if (!empty($params['class']) && strpos($params['class'], 'error') !== false): ?>
                            Tente novamente
                        <?php else: ?>
                            Confira seus servidores na tabela abaixo
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

<script>
(function() {
    var notification = document.getElementById('<?php echo $key; ?>Message');
    if (notification) {
        setTimeout(function() {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(function() {
            notification.classList.remove('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
})();
</script>