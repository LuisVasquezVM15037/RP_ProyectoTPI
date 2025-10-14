$(document).ready(function() {
    'use strict';

    const productContainer = $('.row.g-4');
    const searchInput = $('#searchInput');

    // Función para renderizar los productos
    function renderProducts(products) {
        productContainer.empty(); // Limpiar contenedor

        if (products.length === 0) {
            productContainer.html('<p class="text-center text-muted">No se encontraron productos.</p>');
            return;
        }

        products.forEach(product => {
            const productCard = `
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center product-card">
                    <div class="card-content">
                        <div class="bg-white p-2 rounded shadow-sm product-image-wrapper">
                            <img alt="${product.alt}" class="w-100" style="height: 8rem; object-fit: cover;" src="${product.image}"/>
                        </div>
                        <div class="product-info">
                            <h3 class="fw-semibold text-text-light fs-6 mb-0">${product.name}</h3>
                            <p class="fw-bold text-muted mb-0">$${product.price.toFixed(2)}</p>
                        </div>
                        <div class="btn-container">
                            <button class="w-100 btn btn-primary-custom text-white small fw-bold py-2 px-2 rounded d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-cart-plus"></i>
                                <span>AGREGAR AL CARRITO</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            productContainer.append(productCard);
        });
    }

    // Lógica de búsqueda
    function filterProducts() {
        const searchTerm = searchInput.val().toLowerCase().trim();
        const allProducts = window.ProductsData.plants;
        
        const filteredProducts = allProducts.filter(product => {
            return product.name.toLowerCase().includes(searchTerm);
        });

        renderProducts(filteredProducts);
    }

    // Renderizar todos los productos inicialmente
    if (window.ProductsData && window.ProductsData.plants) {
        renderProducts(window.ProductsData.plants);
    } else {
        console.error("Error: No se encontraron los datos de productos (ProductsData).");
        productContainer.html('<p class="text-center text-danger">Error al cargar los productos.</p>');
    }

    // Listener para el input de búsqueda
    if (searchInput.length > 0) {
        searchInput.on('input', filterProducts);
    } else {
        console.error("Error crítico: No se encontró el campo de búsqueda con id='searchInput'.");
    }
});
