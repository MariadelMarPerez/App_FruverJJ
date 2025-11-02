<x-app-layout>
    
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <style>
        /* Optional: Improve Choices.js appearance */
        .choices__input { padding: 0.5rem 0.75rem !important; background-color: white !important; }
        .choices__list--dropdown .choices__item--selectable { padding-right: 1rem !important; }
        .choices[data-type*="select-one"]::after { right: 11.5px !important; margin-top: -3px !important; }
    </style>
    @endpush

    <div class="py-12">
        {{-- ancho completo --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 

            {{-- Banner  --}}
            <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-lg shadow-lg mb-6">
                <div class="p-6">
                    <div>
                        <h2 class="text-2xl font-bold">Registrar Nueva Venta</h2>
                        <p class="text-emerald-100 text-sm mt-1">Selecciona productos, ajusta cantidades y procesa el pago.</p>
                    </div>
                </div>
            </div>

            <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
                @csrf
                {{--  Volvemos a grid-cols-1 md:grid-cols-2 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 

                    {{-- Columna Izquierda: Ocupa 1 de 2 columnas en MD+ --}}
                    <div class="space-y-6"> 
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 h-full"> 
                             <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                Añadir Productos
                            </h3>
                            <div class="flex items-start space-x-2 mb-4"> 
                                <select id="product-search-select" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Escribe para buscar...">
                                    <option value="">Escribe o selecciona un producto...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->precio }}" data-stock="{{ $product->stock }}" data-name="{{ $product->nombre }}">
                                            {{ $product->nombre }} (Stock: {{ number_format(floatval($product->stock), 2) }} Kg) - ${{ number_format($product->precio, 0) }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-primary-button type="button" id="add-to-cart-btn" class="self-end mb-1"> 
                                    Añadir
                                </x-primary-button>
                            </div>
                        
                             <h3 class="text-lg font-semibold text-gray-700 mb-4 mt-6 flex items-center"> 
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Carrito (<span id="cart-count">0</span>)
                            </h3>
                            <div id="cart-items" class="divide-y divide-gray-200 max-h-60 overflow-y-auto pr-2 border rounded-md p-2 bg-gray-50"> 
                                <p id="empty-cart-message" class="text-gray-500 text-sm p-4 text-center">El carrito está vacío.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Derecha: Ocupa 1 de 2 columnas en MD+ --}}
                    <div class="space-y-6"> 
                         <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 sticky top-6 h-full"> 
                             <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Cliente (Opcional)
                            </h3>
                            <input type="text" id="cliente" name="cliente" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mb-6" placeholder="Nombre del cliente">

                             <h3 class="text-lg font-semibold text-gray-700 mb-4 mt-6 flex items-center"> 
                                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Resumen de Venta
                            </h3>
                            <div class="space-y-2 text-sm mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span id="cart-subtotal" class="font-medium text-gray-800">$0.00</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                                    <span class="text-gray-800">Total a Pagar:</span>
                                    <span id="cart-total" class="text-green-600">$0.00</span>
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-6 border-t border-gray-200 space-y-3"> 
                                <button type="button" id="proceed-to-payment-btn" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center disabled:opacity-50" disabled>
                                    Proceder al Pago
                                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </button>
                                 <a href="{{ route('sales.index') }}" class="block text-center text-sm text-gray-600 hover:text-gray-800">Cancelar Venta</a>
                             </div>
                         </div>
                    </div>
                </div>

                <input type="hidden" name="cart" id="cart-input">

                {{-- Modal de Pago (sin cambios) --}}
                <div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden z-50">
                    {{-- ... Contenido del modal ... --}}
                    <div class="relative bg-white rounded-lg shadow-xl p-8 max-w-lg w-full mx-4">
                         <h3 class="text-xl font-semibold text-gray-800 mb-6">Confirmar Pago</h3>
                        <div class="mb-4 text-center">
                            <span class="text-sm text-gray-600">Total a Pagar</span>
                            <p id="modal-total" class="text-3xl font-bold text-green-600">$0.00</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Método de Pago *</label>
                            <select name="metodo_pago" id="metodo_pago" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccione...</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                        </div>
                        <div id="efectivo-fields" class="hidden space-y-4 mb-4">
                            <div>
                                <label for="monto_recibido" class="block text-sm font-medium text-gray-700 mb-1">Monto Recibido *</label>
                                <input type="number" name="monto_recibido" id="monto_recibido" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" step="0.01" min="0" placeholder="Ej: 50000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cambio a Devolver</label>
                                <p id="cambio-display" class="text-lg font-semibold text-blue-600">$0.00</p>
                            </div>
                        </div>
                        <div id="transferencia-fields" class="hidden mb-4">
                            <label for="referencia_transferencia" class="block text-sm font-medium text-gray-700 mb-1">Referencia/ID Transacción *</label>
                            <input type="text" name="referencia_transferencia" id="referencia_transferencia" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: TRN12345">
                        </div>
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                            <button type="button" id="cancel-payment-btn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-semibold text-sm">Volver</button>
                            <button type="submit" id="confirm-sale-btn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Confirmar Venta
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
             const productSelectElement = document.getElementById('product-search-select');
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const cartItemsContainer = document.getElementById('cart-items');
            const emptyCartMessage = document.getElementById('empty-cart-message');
            const cartSubtotalEl = document.getElementById('cart-subtotal');
            const cartTotalEl = document.getElementById('cart-total');
            const cartCountEl = document.getElementById('cart-count');
            const cartInput = document.getElementById('cart-input');
            const proceedBtn = document.getElementById('proceed-to-payment-btn');
            const paymentModal = document.getElementById('payment-modal');
            const cancelPaymentBtn = document.getElementById('cancel-payment-btn');
            const metodoPagoSelect = document.getElementById('metodo_pago');
            const efectivoFields = document.getElementById('efectivo-fields');
            const transferenciaFields = document.getElementById('transferencia-fields');
            const montoRecibidoInput = document.getElementById('monto_recibido');
            const cambioDisplay = document.getElementById('cambio-display');
            const modalTotalEl = document.getElementById('modal-total');
            const confirmSaleBtn = document.getElementById('confirm-sale-btn');
            const saleForm = document.getElementById('sale-form');
            const referenciaInput = document.getElementById('referencia_transferencia');

            let cart = [];

             const choices = new Choices(productSelectElement, { /* ... options ... */ });

            function renderCart() { 
                cartItemsContainer.innerHTML = '';
                let subtotal = 0;
                if (cart.length === 0) {
                    emptyCartMessage.style.display = 'block';
                    proceedBtn.disabled = true;
                } else {
                    emptyCartMessage.style.display = 'none';
                    proceedBtn.disabled = false;
                    cart.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        const itemElement = document.createElement('div');
                        itemElement.className = 'py-3 flex justify-between items-center cart-item';
                        itemElement.innerHTML = `
                            <div class="flex-grow pr-4">
                                <p class="font-medium text-gray-800">${item.name}</p>
                                <p class="text-xs text-gray-500">Precio: $${parseFloat(item.price).toFixed(2)}/Kg - Stock: ${item.stock} Kg</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="number" value="${item.quantity}" min="0.01" step="0.01" 
                                       class="w-24 px-2 py-1 border border-gray-300 rounded-md text-sm quantity-change" 
                                       data-index="${index}" data-stock="${item.stock}">
                                <span class="text-xs text-gray-500">Kg</span>
                                <span class="font-medium text-gray-700 text-sm w-20 text-right">$${itemTotal.toFixed(2)}</span>
                                <button type="button" class="text-red-500 hover:text-red-700 remove-item" data-index="${index}" title="Quitar producto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        `;
                        cartItemsContainer.appendChild(itemElement);
                    });
                }
                cartSubtotalEl.textContent = `$${subtotal.toFixed(2)}`;
                cartTotalEl.textContent = `$${subtotal.toFixed(2)}`;
                modalTotalEl.textContent = `$${subtotal.toFixed(2)}`;
                cartCountEl.textContent = cart.length;
                cartInput.value = JSON.stringify(cart.map(item => ({ id: item.id, quantity: item.quantity })));
             }
            function addToCart() { 
                 const selectedValue = choices.getValue(true);
                 if (!selectedValue) { Swal.fire('Error', 'Por favor, selecciona un producto de la lista.', 'error'); return; }
                 const productId = selectedValue;
                 const selectedOption = productSelectElement.querySelector(`option[value="${productId}"]`);
                 if (!selectedOption) return;
                 const productStock = parseFloat(selectedOption.getAttribute('data-stock'));
                 const existingItem = cart.find(item => item.id == productId);
                 if (existingItem) { Swal.fire({ icon: 'info', title: 'Producto ya en el carrito', text: 'Puedes ajustar la cantidad directamente en la lista.', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }); return; }
                 if (productStock > 0) cart.push({ id: productId, name: selectedOption.getAttribute('data-name'), price: parseFloat(selectedOption.getAttribute('data-price')), quantity: 1, stock: productStock });
                 else Swal.fire('Stock Agotado', `El producto ${selectedOption.getAttribute('data-name')} no tiene stock.`, 'error');
                 choices.setChoiceByValue('');
                 renderCart();
            }
             function updateQuantity(inputElement) { 
                 const index = inputElement.getAttribute('data-index');
                 let newQuantity = parseFloat(inputElement.value);
                 const itemStock = parseFloat(inputElement.getAttribute('data-stock')); 
                 const itemName = cart[index].name; 
                 if (isNaN(newQuantity)) newQuantity = 0.01; 
                 if (newQuantity >= 0.01 && newQuantity <= itemStock) cart[index].quantity = newQuantity;
                 else if (newQuantity > itemStock) {
                     cart[index].quantity = itemStock; 
                     inputElement.value = itemStock; 
                     Swal.fire({ icon: 'warning', title: 'Límite de Stock Superado', text: `Solo quedan ${itemStock} Kg de ${itemName}. Se ajustó la cantidad.`, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                 } else { 
                     cart[index].quantity = 0.01; 
                     inputElement.value = 0.01;
                 }
                renderCart(); 
            }
             function removeFromCart(index) { 
                cart.splice(index, 1);
                renderCart();
            }
             function calculateChange() { 
                const total = parseFloat(modalTotalEl.textContent.replace('$', '')) || 0;
                const recibido = parseFloat(montoRecibidoInput.value) || 0;
                const cambio = recibido - total;
                cambioDisplay.textContent = `$${(cambio >= 0 ? cambio : 0).toFixed(2)}`;
                confirmSaleBtn.disabled = (cambio < 0); 
                if (cambio < 0) cambioDisplay.textContent = 'Falta dinero';
            }
            
            addToCartBtn.addEventListener('click', addToCart);
            cartItemsContainer.addEventListener('change', function(e) { if (e.target.classList.contains('quantity-change')) updateQuantity(e.target); });
            cartItemsContainer.addEventListener('click', function(e) { const removeButton = e.target.closest('.remove-item'); if (removeButton) removeFromCart(removeButton.getAttribute('data-index')); });
            proceedBtn.addEventListener('click', () => { if (cart.length > 0) { let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0); modalTotalEl.textContent = `$${total.toFixed(2)}`; montoRecibidoInput.min = total.toFixed(2); paymentModal.classList.remove('hidden'); } });
            cancelPaymentBtn.addEventListener('click', () => { paymentModal.classList.add('hidden'); });
            metodoPagoSelect.addEventListener('change', () => {
                const isEfectivo = metodoPagoSelect.value === 'efectivo';
                const isTransferencia = metodoPagoSelect.value === 'transferencia';
                efectivoFields.classList.toggle('hidden', !isEfectivo);
                transferenciaFields.classList.toggle('hidden', !isTransferencia);
                montoRecibidoInput.required = isEfectivo;
                referenciaInput.required = isTransferencia;
                if (isEfectivo) calculateChange(); 
                else confirmSaleBtn.disabled = false;
            });
            montoRecibidoInput.addEventListener('input', calculateChange); 
            saleForm.addEventListener('submit', function(event) {
                event.preventDefault(); 
                let valid = true;
                let errorMessage = '';
                if (cart.length === 0) { errorMessage = 'El carrito está vacío.'; valid = false; } 
                else if (!metodoPagoSelect.value) { errorMessage = 'Por favor, selecciona un método de pago.'; valid = false; }
                else if (metodoPagoSelect.value === 'efectivo') {
                    if (!montoRecibidoInput.value) { errorMessage = 'Por favor, ingresa el monto recibido.'; valid = false; } 
                    else if (parseFloat(montoRecibidoInput.value) < parseFloat(modalTotalEl.textContent.replace('$', ''))) { errorMessage = 'El monto recibido es menor al total a pagar.'; valid = false; }
                } else if (metodoPagoSelect.value === 'transferencia') {
                    if (!referenciaInput.value.trim()) { errorMessage = 'Por favor, ingresa la referencia de la transferencia.'; valid = false; }
                }
                if (!valid) Swal.fire('Error de Validación', errorMessage, 'error'); 
                else this.submit(); 
            });
            renderCart(); 
        });
    </script>
    @endpush

     @stack('styles') 
</x-app-layout>