/**
 * Configuración de la aplicación
 * Tienda en Línea de Plantas y Jardinería
 */

const AppConfig = {
    // Configuración general
    appName: 'Tienda en Línea de Plantas y Jardinería',
    version: '1.0.0',
    debug: true,

    // URLs y endpoints
    api: {
        baseUrl: '/api/v1',
        endpoints: {
            products: '/products',
            categories: '/categories',
            cart: '/cart',
            orders: '/orders',
            users: '/users'
        }
    },

    // Configuración del carrito
    cart: {
        maxItems: 50,
        storageKey: 'tienda_plantas_cart',
        autoSave: true,
        showNotifications: true
    },

    // Configuración de productos
    products: {
        itemsPerPage: 12,
        imagePlaceholder: '/images/placeholder-plant.jpg',
        defaultPrice: 0.00,
        currency: 'MXN',
        currencySymbol: '$'
    },

    // Configuración de búsqueda
    search: {
        minLength: 2,
        debounceDelay: 300,
        maxResults: 50
    },

    // Configuración de notificaciones
    notifications: {
        duration: 3000,
        position: 'top-right',
        types: {
            success: 'success',
            error: 'error',
            warning: 'warning',
            info: 'info'
        }
    },

    // Configuración de PayPal
    paypal: {
        sandbox: true,
        currency: 'MXN',
        locale: 'es_MX'
    },

    // Configuración de responsive
    breakpoints: {
        mobile: 576,
        tablet: 768,
        desktop: 992,
        large: 1200
    },

    // Configuración de animaciones
    animations: {
        duration: 300,
        easing: 'ease-out',
        staggerDelay: 100
    },

    // Mensajes de la aplicación
    messages: {
        cart: {
            added: 'Producto agregado al carrito',
            removed: 'Producto removido del carrito',
            cleared: 'Carrito limpiado',
            empty: 'El carrito está vacío'
        },
        search: {
            noResults: 'No se encontraron productos',
            enterTerm: 'Por favor ingresa un término de búsqueda'
        },
        general: {
            loading: 'Cargando...',
            error: 'Ha ocurrido un error',
            success: 'Operación exitosa'
        }
    }
};

// Exportar configuración
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AppConfig;
} else {
    window.AppConfig = AppConfig;
}
