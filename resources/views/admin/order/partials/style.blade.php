<style>
    :root {
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-bg: #f8f9fa;
        --dark-text: #2c3e50;
        --border-color: rgba(0,0,0,0.08);
        --shadow: 0 8px 30px rgba(0,0,0,0.08);
        --shadow-hover: 0 15px 40px rgba(0,0,0,0.12);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        color: var(--dark-text);
        direction: rtl;
        text-align: right;
        line-height: 1.6;
        min-height: 100vh;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Order Header */
    .order-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .order-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.05); opacity: 0.2; }
    }

    .order-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .order-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .order-meta-item {
        background: rgba(255,255,255,0.15);
        padding: 1rem;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }

    .order-meta-item:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    .order-meta-item .label {
        font-size: 0.9rem;
        opacity: 0.9;
        display: block;
    }

    .order-meta-item .value {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    /* Modern Cards */
    .modern-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .card-header {
        background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-header h5 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark-text);
    }

    .card-header .icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .card-content {
        padding: 2rem;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--light-bg);
        border-radius: 12px;
        border-right: 4px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: #e9ecef;
        transform: translateX(-5px);
    }

    .info-item .icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .info-item .content {
        flex: 1;
    }

    .info-item .label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .info-item .value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
    }

    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: inline-block;
    }

    .badge-success { background: var(--success-color); color: white; }
    .badge-warning { background: var(--warning-color); color: #856404; }
    .badge-danger { background: var(--danger-color); color: white; }
    .badge-info { background: var(--info-color); color: white; }
    .badge-primary { background: var(--primary-color); color: white; }

    /* Bilingual Items */
    .bilingual-item {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-right: 4px solid var(--success-color);
    }

    .bilingual-item .label {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 1rem;
    }

    .language-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .language-row:last-child {
        margin-bottom: 0;
    }

    .language-tag {
        background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        min-width: 40px;
        text-align: center;
    }

    .language-value {
        font-size: 1rem;
        color: #495057;
        font-weight: 500;
    }

    /* Collapsible Sections */
    .collapsible-section {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .collapsible-header {
        background: linear-gradient(135deg, #495057 0%, #343a40 100%);
        color: white;
        padding: 1.5rem 2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .collapsible-header:hover {
        background: linear-gradient(135deg, #343a40 0%, #495057 100%);
    }

    .collapsible-header .title {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .collapsible-header .icon {
        transition: transform 0.3s ease;
    }

    .collapsible-header.active .icon {
        transform: rotate(180deg);
    }

    .collapsible-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .collapsible-content.show {
        max-height: 1000px;
        transition: max-height 0.5s ease-in;
    }

    .collapsible-content .content-inner {
        padding: 2rem;
    }

    /* Action Section */
    .action-section {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .action-section h5 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .action-section h5::before {
        content: '';
        width: 4px;
        height: 30px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 2px;
    }

    .modern-form-group {
        margin-bottom: 1.5rem;
    }

    .modern-form-group label {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
        display: block;
    }

    .modern-form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        width: 100%;
    }

    .modern-form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    .modern-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .modern-btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
    }

    .modern-btn-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #6f42c1 100%);
    }

    .modern-btn-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #ff6b6b 100%);
    }

    /* Alerts */
    .alert-modern {
        border: none;
        border-radius: 15px;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        border-right: 4px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-modern-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-right-color: var(--success-color);
        color: #155724;
    }

    .alert-modern-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-right-color: var(--warning-color);
        color: #856404;
    }

    .alert-modern-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f1c2c7 100%);
        border-right-color: var(--danger-color);
        color: #721c24;
    }

    .alert-modern-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #b8daff 100%);
        border-right-color: var(--info-color);
        color: #0c5460;
    }

    /* Map Link */
    .map-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        text-decoration: none;
        background: linear-gradient(135deg, var(--info-color) 0%, #6f42c1 100%);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .map-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(23, 162, 184, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Price Display */
    .price-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--success-color);
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container { padding: 1rem; }
        .order-header { padding: 1.5rem; }
        .order-header h1 { font-size: 2rem; }
        .order-meta { grid-template-columns: 1fr; }
        .info-grid { grid-template-columns: 1fr; }
        .card-header { padding: 1rem; }
        .card-content { padding: 1.5rem; }
        .action-section { padding: 1.5rem; }
    }
    
    /* --- NEW/REVISED STYLES for Activity Log --- */
    .d-none { display: none !important; }

    .activity-log-item {
        padding: 0.6rem 0.8rem !important;
        margin-bottom: 0.75rem !important;
        gap: 0.8rem !important;
        border-right-width: 3px !important;
        align-items: flex-start !important;
        transition: opacity 0.3s ease-in-out;
    }

    .activity-log-item .icon {
        width: 30px !important; height: 30px !important; font-size: 0.8rem !important; margin-top: 5px !important;
    }

    .activity-log-item .content .label {
        font-size: 0.85rem !important; margin-bottom: 0.1rem !important;
    }

    .activity-log-item .content .value {
        font-size: 0.9rem !important; font-weight: 500 !important;
    }

    .activity-log-item .content .value div {
        font-size: 0.8rem !important; line-height: 1.5 !important;
    }

    .activity-log-item .status-badge {
        padding: 0.2rem 0.6rem !important; font-size: 0.75rem !important;
    }
    
    .activity-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
    }

    .pagination-info {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .pagination-btn {
        background: var(--light-bg);
        border: 1px solid var(--border-color);
        color: var(--primary-color);
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-btn:hover {
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }

    .pagination-btn:disabled {
        color: #adb5bd;
        cursor: not-allowed;
        background: #f8f9fa;
        border-color: #f1f3f5;
    }
</style>