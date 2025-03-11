// planperfect-cart.js - Shared cart functionality across all pages

// Initialize cart in localStorage if it doesn't exist
function initializeCart() {
    if (!localStorage.getItem('planPerfectCart')) {
        localStorage.setItem('planPerfectCart', JSON.stringify([]));
    }
}

// Add a service to the cart
function addToCart(serviceId, serviceName, vendorName, price) {
    initializeCart();
    
    // Get current cart
    const cart = JSON.parse(localStorage.getItem('planPerfectCart'));
    
    // Check if service already exists in cart
    const existingItemIndex = cart.findIndex(item => parseInt(item.id) === parseInt(serviceId));
    
    if (existingItemIndex !== -1) {
        // If service already exists, increment quantity
        cart[existingItemIndex].quantity += 1;
    } else {
        // Otherwise add new service to cart
        cart.push({
            id: parseInt(serviceId),
            name: serviceName,
            vendor: vendorName,
            price: price,
            quantity: 1
        });
    }
    
    // Save updated cart
    localStorage.setItem('planPerfectCart', JSON.stringify(cart));
    
    // Show confirmation message
    showConfirmationMessage('تمت إضافة الخدمة إلى عربة التسوق');
    
    // Update cart icon badge
    updateCartBadge();
    
    // Update cart popup if open
    if (document.getElementById('cartPopup') && 
        document.getElementById('cartPopup').classList.contains('show')) {
        loadCartPopup();
    }
}

// Show a confirmation message
function showConfirmationMessage(message) {
    const confirmMessage = document.createElement('div');
    confirmMessage.className = 'add-confirmation';
    confirmMessage.textContent = message;
    document.body.appendChild(confirmMessage);
    
    // Remove the message after 2 seconds
    setTimeout(() => {
        confirmMessage.classList.add('hide');
        setTimeout(() => confirmMessage.remove(), 300);
    }, 2000);
}

// Update cart badge to show number of items
function updateCartBadge() {
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Update cart shortcut badge
    const cartShortcutBadge = document.getElementById('cartShortcutBadge');
    if (cartShortcutBadge) {
        cartShortcutBadge.textContent = totalItems;
        cartShortcutBadge.style.display = totalItems > 0 ? 'flex' : 'none';
    }
    
    // Update header cart badge if exists
    const headerCartBadge = document.getElementById('headerCartBadge');
    if (headerCartBadge) {
        headerCartBadge.textContent = totalItems;
        headerCartBadge.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

// Load cart popup with items
function loadCartPopup() {
    const cartPopupItems = document.getElementById('cartPopupItems');
    const cartPopupTotal = document.getElementById('cartPopupTotal');
    
    if (!cartPopupItems || !cartPopupTotal) return;
    
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    
    if (cart.length === 0) {
        cartPopupItems.innerHTML = '<div class="empty-cart-message">عربة التسوق فارغة</div>';
        cartPopupTotal.textContent = '0.00';
        return;
    }
    
    let totalAmount = 0;
    let popupHtml = '';
    
    // Add items to popup
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        totalAmount += itemTotal;
        
        popupHtml += `
            <div class="cart-popup-item">
                <div class="cart-popup-item-details">
                    <div class="cart-popup-item-name">${item.name}</div>
                    <div class="cart-popup-item-price">${item.quantity} x ${item.price.toFixed(2)} ريال</div>
                </div>
                <div class="cart-popup-item-total">${itemTotal.toFixed(2)} ريال</div>
            </div>
        `;
    });
    
    cartPopupItems.innerHTML = popupHtml;
    cartPopupTotal.textContent = totalAmount.toFixed(2);
}

// Load cart page with items from localStorage
function loadCartPage() {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cartSummary = document.getElementById('cart-summary');
    
    if (!cartItemsContainer) return;
    
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<div class="empty-cart-message">عربة التسوق فارغة</div>';
        if (cartSummary) cartSummary.style.display = 'none';
        return;
    }
    
    let cartHtml = '';
    let totalPrice = 0;
    
    // Generate cart items
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        totalPrice += itemTotal;
        
        cartHtml += `
            <div class="cart-item" id="item-${item.id}">
                <img src="/api/placeholder/100/100" alt="صورة المنتج" class="cart-item-image">
                <div class="cart-item-details">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-vendor">المتجر: ${item.vendor}</div>
                    <div class="cart-item-price" id="price-${item.id}" data-price="${item.price}">${item.price.toFixed(2)} ريال</div>
                    
                    <div class="cart-item-actions">
                        <div class="quantity-control">
                            <div class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</div>
                            <div class="quantity-display" id="quantity-${item.id}">${item.quantity}</div>
                            <div class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</div>
                        </div>
                        <div class="remove-btn" onclick="removeItem(${item.id})">إزالة</div>
                    </div>
                    
                    <div class="cart-item-subtotal" id="subtotal-${item.id}">${itemTotal.toFixed(2)} ريال</div>
                </div>
            </div>
        `;
    });
    
    // Update cart items
    cartItemsContainer.innerHTML = cartHtml;
    
    // Update summary if it exists
    if (cartSummary) {
        // Show summary
        cartSummary.style.display = 'block';
        
        // Update summary values
        const tax = totalPrice * 0.15; // 15% VAT
        const shipping = 50; // Fixed shipping cost
        const totalWithTaxAndShipping = totalPrice + tax + shipping;
        
        // Find and update summary elements
        const summaryRows = cartSummary.querySelectorAll('.summary-row');
        if (summaryRows.length >= 3) {
            // Products total
            const productsTotal = summaryRows[0].querySelector('div:nth-child(2)');
            if (productsTotal) productsTotal.textContent = totalPrice.toFixed(2) + ' ريال';
            
            // Tax
            const taxTotal = summaryRows[1].querySelector('div:nth-child(2)');
            if (taxTotal) taxTotal.textContent = tax.toFixed(2) + ' ريال';
            
            // Update final total
            const finalTotal = document.getElementById('cart-total');
            if (finalTotal) finalTotal.textContent = totalWithTaxAndShipping.toFixed(2) + ' ريال';
        }
    }
}

// Update quantity of an item in the cart
function updateQuantity(itemId, change) {
    const quantityElement = document.getElementById('quantity-' + itemId);
    if (!quantityElement) return;
    
    let quantity = parseInt(quantityElement.innerText);
    quantity += change;
    
    if (quantity < 1) quantity = 1;
    quantityElement.innerText = quantity;
    
    // Update localStorage
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    const itemIndex = cart.findIndex(item => parseInt(item.id) === parseInt(itemId));
    
    if (itemIndex !== -1) {
        cart[itemIndex].quantity = quantity;
        localStorage.setItem('planPerfectCart', JSON.stringify(cart));
    }
    
    // Update UI
    updateSubtotal(itemId, quantity);
    updateTotal();
    updateCartBadge();
}

// Update subtotal for an item
function updateSubtotal(itemId, quantity) {
    const priceElement = document.getElementById('price-' + itemId);
    const subtotalElement = document.getElementById('subtotal-' + itemId);
    
    if (!priceElement || !subtotalElement) return;
    
    const price = parseFloat(priceElement.getAttribute('data-price'));
    const subtotal = price * quantity;
    
    subtotalElement.innerText = subtotal.toFixed(2) + ' ريال';
}

// Update total in cart
function updateTotal() {
    const subtotals = document.querySelectorAll('.cart-item-subtotal');
    let total = 0;
    
    subtotals.forEach(element => {
        const value = parseFloat(element.innerText);
        if (!isNaN(value)) {
            total += value;
        }
    });
    
    // Update products total
    const summaryRows = document.querySelectorAll('.summary-row');
    if (summaryRows.length >= 3) {
        const productsTotal = summaryRows[0].querySelector('div:nth-child(2)');
        if (productsTotal) productsTotal.textContent = total.toFixed(2) + ' ريال';
        
        // Update tax (15%)
        const tax = total * 0.15;
        const taxTotal = summaryRows[1].querySelector('div:nth-child(2)');
        if (taxTotal) taxTotal.textContent = tax.toFixed(2) + ' ريال';
        
        // Add shipping (50 SAR)
        const totalWithTaxAndShipping = total + tax + 50;
        
        // Update final total
        const finalTotal = document.getElementById('cart-total');
        if (finalTotal) finalTotal.textContent = totalWithTaxAndShipping.toFixed(2) + ' ريال';
    }
}

// Remove item from cart
function removeItem(itemId) {
    const itemElement = document.getElementById('item-' + itemId);
    if (!itemElement) return;
    
    // Remove from UI
    itemElement.remove();
    
    // Remove from localStorage
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    const updatedCart = cart.filter(item => parseInt(item.id) !== parseInt(itemId));
    localStorage.setItem('planPerfectCart', JSON.stringify(updatedCart));
    
    // Update totals
    updateTotal();
    updateCartBadge();
    
    // Check if cart is empty
    if (updatedCart.length === 0) {
        const cartItemsContainer = document.getElementById('cart-items-container');
        const cartSummary = document.getElementById('cart-summary');
        
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = '<div class="empty-cart-message">عربة التسوق فارغة</div>';
        }
        
        if (cartSummary) {
            cartSummary.style.display = 'none';
        }
    }
}

// Toggle cart popup
function toggleCartPopup() {
    const cartPopup = document.getElementById('cartPopup');
    if (!cartPopup) return;
    
    if (cartPopup.classList.contains('show')) {
        cartPopup.classList.remove('show');
    } else {
        loadCartPopup();
        cartPopup.classList.add('show');
    }
}

// For previous orders page - reorder functionality
function reorderItems(orderId) {
    initializeCart();
    
    // Define items for each order ID
    const orderItems = {
        1001: [
            { id: 1, name: "تنظيم حفلات الزفاف الفاخرة", vendor: "روعة للمناسبات", price: 15000 }
        ],
        982: [
            { id: 2, name: "باقة الضيافة المتكاملة", vendor: "ضيافة الخليج", price: 8500 },
            { id: 4, name: "تنسيق الزهور والديكور", vendor: "أزهار الياسمين", price: 6000 }
        ],
        954: [
            { id: 17, name: "بوفيه حلويات متنوع", vendor: "سكر ونكهة", price: 3500 },
            { id: 9, name: "باقات البالونات المميزة", vendor: "بالونات الفرح", price: 1200 }
        ],
        923: [
            { id: 3, name: "تصوير فوتوغرافي احترافي", vendor: "لحظات للتصوير", price: 4500 },
            { id: 7, name: "خدمات الصوت والإضاءة", vendor: "الصدى للصوتيات", price: 3500 }
        ]
    };
    
    const items = orderItems[orderId];
    if (!items) return;
    
    // Get current cart
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    
    // Add each item to cart
    items.forEach(item => {
        // Check if item already exists
        const existingItemIndex = cart.findIndex(cartItem => parseInt(cartItem.id) === parseInt(item.id));
        
        if (existingItemIndex !== -1) {
            // If item already exists, increment quantity
            cart[existingItemIndex].quantity += 1;
        } else {
            // Otherwise add new item
            cart.push({
                id: parseInt(item.id),
                name: item.name,
                vendor: item.vendor,
                price: item.price,
                quantity: 1
            });
        }
    });
    
    // Save updated cart
    localStorage.setItem('planPerfectCart', JSON.stringify(cart));
    
    // Show confirmation message
    showConfirmationMessage(`تمت إضافة الطلب رقم ${orderId} إلى عربة التسوق`);
    
    // Update cart badge
    updateCartBadge();
    
    // Redirect to cart page after a short delay
    setTimeout(() => {
        window.location.href = 'cart.html';
    }, 1000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize cart
    initializeCart();
    
    // Update cart badge
    updateCartBadge();
    
    // If on cart page, load cart items
    if (document.getElementById('cart-items-container')) {
        loadCartPage();
    }
    
    // If cart shortcut exists, add event listener
    const cartShortcut = document.getElementById('cartShortcut');
    if (cartShortcut) {
        cartShortcut.addEventListener('click', toggleCartPopup);
    }
    
    // If cart popup close button exists, add event listener
    const cartPopupClose = document.getElementById('cartPopupClose');
    if (cartPopupClose) {
        cartPopupClose.addEventListener('click', function(e) {
            e.stopPropagation();
            const cartPopup = document.getElementById('cartPopup');
            if (cartPopup) cartPopup.classList.remove('show');
        });
    }
    
    // Add event listeners to 'اختيار' buttons on services page
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productCard = this.closest('.product-card');
            if (!productCard) return;
            
            const productId = parseInt(this.getAttribute('data-id'));
            const productName = productCard.querySelector('.product-name').textContent;
            const vendorName = productCard.querySelector('.vendor-name').textContent;
            const priceText = productCard.querySelector('.product-price').textContent;
            const price = parseFloat(priceText.split(' ')[0]);
            
            addToCart(productId, productName, vendorName, price);
        });
    });
});

// Add these functions to the planperfect-cart.js file

// Initialize orders in localStorage if it doesn't exist
function initializeOrders() {
    if (!localStorage.getItem('planPerfectOrders')) {
        localStorage.setItem('planPerfectOrders', JSON.stringify([]));
    }
}

// Create a new order from the current cart
function createOrder() {
    initializeOrders();
    
    // Get current cart
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    
    if (cart.length === 0) {
        alert('لا يمكن إتمام الطلب، عربة التسوق فارغة');
        return false;
    }
    
    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.15; // 15% VAT
    const shipping = 50; // Fixed shipping cost
    const total = subtotal + tax + shipping;
    
    // Generate a new order ID (current timestamp + random digits)
    const orderId = Date.now().toString().slice(-4) + Math.floor(Math.random() * 900 + 100);
    
    // Create the order object
    const order = {
        id: orderId,
        date: new Date().toISOString(),
        status: 'قيد الانتظار', // Pending status
        items: [...cart], // Copy of cart items
        subtotal: subtotal,
        tax: tax,
        shipping: shipping,
        total: total,
        address: 'حي الملقا، الرياض', // Default address, could be from user input
        deliveryDate: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString() // 14 days from now
    };
    
    // Get current orders and add the new one
    const orders = JSON.parse(localStorage.getItem('planPerfectOrders')) || [];
    orders.unshift(order); // Add to the beginning of the array
    
    // Save updated orders
    localStorage.setItem('planPerfectOrders', JSON.stringify(orders));
    
    // Clear the cart
    localStorage.setItem('planPerfectCart', JSON.stringify([]));
    
    return orderId;
}

// Load orders on the previous orders page
function loadOrders() {
    initializeOrders();
    
    // Get orders from localStorage
    const orders = JSON.parse(localStorage.getItem('planPerfectOrders')) || [];
    const ordersContainer = document.querySelector('.orders-container');
    
    if (!ordersContainer) return;
    
    // If there are no saved orders and we have the default example orders, keep them
    if (orders.length === 0 && ordersContainer.children.length > 0) {
        return;
    }
    
    // If there are no orders at all, show a message
    if (orders.length === 0 && ordersContainer.children.length === 0) {
        ordersContainer.innerHTML = `
            <div class="no-orders">
                <h3>لا توجد طلبات سابقة</h3>
                <p>عند قيامك بإتمام طلب، سيظهر هنا.</p>
            </div>
        `;
        return;
    }
    
    // Clear existing orders if we're going to add new ones
    if (orders.length > 0) {
        // Keep any example orders that don't match our real order IDs
        const exampleOrderIds = ['1001', '982', '954', '923'];
        const realOrderElements = Array.from(ordersContainer.children).filter(el => {
            const orderIdMatch = el.querySelector('.order-number')?.textContent.match(/#(\d+)/);
            if (!orderIdMatch) return false;
            const orderId = orderIdMatch[1];
            return !exampleOrderIds.includes(orderId);
        });
        
        // Remove real orders (keeping examples)
        realOrderElements.forEach(el => el.remove());
    }
    
    // Add each real order to the container
    orders.forEach(order => {
        // Format the date
        const orderDate = new Date(order.date);
        const formattedDate = `${orderDate.getDate()} ${getMonthName(orderDate.getMonth())} ${orderDate.getFullYear()}`;
        
        // Format delivery date
        const deliveryDate = new Date(order.deliveryDate);
        const formattedDeliveryTime = `${deliveryDate.getDate()} ${getMonthName(deliveryDate.getMonth())} ${deliveryDate.getFullYear()}، ${deliveryDate.getHours()}:${String(deliveryDate.getMinutes()).padStart(2, '0')} ${deliveryDate.getHours() >= 12 ? 'مساءً' : 'صباحًا'}`;
        
        // Create the order card HTML
        const orderCard = document.createElement('div');
        orderCard.className = 'order-card';
        orderCard.innerHTML = `
            <div class="order-header" onclick="toggleOrderDetails('${order.id}')">
                <div class="order-info">
                    <div class="order-number">طلب رقم #${order.id}</div>
                    <div class="order-date">${formattedDate}</div>
                    <div class="order-total">المجموع: ${order.total.toFixed(2)} ريال</div>
                </div>
                <div class="order-status-container">
                    <div id="order-status-${order.id}" class="order-status ${getStatusClass(order.status)}">${order.status}</div>
                    <span id="expand-icon-${order.id}" class="expand-icon">◀</span>
                </div>
            </div>
            <div id="order-details-${order.id}" class="order-details">
                <h3>تفاصيل الطلب</h3>
                <div class="order-items">
                    ${order.items.map(item => `
                        <div class="order-item">
                            <div class="item-name">${item.name}</div>
                            <div class="item-price">${item.price.toFixed(2)} ريال</div>
                            <div class="item-quantity">${item.quantity}</div>
                            <div class="item-total">${(item.price * item.quantity).toFixed(2)} ريال</div>
                        </div>
                    `).join('')}
                </div>
                <div class="delivery-info">
                    <p><strong>موعد التسليم:</strong> ${formattedDeliveryTime}</p>
                    <p><strong>عنوان التوصيل:</strong> ${order.address}</p>
                </div>
                <div class="order-actions">
                    <button class="reorder-btn" onclick="reorderItems('${order.id}')">إعادة طلب</button>
                    ${order.status !== 'ملغي' && order.status !== 'تم التوصيل' ? 
                        `<button id="cancel-btn-${order.id}" class="cancel-btn" onclick="cancelOrder('${order.id}')">إلغاء الطلب</button>` : 
                        ''}
                </div>
            </div>
        `;
        
        // Insert at the beginning of the container (before example orders)
        ordersContainer.insertBefore(orderCard, ordersContainer.firstChild);
    });
}

// Helper function to get month name in Arabic
function getMonthName(monthIndex) {
    const monthsArabic = [
        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];
    return monthsArabic[monthIndex];
}

// Helper function to get status class
function getStatusClass(status) {
    switch(status) {
        case 'قيد الانتظار': return 'pending';
        case 'قيد التجهيز': return 'processing';
        case 'تم التوصيل': return 'delivered';
        case 'ملغي': return 'canceled';
        default: return 'pending';
    }
}

// Enhanced reorderItems function that handles both predefined and saved orders
function reorderItems(orderId) {
    initializeCart();
    
    // Define items for predefined order IDs
    const predefinedOrderItems = {
        1001: [
            { id: 1, name: "تنظيم حفلات الزفاف الفاخرة", vendor: "روعة للمناسبات", price: 15000 }
        ],
        982: [
            { id: 2, name: "باقة الضيافة المتكاملة", vendor: "ضيافة الخليج", price: 8500 },
            { id: 4, name: "تنسيق الزهور والديكور", vendor: "أزهار الياسمين", price: 6000 }
        ],
        954: [
            { id: 17, name: "بوفيه حلويات متنوع", vendor: "سكر ونكهة", price: 3500 },
            { id: 9, name: "باقات البالونات المميزة", vendor: "بالونات الفرح", price: 1200 }
        ],
        923: [
            { id: 3, name: "تصوير فوتوغرافي احترافي", vendor: "لحظات للتصوير", price: 4500 },
            { id: 7, name: "خدمات الصوت والإضاءة", vendor: "الصدى للصوتيات", price: 3500 }
        ]
    };
    
    // Check if this is a predefined order or saved order
    let items = null;
    if (predefinedOrderItems[orderId]) {
        items = predefinedOrderItems[orderId];
    } else {
        // Look for the order in saved orders
        const orders = JSON.parse(localStorage.getItem('planPerfectOrders')) || [];
        const order = orders.find(o => o.id === orderId);
        if (order) {
            items = order.items;
        }
    }
    
    if (!items) return;
    
    // Get current cart
    const cart = JSON.parse(localStorage.getItem('planPerfectCart')) || [];
    
    // Add each item to cart
    items.forEach(item => {
        // Check if item already exists
        const existingItemIndex = cart.findIndex(cartItem => parseInt(cartItem.id) === parseInt(item.id));
        
        if (existingItemIndex !== -1) {
            // If item already exists, increment quantity
            cart[existingItemIndex].quantity += 1;
        } else {
            // Otherwise add new item
            cart.push({
                id: parseInt(item.id),
                name: item.name,
                vendor: item.vendor,
                price: item.price,
                quantity: item.quantity || 1
            });
        }
    });
    
    // Save updated cart
    localStorage.setItem('planPerfectCart', JSON.stringify(cart));
    
    // Show confirmation message
    showConfirmationMessage(`تمت إضافة الطلب رقم ${orderId} إلى عربة التسوق`);
    
    // Update cart badge
    updateCartBadge();
    
    // Redirect to cart page after a short delay
    setTimeout(() => {
        window.location.href = 'cart.html';
    }, 1000);
}

// Enhanced cancelOrder function to work with saved orders
function cancelOrder(orderId) {
    if (confirm(`هل أنت متأكد من إلغاء الطلب رقم ${orderId}؟`)) {
        // Update the status of the order in localStorage
        const orders = JSON.parse(localStorage.getItem('planPerfectOrders')) || [];
        const orderIndex = orders.findIndex(order => order.id === orderId);
        
        if (orderIndex !== -1) {
            // Update the status to canceled
            orders[orderIndex].status = 'ملغي';
            localStorage.setItem('planPerfectOrders', JSON.stringify(orders));
        }
        
        // Update the UI
        const statusElement = document.getElementById(`order-status-${orderId}`);
        if (statusElement) {
            statusElement.textContent = 'ملغي';
            statusElement.className = 'order-status canceled';
        }
        
        const cancelBtn = document.getElementById(`cancel-btn-${orderId}`);
        if (cancelBtn) {
            cancelBtn.style.display = 'none';
        }
        
        // Show confirmation
        showConfirmationMessage(`تم إلغاء الطلب رقم ${orderId} بنجاح`);
    }
}