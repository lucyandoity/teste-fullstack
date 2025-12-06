<div class="mb-4 bg-red-50 border border-primary-red text-red-900 px-4 py-3 rounded-lg relative shadow-sm" role="alert">
  <div class="flex">
    <div class="py-1">
      <svg class="h-6 w-6 text-primary-red mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <div class="flex items-center">
      <p class="font-medium text-sm"><?php echo htmlspecialchars($message); ?></p>
    </div>
  </div>
</div>