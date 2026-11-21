/**
 * Custom Modal & Alert System
 * Vanilla JavaScript tanpa library eksternal
 */

const CustomModal = {
  /**
   * Success Alert
   * @param {string} message - Pesan yang ditampilkan
   * @param {number} duration - Durasi tampil (ms), 0 = manual close
   */
  success(message, duration = 3000) {
    this._show('success', 'Berhasil', message, duration);
  },

  /**
   * Error Alert
   * @param {string} message - Pesan error
   * @param {number} duration - Durasi tampil (ms)
   */
  error(message, duration = 4000) {
    this._show('error', 'Error', message, duration);
  },

  /**
   * Warning Alert
   * @param {string} message - Pesan warning
   * @param {number} duration - Durasi tampil (ms)
   */
  warning(message, duration = 3500) {
    this._show('warning', 'Peringatan', message, duration);
  },

  /**
   * Info Alert
   * @param {string} message - Pesan info
   * @param {number} duration - Durasi tampil (ms)
   */
  info(message, duration = 3000) {
    this._show('info', 'Informasi', message, duration);
  },

  /**
   * Confirmation Dialog - Promise based
   * @param {string} message - Pesan konfirmasi
   * @param {string} title - Judul dialog (optional)
   * @param {object} buttons - {confirmText, cancelText}
   * @returns {Promise<boolean>}
   */
  confirm(message, title = 'Konfirmasi', buttons = {}) {
    return new Promise((resolve) => {
      const {
        confirmText = 'Ya',
        cancelText = 'Tidak'
      } = buttons;

      const html = `
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-[9999] p-4" id="modal-backdrop">
          <div class="bg-white dark:bg-gray-900 rounded-lg shadow-2xl max-w-md w-full transform transition-all duration-300 scale-100 flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 5.5H2m0 9h16M10 1a9 9 0 000 18 9 9 0 000-18zm0 13a4 4 0 110-8 4 4 0 010 8z" clip-rule="evenodd"/>
                </svg>
                ${title}
              </h2>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 flex-grow">
              <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                ${message}
              </p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end">
              <button 
                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors" 
                id="modal-cancel"
              >
                ${cancelText}
              </button>
              <button 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors" 
                id="modal-confirm"
              >
                ${confirmText}
              </button>
            </div>
          </div>
        </div>
      `;

      const container = document.createElement('div');
      container.innerHTML = html;
      document.body.appendChild(container);

      const backdrop = container.querySelector('#modal-backdrop');
      const confirmBtn = container.querySelector('#modal-confirm');
      const cancelBtn = container.querySelector('#modal-cancel');

      const cleanup = () => {
        container.remove();
      };

      confirmBtn.addEventListener('click', () => {
        resolve(true);
        cleanup();
      });

      cancelBtn.addEventListener('click', () => {
        resolve(false);
        cleanup();
      });

      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
          resolve(false);
          cleanup();
        }
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          resolve(false);
          cleanup();
        }
      });
    });
  },

  /**
   * Generic Modal
   * @param {string} title - Judul modal
   * @param {string} content - HTML content
   * @param {array} actions - [{label, callback, variant}]
   */
  open(title, content, actions = []) {
    const modalId = 'custom-modal-' + Date.now();
    
    let actionsHtml = '';
    if (actions.length === 0) {
      actionsHtml = `<button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" data-close>Tutup</button>`;
    } else {
      actionsHtml = actions.map(action => {
        const variant = action.variant || 'primary';
        const bgColor = variant === 'danger' ? 'bg-red-600 hover:bg-red-700' : 
                       variant === 'secondary' ? 'bg-gray-400 hover:bg-gray-500' :
                       'bg-blue-600 hover:bg-blue-700';
        return `<button class="px-4 py-2 ${bgColor} text-white rounded-lg transition-colors" data-action="${action.label}">${action.label}</button>`;
      }).join('');
    }

    const html = `
      <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-[9999] p-4" id="${modalId}-backdrop">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300">
          <!-- Header -->
          <div class="sticky top-0 px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">${title}</h2>
            <button 
              class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors" 
              id="${modalId}-close"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="px-6 py-4 text-gray-700 dark:text-gray-300">
            ${content}
          </div>

          <!-- Footer -->
          <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end bg-gray-50 dark:bg-gray-800">
            ${actionsHtml}
          </div>
        </div>
      </div>
    `;

    const container = document.createElement('div');
    container.innerHTML = html;
    document.body.appendChild(container);

    const backdrop = container.querySelector(`#${modalId}-backdrop`);
    const closeBtn = container.querySelector(`#${modalId}-close`);
    const closeBtn2 = container.querySelector('[data-close]');

    const cleanup = () => {
      container.remove();
    };

    const closeModal = () => {
      backdrop.style.opacity = '0';
      setTimeout(cleanup, 200);
    };

    closeBtn.addEventListener('click', closeModal);
    if (closeBtn2) closeBtn2.addEventListener('click', closeModal);

    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) closeModal();
    });

    // Action buttons
    actions.forEach(action => {
      const btn = container.querySelector(`[data-action="${action.label}"]`);
      if (btn && action.callback) {
        btn.addEventListener('click', () => {
          action.callback();
          closeModal();
        });
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });

    return { close: closeModal };
  },

  /**
   * Toast/Alert notification
   * @private
   */
  _show(type, title, message, duration) {
    const toastId = 'toast-' + Date.now();
    
    const iconSvg = {
      success: '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
      error: '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
      warning: '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
      info: '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1h2v2H7V4zm2 5H7v2h2V9zm2-5h2v2h-2V4zm2 5h2v2h-2V9z" clip-rule="evenodd"/></svg>'
    };

    const bgColor = {
      success: 'bg-green-600',
      error: 'bg-red-600',
      warning: 'bg-yellow-600',
      info: 'bg-blue-600'
    };

    const html = `
      <div class="fixed top-4 right-4 max-w-md animate-in slide-in-from-right z-[9998] transition-all duration-300" id="${toastId}">
        <div class="flex gap-4 rounded-lg p-4 ${bgColor[type]} text-white shadow-lg">
          <div class="flex-shrink-0">
            ${iconSvg[type]}
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-sm">${title}</h3>
            <p class="text-sm opacity-90 mt-1">${message}</p>
          </div>
          <button class="flex-shrink-0 text-white hover:text-gray-200 transition-colors" id="${toastId}-close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    `;

    const container = document.createElement('div');
    container.innerHTML = html;
    document.body.appendChild(container);

    const toast = container.querySelector(`#${toastId}`);
    const closeBtn = container.querySelector(`#${toastId}-close`);

    const remove = () => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(400px)';
      setTimeout(() => container.remove(), 300);
    };

    closeBtn.addEventListener('click', remove);

    if (duration > 0) {
      setTimeout(remove, duration);
    }
  }
};

// Tambah ke window untuk diakses global
window.CustomModal = CustomModal;
