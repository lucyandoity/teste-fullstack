<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Seu João - <?php echo $this->fetch('title'); ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Tailwind CSS -->
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						'primary-red': '#f83e52',
						'soft-gray': '#f9fafb',
						'border-light': '#e5e7eb',
						'text-dark': '#111827',
						'text-secondary': '#6b7280',
						'success-green-bg': '#ecfdf5',
						'success-green-icon': '#10b981',
					},
					fontFamily: {
						sans: ['Inter', 'sans-serif'],
					},
					boxShadow: {
						'custom': '0 4px 12px rgba(0, 0, 0, 0.1)',
					}
				}
			}
		}
	</script>
	<style>
		body {
			font-family: 'Inter', sans-serif;
			background-color: #ffffff;
			min-height: 100vh;
		}

		.icon-plus {
			width: 16px;
			height: 16px;
			fill: none;
			stroke: currentColor;
			stroke-width: 2;
			stroke-linecap: round;
			stroke-linejoin: round;
		}

		.dropdown-content {
			box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
			max-height: 200px;
			overflow-y: auto;
		}

		.dropdown-content::-webkit-scrollbar {
			width: 8px;
		}

		.dropdown-content::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 10px;
		}
	</style>
</head>

<body class="text-text-dark">
	<div id="app-container">
		<?php echo $this->Flash->render(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>

	<!-- Global Scripts -->
	<script src="https://unpkg.com/lucide@latest"></script>
	<script>
		lucide.createIcons();
		function openModal(modalId) {
			const modal = document.getElementById(modalId);
			if (modal) modal.classList.remove('hidden');
		}
		function closeModal(modalId) {
			const modal = document.getElementById(modalId);
			if (modal) modal.classList.add('hidden');
		}
		document.querySelectorAll('.fixed.inset-0').forEach(modal => {
			modal.addEventListener('click', (e) => {
				if (e.target === modal) modal.classList.add('hidden');
			});
		});
	</script>
</body>

</html>