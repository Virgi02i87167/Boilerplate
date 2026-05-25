import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// Interceptor global para formularios con confirmación
document.addEventListener('submit', function (e) {
    const form = e.target;
    
    // Verificamos si el formulario tiene la clase o atributo de confirmación
    if (form.classList.contains('confirm-form') || form.hasAttribute('data-confirm')) {
        // Si ya fue confirmado, permitimos el envío
        if (form.dataset.confirmed === 'true') {
            return;
        }

        // Si no, detenemos el envío inmediato
        e.preventDefault();

        const message = form.getAttribute('data-confirm') || '¿Estás seguro de realizar esta acción?';
        const subtitle = form.getAttribute('data-confirm-subtitle') || 'Esta acción no se puede deshacer.';
        const type = form.getAttribute('data-confirm-type') || 'warning'; // warning, info, question

        Swal.fire({
            title: message,
            text: subtitle,
            icon: type,
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            // Aplicamos clases exactas de tu sistema de diseño Tailwind CSS
            customClass: {
                popup: 'bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-white border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl shadow-xl font-sans p-6 max-w-md w-11/12',
                title: 'text-xl font-bold text-gray-900 dark:text-white mt-4',
                htmlContainer: 'text-sm text-gray-500 dark:text-[#A1A09A] mt-2 mb-4',
                confirmButton: 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs tracking-wide uppercase transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-[#161615]',
                cancelButton: 'px-4 py-2 bg-white dark:bg-[#1C1C1B] border border-[#e3e3e0] dark:border-[#3E3E3A] text-gray-700 dark:text-[#EDEDEC] hover:bg-gray-50 dark:hover:bg-[#252524] font-bold rounded-lg text-xs tracking-wide uppercase transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-[#161615] mr-3'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Marcamos el formulario como confirmado y lo enviamos
                form.dataset.confirmed = 'true';
                form.submit();
            }
        });
    }
});

