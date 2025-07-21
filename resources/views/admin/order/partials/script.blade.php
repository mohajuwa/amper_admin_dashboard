<script>
    document.addEventListener("DOMContentLoaded", function() {

        // --- Collapsible Section Functionality ---
        const collapsibleHeaders = document.querySelectorAll('.collapsible-header');
        collapsibleHeaders.forEach(header => {
            header.addEventListener('click', function() {
                this.classList.toggle('active');
                const content = this.nextElementSibling;
                if (content.classList.contains('show')) {
                    content.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    content.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // --- General Order Update Logic ---
        const updateOrderBtn = document.getElementById('updateOrderBtn');
        const orderUpdateForm = document.getElementById('orderUpdateForm');
        const updateResult = document.getElementById('updateResult');
        const totalAmountInput = document.getElementById('totalAmountInput');
        if (updateOrderBtn && orderUpdateForm) {
            updateOrderBtn.addEventListener('click', function() {
                const formData = new FormData(orderUpdateForm);
                const selectedStatusElement = document.getElementById('orderStatus');
                const selectedStatusText = selectedStatusElement.options[selectedStatusElement
                    .selectedIndex].text;
                const totalAmount = totalAmountInput ? totalAmountInput.value : '';
                const orderId = formData.get('orderId');
                const userId = formData.get('userId');
                const orderStatus = formData.get('orderStatus');

                if (!orderId || !userId || orderStatus === null) {
                    if (typeof showError === 'function') {
                        showError(
                            'يرجى التأكد من صحة البيانات المطلوبة (معرف الطلب، معرف المستخدم، حالة الطلب).',
                            'بيانات ناقصة');
                    } else {
                        alert(
                            'يرجى التأكد من صحة البيانات المطلوبة (معرف الطلب، معرف المستخدم، حالة الطلب).');
                    }
                    return;
                }

                let confirmMessage = `هل أنت متأكد من تغيير حالة الطلب إلى "${selectedStatusText}"؟`;
                if (totalAmountInput && totalAmount.trim() !== '') {
                    confirmMessage += `\nسيتم تحديث المبلغ الإجمالي إلى: ر.س${totalAmount}`;
                }

                if (typeof showCustomConfirm === 'function') {
                    showCustomConfirm(confirmMessage, function() {
                        proceedUpdateOrder();
                    }, 'تأكيد التحديث');
                } else {
                    if (confirm(confirmMessage)) {
                        proceedUpdateOrder();
                    }
                }

                function proceedUpdateOrder() {
                    updateOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
                    updateOrderBtn.disabled = true;
                    updateResult.innerHTML = '';
                    const params = new URLSearchParams();
                    params.append('orderId', orderId);
                    params.append('userId', userId);
                    params.append('orderStatus', orderStatus);
                    if (totalAmountInput) {
                        params.append('totalAmount', totalAmount);
                    }
                    updateResult.innerHTML =
                        `<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> جاري إرسال التحديثات...</div>`;
                    fetch('https://modwir.com/haytham_store/admin/orders/prepare.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: params.toString()
                        })
                        .then(response => response.ok ? response.json() : response.text().then(text => {
                            throw new Error(text)
                        }))
                        .then(responseData => {
                            updateOrderBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التغييرات';
                            updateOrderBtn.disabled = false;
                            if (responseData.status === 'success') {
                                if (typeof showSuccess === 'function') {
                                    showSuccess('تم تحديث الطلب بنجاح', responseData.message_ar ||
                                        responseData.message);
                                }
                                updateResult.innerHTML =
                                    `<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>تم بنجاح!</strong> ${responseData.message_ar || responseData.message}</div>`;
                                setTimeout(() => window.location.reload(), 3000);
                            } else {
                                if (typeof showError === 'function') {
                                    showError(responseData.message_ar || responseData.message ||
                                        'خطأ غير معروف', 'فشل في التحديث');
                                } else {
                                    updateResult.innerHTML =
                                        `<div class="alert alert-danger"><strong>فشل في التحديث:</strong><br>${responseData.message_ar || responseData.message}</div>`;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error (General Update):', error);
                            updateOrderBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التغييرات';
                            updateOrderBtn.disabled = false;
                            if (typeof showError === 'function') {
                                showError(error.message, 'خطأ في الإرسال');
                            } else {
                                updateResult.innerHTML =
                                    `<div class="alert alert-danger"><strong>خطأ في الإرسال:</strong><br>${error.message}</div>`;
                            }
                        });
                }
            });
        }

        // --- Send Offer To Vendor Logic ---
        const sendOfferToVendorBtn = document.getElementById('sendOfferToVendorBtn');
        const offerCustomPriceInput = document.getElementById('offerCustomPrice');
        const offerCustomAppCommissionInput = document.getElementById('offerCustomAppCommission');
        const offerSubServiceId = document.getElementById('offerSubServiceId');
        const offerSubServiceName = document.getElementById('offerSubServiceName');
        const offerAddressStreet = document.getElementById('offerAddressStreet');
        const offerAddressCity = document.getElementById('offerAddressCity');
        const offerVendorSelect = document.getElementById('offerVendorSelect');
        const updateResultOffer = document.getElementById('updateResultOffer');
        const orderTypeInput = document.getElementById('orderType');
        if (sendOfferToVendorBtn) {
            offerCustomPriceInput.addEventListener('input', calculateAmounts);
            offerCustomAppCommissionInput.addEventListener('input', calculateAmounts);
            sendOfferToVendorBtn.addEventListener('click', function() {
                const orderId = document.querySelector('input[name="orderId"]').value;
                const selectedVendorId = offerVendorSelect ? offerVendorSelect.value : '';
                const offerWorkshopAmount = offerCustomPriceInput ? offerCustomPriceInput.value : '';
                const customAppCommission = offerCustomAppCommissionInput ?
                    offerCustomAppCommissionInput.value : '';
                const subServiceIdValue = offerSubServiceId ? offerSubServiceId.value : '';
                const subServiceNameValue = offerSubServiceName ? offerSubServiceName.value : '';
                const addressStreetValue = offerAddressStreet ? offerAddressStreet.value : '';
                const addressCityValue = offerAddressCity ? offerAddressCity.value : '';
                const userAddressString = [addressStreetValue, addressCityValue].filter(Boolean).join(
                    ', ');
                const orderType = orderTypeInput ? orderTypeInput.value : '0';

                if (!orderId || !selectedVendorId || offerWorkshopAmount === '' || parseFloat(
                        offerWorkshopAmount) < 0) {
                    showError('يرجى التأكد من اختيار مورد وإدخال مبلغ صحيح للعرض.', 'بيانات ناقصة');
                    return;
                }

                const offerWorkshopAmountNum = parseFloat(offerWorkshopAmount) || 0;
                let calculatedAppCommission = 0;
                let calculatedTotalAmount = 0;

                if (customAppCommission.trim() !== '') {
                    calculatedAppCommission = parseFloat(customAppCommission);
                    calculatedTotalAmount = offerWorkshopAmountNum + calculatedAppCommission;
                } else {
                    if (offerWorkshopAmountNum === 0) {
                        calculatedTotalAmount = 0;
                        calculatedAppCommission = 0;
                    } else {
                        calculatedTotalAmount = offerWorkshopAmountNum / 0.90;
                        calculatedAppCommission = calculatedTotalAmount * 0.10;
                    }
                }
                calculatedTotalAmount = Math.max(0, calculatedTotalAmount);
                calculatedAppCommission = Math.max(0, calculatedAppCommission);

                let confirmMessage =
                    `هل أنت متأكد من إرسال عرض سعر للمورد رقم ${selectedVendorId}؟\n\n` +
                    `المبلغ الكلي للعميل: ر.س${calculatedTotalAmount.toFixed(2)}\n` +
                    `مبلغ المورد: ر.س${offerWorkshopAmountNum.toFixed(2)}\n` +
                    `عمولة التطبيق: ر.س${calculatedAppCommission.toFixed(2)}\n\n` +
                    `الخدمة: ${subServiceNameValue || 'غير متاح'}`;

                if (orderType == '1') {
                    confirmMessage += `\nالموقع: الاستلام من الورشة`;
                } else {
                    confirmMessage += `\nالعنوان: ${userAddressString || 'غير متاح'}`;
                }

                if (typeof showCustomConfirm === 'function') {
                    showCustomConfirm(confirmMessage, proceedSendOffer, 'تأكيد إرسال العرض');
                } else {
                    if (confirm(confirmMessage)) {
                        proceedSendOffer();
                    }
                }

                function proceedSendOffer() {
                    sendOfferToVendorBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
                    sendOfferToVendorBtn.disabled = true;
                    updateResultOffer.innerHTML = '';
                    const params = new URLSearchParams();
                    params.append('orderId', orderId);
                    params.append('offerPrice', offerWorkshopAmount);
                    params.append('vendorId', selectedVendorId);
                    params.append('customAppCommission', customAppCommission);
                    params.append('subServiceId', subServiceIdValue);
                    params.append('subServiceName', subServiceNameValue);
                    params.append('userAddressStreet', addressStreetValue);
                    params.append('userAddressCity', addressCityValue);
                    updateResultOffer.innerHTML =
                        `<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> جاري إرسال العرض...</div>`;
                    fetch('https://modwir.com/haytham_store/admin/orders/send_offer_to_vendor.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: params.toString()
                        })
                        .then(response => response.ok ? response.json() : response.text().then(text => {
                            throw new Error(text)
                        }))
                        .then(responseData => {
                            sendOfferToVendorBtn.innerHTML =
                                '<i class="fas fa-paper-plane"></i> إرسال العرض للمورد';
                            sendOfferToVendorBtn.disabled = false;
                            if (responseData.status === 'success') {
                                if (typeof showSuccess === 'function') {
                                    showSuccess('تم إرسال العرض بنجاح', responseData.message_ar ||
                                        responseData.message);
                                }
                                updateResultOffer.innerHTML =
                                    `<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>تم بنجاح!</strong> ${responseData.message_ar || responseData.message}</div>`;
                                setTimeout(() => window.location.reload(), 3000);
                            } else {
                                if (typeof showError === 'function') {
                                    showError(responseData.message_ar || responseData.message ||
                                        'خطأ غير معروف', 'فشل في الإرسال');
                                } else {
                                    updateResultOffer.innerHTML =
                                        `<div class="alert alert-danger"><strong>فشل في الإرسال:</strong><br>${responseData.message_ar || responseData.message}</div>`;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error (Send Offer):', error);
                            sendOfferToVendorBtn.innerHTML =
                                '<i class="fas fa-paper-plane"></i> إرسال العرض للمورد';
                            sendOfferToVendorBtn.disabled = false;
                            if (typeof showError === 'function') {
                                showError(error.message, 'خطأ في الاتصال');
                            } else {
                                updateResultOffer.innerHTML =
                                    `<div class="alert alert-danger"><strong>خطأ في الإرسال:</strong><br>${error.message}</div>`;
                            }
                        });
                }
            });
        }

        function calculateAmounts() {
            const offerWorkshopAmount = parseFloat(offerCustomPriceInput.value) || 0;
            const customAppCommissionInputVal = offerCustomAppCommissionInput.value.trim();
            let calculatedAppCommission, calculatedTotalAmount;
            if (customAppCommissionInputVal !== '') {
                calculatedAppCommission = parseFloat(customAppCommissionInputVal);
                calculatedTotalAmount = offerWorkshopAmount + calculatedAppCommission;
            } else {
                if (offerWorkshopAmount === 0) {
                    calculatedTotalAmount = 0;
                    calculatedAppCommission = 0;
                } else {
                    calculatedTotalAmount = offerWorkshopAmount / 0.90;
                    calculatedAppCommission = calculatedTotalAmount * 0.10;
                }
            }
            calculatedTotalAmount = Math.max(0, calculatedTotalAmount);
            calculatedAppCommission = Math.max(0, calculatedAppCommission);
            if (offerCustomAppCommissionInput) {
                if (customAppCommissionInputVal === '') {
                    offerCustomAppCommissionInput.placeholder =
                        `أو تلقائي: ر.س${calculatedAppCommission.toFixed(2)}`;
                } else {
                    offerCustomAppCommissionInput.placeholder = `أدخل عمولة التطبيق المخصصة`;
                }
            }
        }
        calculateAmounts();

        // --- Activity Log Pagination Logic ---
        const activityWrapper = document.getElementById('activityLogWrapper');
        if (activityWrapper) {
            const itemsPerPage = 2;
            const allActivities = activityWrapper.querySelectorAll('.activity-log-item');
            const totalItems = allActivities.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            let currentPage = 1;

            const nextBtn = document.getElementById('activityNextBtn');
            const prevBtn = document.getElementById('activityPrevBtn');
            const pageInfo = document.getElementById('activityPageInfo');
            const paginationContainer = document.querySelector('.activity-pagination');

            if (totalPages <= 1 && paginationContainer) {
                paginationContainer.classList.add('d-none');
                return;
            }

            const renderPage = (page) => {
                if (page < 1 || page > totalPages) return;
                currentPage = page;

                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage - 1, totalItems - 1);

                allActivities.forEach(item => item.classList.add('d-none'));
                for (let i = startIndex; i <= endIndex; i++) {
                    if (allActivities[i]) {
                        allActivities[i].classList.remove('d-none');
                    }
                }

                if (pageInfo) {
                    pageInfo.textContent = `عرض ${startIndex + 1}-${endIndex + 1} من ${totalItems}`;
                }

                if (prevBtn) prevBtn.disabled = (currentPage === 1);
                if (nextBtn) nextBtn.disabled = (currentPage === totalPages);
            };

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    renderPage(currentPage + 1);
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    renderPage(currentPage - 1);
                });
            }

            renderPage(1);
        }
    });
</script>
