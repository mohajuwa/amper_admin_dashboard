// ============================================
// ADMIN SIDEBAR - CLEAN & FIXED VERSION
// ============================================

(function () {
  'use strict';

  // ============================================
  // DEVICE TYPE DETECTION
  // ============================================
  function getDeviceType() {
    const width = window.innerWidth;
    if (width < 768) return 'mobile';
    if (width < 992) return 'tablet';
    return 'desktop';
  }

  // ============================================
  // MOBILE SIDEBAR FUNCTIONS
  // ============================================
  function openMobileSidebar() {
    const $sidebar = $('.main-sidebar');
    const $overlay = $('.sidebar-overlay');
    const $body = $('body');

    console.log('Opening mobile sidebar');

    $sidebar.addClass('sidebar-open');
    $overlay.addClass('show');
    $body.addClass('sidebar-open').css('overflow', 'hidden');
  }

  function closeMobileSidebar() {
    const $sidebar = $('.main-sidebar');
    const $overlay = $('.sidebar-overlay');
    const $body = $('body');

    console.log('Closing mobile sidebar');

    $sidebar.removeClass('sidebar-open');
    $overlay.removeClass('show');
    $body.removeClass('sidebar-open').css('overflow', '');
  }

  // ============================================
  // MENU TOGGLE FUNCTION
  // ============================================
  function toggleMenu(menuElement) {
    if (!menuElement) return;

    const $menu = $(menuElement);
    const $arrow = $menu.children('.nav-link').find('i.fas');
    const $treeview = $menu.children('.nav-treeview');

    if (!$arrow.length || !$treeview.length) return;

    // Close other open menus
    $('[id$="-menu"]').not($menu).each(function () {
      const $otherMenu = $(this);
      const $otherArrow = $otherMenu.children('.nav-link').find('i.fas');
      const $otherTreeview = $otherMenu.children('.nav-treeview');

      $otherMenu.removeClass('menu-open');
      $otherArrow.removeClass('fa-angle-down').addClass('fa-angle-left');
      $otherTreeview.slideUp(200);
    });

    // Toggle current menu
    const isOpen = $menu.hasClass('menu-open');

    if (isOpen) {
      // Close menu
      $menu.removeClass('menu-open');
      $arrow.removeClass('fa-angle-down').addClass('fa-angle-left');
      $treeview.slideUp(200);
    } else {
      // Open menu
      $menu.addClass('menu-open');
      $arrow.removeClass('fa-angle-left').addClass('fa-angle-down');
      $treeview.slideDown(200);
    }
  }

  // ============================================
  // INITIALIZE MENU HANDLERS
  // ============================================
  function initializeMenuHandlers() {
    // Remove existing handlers
    $('[id$="-menu"] > .nav-link').off('click.menuToggle');

    // Add new handlers
    $('[id$="-menu"] > .nav-link').on('click.menuToggle', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const menuElement = $(this).parent('[id$="-menu"]')[0];
      toggleMenu(menuElement);
    });

    console.log('Menu handlers initialized');
  }

  // ============================================
  // FIX MENU ARROWS AND STATES
  // ============================================
  function fixMenuArrows() {
    const menuIds = ['products-menu', 'blog-menu', 'settings-menu'];

    menuIds.forEach(function (menuId) {
      const $menu = $('#' + menuId);
      if (!$menu.length) return;

      const $arrow = $menu.children('.nav-link').find('i.fas');
      const $treeview = $menu.children('.nav-treeview');

      if (!$arrow.length || !$treeview.length) return;

      // Clean up arrow classes
      $arrow.removeClass('fa-angle-left fa-angle-down');

      // Ensure proper positioning
      if (!$arrow.hasClass('left')) {
        $arrow.addClass('left');
      }

      // Set correct state based on menu-open class
      if ($menu.hasClass('menu-open')) {
        $arrow.addClass('fa-angle-down');
        $treeview.show();
      } else {
        $arrow.addClass('fa-angle-left');
        $treeview.hide();
      }
    });

    console.log('Menu arrows fixed');
  }

  // ============================================
  // MOBILE SIDEBAR TOGGLE
  // ============================================
  function initializeSidebarToggle() {
    // Remove existing handlers
    $(document).off('click.pushmenu');
    $(document).off('click.overlay');
    $(document).off('keydown.pushmenu');

    // Sidebar toggle button
    $(document).on('click.pushmenu', '[data-widget="pushmenu"]', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const deviceType = getDeviceType();
      const $body = $('body');

      console.log('Push menu clicked, device type:', deviceType);

      if (deviceType === 'desktop') {
        // Desktop: toggle collapse
        $body.toggleClass('sidebar-collapse');
      } else {
        // Mobile/Tablet: toggle sidebar
        const $sidebar = $('.main-sidebar');
        if ($sidebar.hasClass('sidebar-open')) {
          closeMobileSidebar();
        } else {
          openMobileSidebar();
        }
      }
    });

    // Overlay click to close
    $(document).on('click.overlay', '.sidebar-overlay', function (e) {
      e.stopPropagation();
      const deviceType = getDeviceType();
      if (deviceType !== 'desktop') {
        closeMobileSidebar();
      }
    });

    // Escape key to close
    $(document).on('keydown.pushmenu', function (e) {
      if (e.key === 'Escape') {
        const deviceType = getDeviceType();
        const $sidebar = $('.main-sidebar');
        if (deviceType !== 'desktop' && $sidebar.hasClass('sidebar-open')) {
          closeMobileSidebar();
        }
      }
    });

    // Prevent sidebar from closing when clicking inside
    $('.main-sidebar').on('click', function (e) {
      e.stopPropagation();
    });

    console.log('Sidebar toggle initialized');
  }

  // ============================================
  // HANDLE WINDOW RESIZE
  // ============================================
  function handleWindowResize() {
    let previousDeviceType = getDeviceType();

    $(window).on('resize.pushmenu', function () {
      const currentDeviceType = getDeviceType();

      if (previousDeviceType !== currentDeviceType) {
        const $body = $('body');
        const $sidebar = $('.main-sidebar');
        const $overlay = $('.sidebar-overlay');

        // Clean up states when switching device types
        if (currentDeviceType === 'desktop') {
          // Switching to desktop
          $sidebar.removeClass('sidebar-open');
          $overlay.removeClass('show');
          $body.removeClass('sidebar-open').css('overflow', '');
        } else {
          // Switching to mobile/tablet
          $body.removeClass('sidebar-collapse');
          closeMobileSidebar();
        }

        previousDeviceType = currentDeviceType;
        console.log('Device type changed to:', currentDeviceType);
      }
    });
  }

  // ============================================
  // INITIALIZATION
  // ============================================
  function initializeSidebar() {
    console.log('Initializing sidebar...');

    // Initialize menu handlers
    initializeMenuHandlers();

    // Fix menu arrows and states
    fixMenuArrows();

    // Initialize sidebar toggle
    initializeSidebarToggle();

    // Handle window resize
    handleWindowResize();

    console.log('Sidebar initialization complete');
  }

  // ============================================
  // DOCUMENT READY
  // ============================================
  $(document).ready(function () {
    console.log('DOM Ready - Initializing sidebar');

    // Initialize sidebar
    initializeSidebar();

    // Initialize other admin features
    initializeAdminFeatures();
  });

  // ============================================
  // OTHER ADMIN FEATURES
  // ============================================
  function initializeAdminFeatures() {
    // Initialize Summernote with Arabic support
    if (typeof $.fn.summernote !== 'undefined') {
      $('.editor').summernote({
        height: 300,
        direction: 'rtl',
        lang: 'ar-AR',
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function () {
      $('.alert').fadeOut('slow');
    }, 5000);

    // CSRF token for AJAX requests
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Form loading states
    $('form').on('submit', function () {
      const $submitBtn = $(this).find('button[type="submit"]');
      const originalText = $submitBtn.text();

      $submitBtn.prop('disabled', true)
        .html('<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...');

      setTimeout(function () {
        $submitBtn.prop('disabled', false).text(originalText);
      }, 30000);
    });

    // Enhanced dropdown behavior
    $('.dropdown-toggle').on('click', function (e) {
      e.stopPropagation();
      const $dropdown = $(this).next('.dropdown-menu');
      $('.dropdown-menu').not($dropdown).removeClass('show');
      $dropdown.toggleClass('show');
    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('.dropdown').length) {
        $('.dropdown-menu').removeClass('show');
      }
    });

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function (e) {
      const href = $(this).attr('href');
      if (href === "#" || href === "") return;

      const target = $(href);
      if (target.length) {
        e.preventDefault();
        $('html, body').stop().animate({
          scrollTop: target.offset().top - 100
        }, 500);
      }
    });

    // Add fade-in animation to content
    $('.content-wrapper').addClass('fade-in');

    // Process flash messages
    processFlashMessages();

    console.log('Admin features initialized');
  }
  (function () {
    'use strict';

    // Ensure toast container exists
    function ensureToastContainer() {
      let toastContainer = document.getElementById('toastContainer');
      if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
      }
      return toastContainer;
    }

    // Ensure custom confirm modal exists
    function ensureCustomModal() {
      let customModal = document.getElementById('customConfirmModal');
      if (!customModal) {
        customModal = document.createElement('div');
        customModal.id = 'customConfirmModal';
        customModal.className = 'custom-modal-overlay';
        customModal.innerHTML = `
                <div class="custom-modal">
                    <div class="custom-modal-header">
                        <div class="custom-modal-icon">
                            <i class="fas fa-question"></i>
                        </div>
                        <div class="custom-modal-title" id="customConfirmTitle">تأكيد العملية</div>
                    </div>
                    <div class="custom-modal-body">
                        <p id="customConfirmMessage">هل أنت متأكد من القيام بهذا الإجراء...؟</p>
                    </div>
                    <div class="custom-modal-actions">
                        <button id="customConfirmCancel" class="btn btn-secondary">إلغاء</button>
                        <button id="customConfirmOk" class="btn btn-primary">موافق</button>
                    </div>
                </div>
            `;
        document.body.appendChild(customModal);
      }
      return customModal;
    }

    let confirmCallback = null;

    // Remove Toast
    function removeToast(toast) {
      if (!toast || !toast.parentNode) return;

      toast.classList.add('hide');
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 400);
    }

    // Show Toast Notification
    window.showToast = function (type = 'info', title = '', message = '', duration = 5000) {
      const toastContainer = ensureToastContainer();

      const toast = document.createElement('div');
      toast.className = `toast ${type}`;

      const icons = {
        success: 'fas fa-check',
        error: 'fas fa-times',
        warning: 'fas fa-exclamation',
        info: 'fas fa-info'
      };

      toast.innerHTML = `
            <div class="toast-header">
                <div class="toast-icon">
                    <i class="${icons[type]}"></i>
                </div>
                <div class="toast-title">${title}</div>
                <button class="toast-close" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-body">${message}</div>
            <div class="toast-progress">
                <div class="toast-progress-bar"></div>
            </div>
        `;

      // Add close event
      toast.querySelector('.toast-close').onclick = function () {
        removeToast(toast);
      };

      toastContainer.appendChild(toast);

      // Animate in
      setTimeout(() => {
        toast.classList.add('show');
      }, 100);

      // Progress bar animation
      const progressBar = toast.querySelector('.toast-progress-bar');
      setTimeout(() => {
        progressBar.style.transitionDuration = `${duration}ms`;
        progressBar.style.transform = 'translateX(0)';
      }, 200);

      // Auto remove
      setTimeout(() => {
        removeToast(toast);
      }, duration);

      return toast;
    };

    // Show Custom Confirmation Modal
    window.showCustomConfirm = function (message, callback, title = 'تأكيد العملية') {
      const customModal = ensureCustomModal();

      document.getElementById('customConfirmTitle').textContent = title;
      document.getElementById('customConfirmMessage').textContent = message;
      confirmCallback = callback;
      customModal.classList.add('show');
    };

    // Quick Alert Functions
    window.showSuccess = function (message, title = 'نجح العمل') {
      return showToast('success', title, message);
    };

    window.showError = function (message, title = 'حدث خطأ') {
      return showToast('error', title, message);
    };

    window.showWarning = function (message, title = 'تحذير') {
      return showToast('warning', title, message);
    };

    window.showInfo = function (message, title = 'معلومات') {
      return showToast('info', title, message);
    };

    // Initialize custom modal events
    function initCustomModal() {
      const customModal = ensureCustomModal();

      document.getElementById('customConfirmOk').onclick = function () {
        customModal.classList.remove('show');
        if (confirmCallback) {
          confirmCallback();
          confirmCallback = null;
        }
      };

      document.getElementById('customConfirmCancel').onclick = function () {
        customModal.classList.remove('show');
        confirmCallback = null;
      };

      customModal.onclick = function (e) {
        if (e.target === customModal) {
          customModal.classList.remove('show');
          confirmCallback = null;
        }
      };
    }

    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initCustomModal);
    } else {
      initCustomModal();
    }

  })();
  // ============================================
  // FLASH MESSAGE PROCESSING
  // ============================================
  function processFlashMessages() {
    const flashSuccess = $('meta[name="flash-success"]').attr('content');
    const flashError = $('meta[name="flash-error"]').attr('content');
    const flashWarning = $('meta[name="flash-warning"]').attr('content');
    const flashInfo = $('meta[name="flash-info"]').attr('content');

    if (flashSuccess && typeof showSuccess === 'function') {
      showSuccess(flashSuccess);
    }
    if (flashError && typeof showError === 'function') {
      showError(flashError);
    }
    if (flashWarning && typeof showWarning === 'function') {
      showWarning(flashWarning);
    }
    if (flashInfo && typeof showInfo === 'function') {
      showInfo(flashInfo);
    }
  }

  // ============================================
  // GLOBAL UTILITY FUNCTIONS
  // ============================================

  // Debug helper
  window.debugSidebar = function () {
    const deviceType = getDeviceType();
    const $sidebar = $('.main-sidebar');
    const $overlay = $('.sidebar-overlay');
    const $body = $('body');

    console.log('=== SIDEBAR DEBUG ===');
    console.log('Device type:', deviceType);
    console.log('Sidebar has sidebar-open:', $sidebar.hasClass('sidebar-open'));
    console.log('Overlay has show:', $overlay.hasClass('show'));
    console.log('Body has sidebar-open:', $body.hasClass('sidebar-open'));
    console.log('Body has sidebar-collapse:', $body.hasClass('sidebar-collapse'));

    // Debug menus
    $('[id$="-menu"]').each(function () {
      const menuId = $(this).attr('id');
      const hasMenuOpen = $(this).hasClass('menu-open');
      const $treeview = $(this).children('.nav-treeview');
      const treeviewVisible = $treeview.is(':visible');

      console.log(`Menu ${menuId}: menu-open=${hasMenuOpen}, treeview visible=${treeviewVisible}`);
    });
  };

  // Force re-initialize (useful for debugging)
  window.reinitializeSidebar = function () {
    console.log('Reinitializing sidebar...');
    initializeSidebar();
  };

})();