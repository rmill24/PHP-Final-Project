        document.addEventListener('DOMContentLoaded', function() {
            // Store cart items data
            const cartItemsData = [
                { id: 1, name: "Silk Slip Dress", price: 189.00, size: "S", color: "Champagne", quantity: 1, selected: true },
                { id: 2, name: "Cashmere Cardigan", price: 245.00, size: "M", color: "Ivory", quantity: 1, selected: true },
                { id: 3, name: "Linen Wide-Leg Pants", price: 158.00, size: "6", color: "Oatmeal", quantity: 1, selected: false }
            ];

            // Quantity selector functionality
            const quantityInputs = document.querySelectorAll('.quantity-input');
            
            quantityInputs.forEach((input, index) => {
                const minusBtn = input.previousElementSibling;
                const plusBtn = input.nextElementSibling;
                
                minusBtn.addEventListener('click', () => {
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        cartItemsData[index].quantity = parseInt(input.value);
                        updateCartTotals();
                    }
                });
                
                plusBtn.addEventListener('click', () => {
                    input.value = parseInt(input.value) + 1;
                    cartItemsData[index].quantity = parseInt(input.value);
                    updateCartTotals();
                });
                
                input.addEventListener('change', () => {
                    if (parseInt(input.value) < 1) {
                        input.value = 1;
                    }
                    cartItemsData[index].quantity = parseInt(input.value);
                    updateCartTotals();
                });
            });
            
            // Remove item functionality
            const removeButtons = document.querySelectorAll('.remove-item');
            
            removeButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    const item = this.closest('.cart-item');
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        cartItemsData.splice(index, 1);
                        updateCartTotals();
                        checkEmptyCart();
                    }, 300);
                });
            });
            
            // Check if cart is empty
            function checkEmptyCart() {
                if (cartItemsData.length === 0) {
                    document.querySelector('.cart-items').style.display = 'none';
                    document.querySelector('.empty-cart').style.display = 'block';
                    document.querySelector('.order-summary').style.display = 'none';
                }
            }
            
            // Item selection functionality
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach((checkbox, index) => {
                checkbox.addEventListener('change', function() {
                    cartItemsData[index].selected = this.checked;
                    updateCartTotals();
                });
            });
            
            // Promo code functionality
            const promoInput = document.getElementById('promoCodeInput');
            const applyPromoBtn = document.getElementById('applyPromoBtn');
            const promoMessage = document.getElementById('promoMessage');
            const promoDiscountRow = document.querySelector('.promo-discount');
            const promoCodeName = document.querySelector('.promo-code-name');
            const discountAmount = document.querySelector('.discount-amount');
            const removePromoBtn = document.querySelector('.promo-discount-remove');
            
            let activePromo = null;
            const validPromoCodes = {
                'VENUSIA20': { discount: 0.2, type: 'percent' },
                'FREESHIP': { discount: 10, type: 'fixed-shipping' },
                'SUMMER15': { discount: 0.15, type: 'percent' }
            };
            
            applyPromoBtn.addEventListener('click', applyPromoCode);
            promoInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyPromoCode();
                }
            });
            
            removePromoBtn.addEventListener('click', removePromoCode);
            
            function applyPromoCode() {
                const promoCode = promoInput.value.trim().toUpperCase();
                promoMessage.className = 'promo-message';
                
                if (!promoCode) {
                    promoMessage.textContent = 'Please enter a promo code';
                    promoMessage.classList.add('promo-error');
                    return;
                }
                
                if (activePromo) {
                    promoMessage.textContent = 'A promo code is already applied';
                    promoMessage.classList.add('promo-error');
                    return;
                }
                
                if (validPromoCodes[promoCode]) {
                    activePromo = validPromoCodes[promoCode];
                    activePromo.code = promoCode;
                    
                    // Show success message
                    promoMessage.textContent = 'Promo code applied successfully!';
                    promoMessage.classList.add('promo-success');
                    
                    // Show discount row
                    promoDiscountRow.style.display = 'flex';
                    promoCodeName.textContent = `(${promoCode})`;
                    
                    // Calculate and display discount
                    updateCartTotals();
                } else {
                    promoMessage.textContent = 'Invalid promo code';
                    promoMessage.classList.add('promo-error');
                }
            }
            
            function removePromoCode() {
                activePromo = null;
                promoDiscountRow.style.display = 'none';
                promoInput.value = '';
                promoMessage.className = 'promo-message';
                updateCartTotals();
            }
            
            // Update cart totals
            function updateCartTotals() {
                let selectedCount = 0;
                let subtotal = 0;
                
                // Update cart items data from UI
                document.querySelectorAll('.cart-item').forEach((item, index) => {
                    const checkbox = item.querySelector('.item-checkbox');
                    const quantityInput = item.querySelector('.quantity-input');
                    
                    // Update data model from UI
                    cartItemsData[index].selected = checkbox.checked;
                    cartItemsData[index].quantity = parseInt(quantityInput.value);
                    
                    if (checkbox.checked) {
                        selectedCount++;
                        const priceText = item.querySelector('.item-price').textContent;
                        const price = parseFloat(priceText.replace('$', ''));
                        subtotal += price * parseInt(quantityInput.value);
                    }
                });
                
                const taxRate = 0.09; // 9% tax
                let tax = subtotal * taxRate;
                let shipping = 0;
                let discount = 0;
                
                // Apply promo code discount if active
                if (activePromo) {
                    if (activePromo.type === 'percent') {
                        discount = subtotal * activePromo.discount;
                    } else if (activePromo.type === 'fixed-shipping') {
                        shipping = -activePromo.discount; // Negative because it's a discount
                    }
                    
                    // Update discount display
                    discountAmount.textContent = `-$${discount.toFixed(2)}`;
                }
                
                const total = subtotal + tax + shipping - discount;
                
                // Update summary display
                document.getElementById('selected-count').textContent = selectedCount;
                document.getElementById('subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('tax').textContent = tax.toFixed(2);
                document.getElementById('total').textContent = total.toFixed(2);
                
                // Update shipping display if promo affects it
                if (activePromo && activePromo.type === 'fixed-shipping') {
                    document.getElementById('shipping').textContent = `-$${activePromo.discount.toFixed(2)}`;
                } else {
                    document.getElementById('shipping').textContent = subtotal > 0 ? 'Free' : '$0.00';
                }
                
                // Disable checkout if no items selected
                const checkoutBtn = document.querySelector('.checkout-btn');
                checkoutBtn.disabled = selectedCount === 0;
            }
            
            // Customization modal functionality
            const modal = document.getElementById('customizationModal');
            const customizeButtons = document.querySelectorAll('.change-size, .change-color');
            const closeModal = document.querySelector('.close-modal');
            const cancelBtn = document.querySelector('.cancel-btn');
            let currentItem = null;
            let currentOption = null;
            let currentItemIndex = null;
            
            customizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentItem = this.closest('.cart-item');
                    currentOption = this.classList.contains('change-size') ? 'size' : 'color';
                    currentItemIndex = Array.from(document.querySelectorAll('.cart-item')).indexOf(currentItem);
                    
                    // Set current selections in modal
                    const currentValue = currentItem.querySelector(`.current-${currentOption}`).textContent;
                    const optionValues = document.querySelectorAll(`#${currentOption}Options .option-value`);
                    
                    optionValues.forEach(option => {
                        option.classList.remove('selected');
                        if (option.dataset.value === currentValue) {
                            option.classList.add('selected');
                        }
                    });
                    
                    // Update modal title
                    document.querySelector('.modal-title').textContent = 
                        `Change ${currentOption.charAt(0).toUpperCase() + currentOption.slice(1)}`;
                    
                    // Show modal
                    modal.style.display = 'flex';
                });
            });
            
            // Option selection in modal
            document.querySelectorAll('.option-value').forEach(option => {
                option.addEventListener('click', function() {
                    this.parentElement.querySelectorAll('.option-value').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');
                });
            });
            
            // Close modal
            function closeModalFunc() {
                modal.style.display = 'none';
            }
            
            closeModal.addEventListener('click', closeModalFunc);
            cancelBtn.addEventListener('click', closeModalFunc);
            
            // Save changes
            document.querySelector('.save-btn').addEventListener('click', function() {
                if (currentItem && currentOption && currentItemIndex !== null) {
                    const selectedOption = document.querySelector(`#${currentOption}Options .option-value.selected`);
                    if (selectedOption) {
                        // Update UI
                        currentItem.querySelector(`.current-${currentOption}`).textContent = selectedOption.dataset.value;
                        
                        // Update data model
                        if (currentOption === 'size') {
                            cartItemsData[currentItemIndex].size = selectedOption.dataset.value;
                        } else {
                            cartItemsData[currentItemIndex].color = selectedOption.dataset.value;
                        }
                    }
                }
                closeModalFunc();
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModalFunc();
                }
            });
            
            // Initialize cart totals
            updateCartTotals();
        });