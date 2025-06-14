document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    const productModal = document.getElementById('productModal');
    const cartModal = document.getElementById('cartModal');
    const closeButtons = document.querySelectorAll('.close-button');
    const modalImage = document.getElementById('modalProductImage');
    const modalTitle = document.getElementById('modalProductTitle');
    const modalDescription = document.getElementById('modalProductDescription');
    const modalPrice = document.getElementById('modalProductPrice');
    const buyNowButton = document.getElementById('buyNowButton');
    const addToCartButton = document.getElementById('addToCartButton');
    const cartIcon = document.getElementById('cartIcon');
    const cartCount = document.getElementById('cartCount');
    const cartItemsList = document.getElementById('cartItems');
    const cartTotalSpan = document.getElementById('cartTotal');
    const checkoutButtonCart = document.getElementById('checkoutButtonCart');

    let currentProductId = null; // Para saber qué producto está en el modal

    // --- Funciones del Modal de Producto ---
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            currentProductId = this.dataset.productId; // Obtener el ID del producto
            
            // Cargar los detalles del producto usando AJAX
            // ASUMIMOS que 'detalle_producto.php' está en la misma carpeta que 'tienda.php'
            fetch('detalle_producto.php?id=' + currentProductId)
                .then(response => {
                    // Verificar si la respuesta es un 404 o similar antes de intentar parsear JSON
                    if (!response.ok) {
                        // Si la respuesta no es OK (ej: 404, 500), lanzamos un error
                        // Esto enviará el control al bloque .catch()
                        throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
                    }
                    return response.json(); // Intentar parsear como JSON
                })
                .then(data => {
                    if (data.success) {
                        // Si no hay imagen en la DB, usa una ruta vacía o un placeholder
                        modalImage.src = data.product.imagen ? data.product.imagen : ''; // Deja vacío si no hay URL
                        modalImage.alt = data.product.tipo_producto;
                        modalTitle.textContent = data.product.tipo_producto;
                        modalDescription.textContent = data.product.descripcion;
                        modalPrice.textContent = 'Bs. ' + parseFloat(data.product.precio).toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        
                        // Actualizar botones con el ID del producto
                        buyNowButton.dataset.productId = currentProductId;
                        addToCartButton.dataset.productId = currentProductId;

                        productModal.style.display = 'flex'; // Mostrar el modal
                    } else {
                        // Si success es false, mostrar el mensaje de error del PHP
                        alert('Error al cargar los detalles del producto: ' + data.message);
                    }
                })
                .catch(error => {
                    // Este catch capturará errores de red, 404, y errores de parseo de JSON
                    console.error('Error fetching product details:', error);
                    alert('Error de conexión al cargar los detalles del producto. Verifica la consola para más detalles.');
                });
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', function(event) {
        if (event.target == productModal) {
            productModal.style.display = 'none';
        }
        if (event.target == cartModal) {
            cartModal.style.display = 'none';
        }
    });

    // --- Funciones del Carrito de Compras ---
    function updateCartCount() {
        // ASUMIMOS que 'agregar_carrito.php' está en la misma carpeta que 'tienda.php'
        fetch('agregar_carrito.php?action=get_count')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
                }
                return response.json();
            })
            .then(data => {
                cartCount.textContent = data.count;
                cartCount.style.display = data.count > 0 ? 'block' : 'none';
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }

    function loadCartItems() {
        // ASUMIMOS que 'agregar_carrito.php' está en la misma carpeta que 'tienda.php'
        fetch('agregar_carrito.php?action=get_items')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
                }
                return response.json();
            })
            .then(data => {
                cartItemsList.innerHTML = '';
                let total = 0;
                if (data.items && data.items.length > 0) {
                    data.items.forEach(item => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            <div class="item-info">
                                <span class="item-name">${item.tipo_producto}</span>
                                <span class="item-price">Bs. ${parseFloat(item.precio).toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} x ${item.quantity}</span>
                            </div>
                            <button class="remove-item" data-id="${item.id_producto}">Eliminar</button>
                        `;
                        cartItemsList.appendChild(li);
                        total += parseFloat(item.precio) * item.quantity;
                    });
                } else {
                    cartItemsList.innerHTML = '<li>El carrito está vacío.</li>';
                }
                cartTotalSpan.textContent = 'Total: Bs. ' + total.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                updateCartCount(); // Actualizar el contador del icono del carrito
            })
            .catch(error => console.error('Error loading cart items:', error));
    }

    // Agregar al carrito desde el modal
    addToCartButton.addEventListener('click', function() {
        const productId = this.dataset.productId;
        // ASUMIMOS que 'agregar_carrito.php' está en la misma carpeta que 'tienda.php'
        fetch('agregar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=add&id=' + productId + '&quantity=1'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                updateCartCount();
                productModal.style.display = 'none'; // Cerrar modal después de agregar
            } else {
                alert('Error al agregar al carrito: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            alert('Error de conexión al agregar al carrito. Verifica la consola para más detalles.');
        });
    });

    // Abrir el modal del carrito
    cartIcon.addEventListener('click', function() {
        loadCartItems(); // Cargar los items cada vez que se abre el carrito
        cartModal.style.display = 'flex';
    });

    // Eliminar item del carrito (delegación de eventos para botones dinámicos)
    cartItemsList.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-item')) {
            const productId = event.target.dataset.id;
            // ASUMIMOS que 'agregar_carrito.php' está en la misma carpeta que 'tienda.php'
            fetch('agregar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=remove&id=' + productId
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadCartItems(); // Recargar la lista del carrito
                } else {
                    alert('Error al eliminar del carrito: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error removing from cart:', error);
                alert('Error de conexión al eliminar del carrito. Verifica la consola para más detalles.');
            });
        }
    });

    // Botón Comprar Ahora del modal de producto
    buyNowButton.addEventListener('click', function() {
        const productId = this.dataset.productId;
        // Limpiar el carrito actual y añadir solo este producto
        // ASUMIMOS que 'agregar_carrito.php' está en la misma carpeta que 'tienda.php'
        fetch('agregar_carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=clear_and_add&id=' + productId + '&quantity=1'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirigir a la página de finalizar compra
                window.location.href = 'finalizar_compra.php'; // ASUMIMOS que finalizar_compra.php está en la misma carpeta
            } else {
                alert('Error al preparar la compra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error buying now:', error);
            alert('Error de conexión al procesar la compra. Verifica la consola para más detalles.');
        });
    });

    // Botón Finalizar Compra del modal de carrito
    checkoutButtonCart.addEventListener('click', function() {
        // ASUMIMOS que 'agregar_carrito.php' está en la misma carpeta que 'tienda.php'
        fetch('agregar_carrito.php?action=get_count')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status + ' - URL: ' + response.url);
                }
                return response.json();
            })
            .then(data => {
                if (data.count > 0) {
                    window.location.href = 'finalizar_compra.php'; // ASUMIMOS que finalizar_compra.php está en la misma carpeta
                } else {
                    alert('El carrito está vacío. Agrega productos antes de finalizar la compra.');
                }
            })
            .catch(error => console.error('Error checking cart for checkout:', error));
    });

    // Inicializar el contador del carrito al cargar la página
    updateCartCount();
});