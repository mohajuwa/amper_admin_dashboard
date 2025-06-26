{{-- Ajax Handler Script --}}
<script>
class AjaxHandler {
    constructor(config = {}) {
        this.config = {
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            defaultHeaders: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            timeout: 30000,
            ...config
        };
    }
    
    async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                ...this.config.defaultHeaders,
                'X-CSRF-TOKEN': this.config.csrfToken
            }
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        // Add headers if not already present
        if (finalOptions.headers) {
            finalOptions.headers = { ...defaultOptions.headers, ...finalOptions.headers };
        }
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), this.config.timeout);
            
            finalOptions.signal = controller.signal;
            
            const response = await fetch(url, finalOptions);
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return { success: true, data, response };
            
        } catch (error) {
            console.error('Ajax request failed:', error);
            return { success: false, error: error.message };
        }
    }
    
    async get(url, params = {}) {
        const urlWithParams = new URL(url, window.location.origin);
        Object.keys(params).forEach(key => 
            urlWithParams.searchParams.append(key, params[key])
        );
        
        return this.request(urlWithParams.toString());
    }
    
    async post(url, data = {}) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
    
    async put(url, data = {}) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }
    
    async delete(url) {
        return this.request(url, {
            method: 'DELETE'
        });
    }
    
    // Form submission helper
    async submitForm(form, options = {}) {
        const formData = new FormData(form);
        const method = form.method || 'POST';
        const url = form.action || window.location.href;
        
        // Convert FormData to JSON if content type is JSON
        let body = formData;
        if (this.config.defaultHeaders['Content-Type'] === 'application/json') {
            const jsonData = {};
            for (let [key, value] of formData.entries()) {
                jsonData[key] = value;
            }
            body = JSON.stringify(jsonData);
        } else {
            // For file uploads, use FormData and remove Content-Type to let browser set it
            const headers = { ...this.config.defaultHeaders };
            delete headers['Content-Type'];
            options.headers = { ...headers, ...options.headers };
        }
        
        return this.request(url, {
            method: method.toUpperCase(),
            body,
            ...options
        });
    }
    
    // Loading state management
    showLoading(element) {
        if (element) {
            element.disabled = true;
            const originalText = element.textContent;
            element.dataset.originalText = originalText;
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
        }
    }
    
    hideLoading(element) {
        if (element && element.dataset.originalText) {
            element.disabled = false;
            element.textContent = element.dataset.originalText;
            delete element.dataset.originalText;
        }
    }
    
    // Notification helper
    showNotification(message, type = 'info') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type === 'success' ? 'نجح!' : type === 'error' ? 'خطأ!' : 'معلومة',
                text: message,
                icon: type,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }
}

// Global instance
window.ajaxHandler = new AjaxHandler();

// jQuery-style shortcuts if jQuery is available
if (typeof $ !== 'undefined') {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}
</script>