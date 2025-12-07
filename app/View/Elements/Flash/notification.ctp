<!-- Flash Messages component :^) -->
<div id="<?php echo $key; ?>Message" class="flash-notification <?php echo !empty($params['class']) ? $params['class'] : 'flash-success'; ?>">
    <div class="flash-icon">
        <?php if (!empty($params['class']) && strpos($params['class'], 'error') !== false): ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10 6.66667V10M10 13.3333H10.0083M18.3333 10C18.3333 14.6024 14.6024 18.3333 10 18.3333C5.39763 18.3333 1.66667 14.6024 1.66667 10C1.66667 5.39763 5.39763 1.66667 10 1.66667C14.6024 1.66667 18.3333 5.39763 18.3333 10Z" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        <?php else: ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M16.6667 5L7.5 14.1667L3.33333 10" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        <?php endif; ?>
    </div>
    <div class="flash-content">
        <p class="flash-message"><?php echo $message; ?></p>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
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
        }, 5000);
    }
})();
</script>