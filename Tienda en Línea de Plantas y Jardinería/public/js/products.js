/**
 * Datos de productos - Tienda en Línea de Plantas y Jardinería
 * Información de los productos disponibles en la tienda
 */

const ProductsData = {
    // Productos de plantas
    plants: [
        {
            id: 'petunias',
            name: 'Petunias',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-qrYG0E3-Xr5L0cdguwLAELivjPiEUPw2dRsW1Cia2T5eDM_SYkcm__0qfV3PeWfDWlZKFs-KXeHom3X38LRYQN5FOGjvZcW2jGCEcSTdTzKJyeqpCgU907tFVHIQr7u4nVANg-Q8VPMXYIxLHmL5ZRWy6TGsh9LqvIG3jcDLZ3Bm2AZxk1qaDe-EqTwW1n2zevN3tzliX-QeopRUI751k949vxf7lDFu7GntyUktO3TbMAhJp552QjVSQoG7pdmKoY84d3sOhHTr',
            alt: 'Petunias en una cesta colgante',
            category: 'flores',
            description: 'Hermosas petunias en colores vibrantes, perfectas para cestas colgantes.',
            inStock: true,
            tags: ['flores', 'colgantes', 'exterior']
        },
        {
            id: 'romero',
            name: 'Romero',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDBXZ3AHsVyU3pm_XCHfyVzqwdsG2lUb_Df0PmvPVCM0fcKmNyvc7KUUmnISuot8w_TdAkyZrDJ8VI6uAyiRfzDdpIEHnn2M548z_UrKr3ZUlY4XD5M9gnrSiyVgnW6vaKRleGt5m6CWLatvhda7JVk6OXt95CCQX6zrqJcIyrimbdY37N-YK95BAWNXASvwn2FHSLzrRiEhZ06X8y1MW3wnIMgKfmnTpQh0aMNHtXMtynPJUXBn4as1rAYItim79QabPNhYyy7tsKV',
            alt: 'Flores blancas de Romero',
            category: 'hierbas',
            description: 'Planta aromática de romero con pequeñas flores blancas, ideal para cocina.',
            inStock: true,
            tags: ['hierbas', 'aromáticas', 'cocina', 'exterior']
        },
        {
            id: 'diente-de-leon',
            name: 'Diente de León',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAaS_V6aUb2mNlXwy5e8RYUnp_LA42Hs1k8wGixUxMUOZ_KaMCjImSiB4RP-Q9C1c-HGxz_Fr-PQ3rCeONd5t6-bdL284A-O3y62FZKLGryJsFZJ69vUSMp6BgI-MJ-wUkvAjzsX_L6MYsTjKQYRs1Ak9sKgz8OiGl0wwbR4vEfjo6RajJv0WkwLq9NxyzTHxkzZBCBSthKmzM82NznZVzE9SNr8tCR96G0nChRCUaNgj7ml0ScKmgGxBRcm8uJm5tjwZ9fwYguGC3C',
            alt: 'Flor amarilla de Diente de León',
            category: 'silvestres',
            description: 'Diente de león con flores amarillas, planta medicinal y comestible.',
            inStock: true,
            tags: ['silvestres', 'medicinales', 'comestibles', 'exterior']
        },
        {
            id: 'albahaca',
            name: 'Albahaca',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZ3BMTkI3VS8G3JSdlVAdYraNkmKqYHA9mvAAupLiAcNSr40-U6D0vB2i_kkH6RJdmUTktVfMIXmxE20H_JcKybUkm8M_Jjwj-KnLVkdtUWSVSg7bSqnrNCks0UZsCc9umadyvQlZLrtzaAQuU3XqAkXqIIqwDVtL6lXtcnzS8gTgWcXJoZ_Xwt6jNRgSvPTuUDMEHQ3PZsW_ZkNbgzGIoyLUjt0i28DT_h-HQeHIr_o4vtwUfrYKVIUzrFuf-zV6DkLq4DRqXPB6h',
            alt: 'Planta de Albahaca en una maceta negra',
            category: 'hierbas',
            description: 'Albahaca fresca en maceta negra, perfecta para ensaladas y pesto.',
            inStock: true,
            tags: ['hierbas', 'aromáticas', 'cocina', 'interior']
        },
        {
            id: 'aloe-vera',
            name: 'Aloe Vera',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBUilmCj_cnH2qfeCKmQI5tF6ey370z9-Sap4BjtnHgplzW9BtVGx6nxvG1RK9XixOR8bAelAj6zlN7lEKkSOJ9JnDLvkq6XseOdM0opHNqOL7eVZmqzDogu35Mr4jfmYz3idDhTSIoDzYIoc9xHz7CFy7jFSOW1xzoZlzF57e3S_W9l8iGY85wXn5CMeRIQSU9JPGfQ9aLV3fTigj91anVJ7DqRPFxyNm9CQy11vG2sTNNIuE_NFyLFHBvI0dzV_cQuTQUunRhtxt7',
            alt: 'Planta de Aloe Vera en una maceta de terracota',
            category: 'suculentas',
            description: 'Aloe vera en maceta de terracota, planta medicinal y decorativa.',
            inStock: true,
            tags: ['suculentas', 'medicinales', 'decorativas', 'interior']
        },
        {
            id: 'aloe-vera-flores',
            name: 'Aloe Vera',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDsVfVVyz_JjlPPSPXPwJCnYi5aA0bY5_LuLf8nspao_Dn7AvjTHgGy0R_lmNikbyMsqLmsDTGmIhxI9LT0RjV94qhAWWOz6KuDSbyA2d04Lu6tqqMfzS9m6laZD1grDhKyI83oJTY8gds7Udz-qAnWufFOWm87oEr3u7ZTXZQXCQ5msUpAp6j659VggLeI5-83eSjo1z2Tz0OzhFkuwkIUmZavBdUcyoOL2skMO1WehDHh1ocgCzvkbxcR6-F6B_WNY8XzSi7ZAMjQ',
            alt: 'Flores rosas de Aloe Vera en una maceta',
            category: 'suculentas',
            description: 'Aloe vera con flores rosas, variedad especial con floración.',
            inStock: true,
            tags: ['suculentas', 'flores', 'decorativas', 'interior']
        },
        {
            id: 'cinta',
            name: 'Cinta',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBN7Jk2WnaOSMmdirkyxf0zJGe636DVaSCx2JFM--GggguzIdU6_Z5GSDy2JtGEb24E5vkfPPeQWFb6ZEoYGXh7WLPaRPB1An_0uvUnRuc_00othMR5MwSBTH23M8e_oI6H1fK8ONCoWrHWOBUlTbAN98btPWN3xn0E1JmBYZpDtVSLnHJyW8bPInr_SSzglHABiPlTvXSDclg1uGz-lHekpHeLkErZfPLY7LCYTyARTdFmqpMav82ca8xFNk4yQnGiEycjy3vn2gk7',
            alt: 'Planta Cinta en una maceta blanca',
            category: 'interior',
            description: 'Planta cinta en maceta blanca, ideal para decorar interiores.',
            inStock: true,
            tags: ['interior', 'decorativas', 'fácil-cuidado', 'purificadoras']
        },
        {
            id: 'azalea',
            name: 'Azalea',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCRJd4KQZD54NsR_jaZVC2cXjkj9RXzJizU_Hxc-EWU-WjQHEHLlaeazslBvXMsj_y0BmGVZXguqJCOUl2hb9aa9d55bBqCG9EwhAEJ4Ft6k0GbEGpT5ER9Uv-G1fd75IkJ-CyW2KFX04QOFstIuAR7rkdwH5DgoOvsh0xFchthD8NjtUnNynRl5yPgY827E68jDAXHQ2cqHtQaHHBknCvIpOcpRPSde-uNGrfgQ-3TsRiGrUtwyWZXbfWE9IimRvvx0pL56tKIooAD',
            alt: 'Flores rosas de Azalea en una maceta',
            category: 'flores',
            description: 'Azalea con flores rosas, planta ornamental muy apreciada.',
            inStock: true,
            tags: ['flores', 'ornamentales', 'exterior', 'primavera']
        },
        {
            id: 'clavel',
            name: 'Clavel',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuChy8EW6HgpR_qgtrkZvkYqn_U6wwAURMK0e1lgB6mWs-zOAwEZGyEjn60cFd6Rp8sQvDhnaFNCPQFVa9o4hOiijNYmQeGcDDVfqw286aE58-FZU7UdNUUe3ad4U219m7r0kudl6qgvF_VtyEOfzZ0xQzNlgvldojFaie1ycTxGp02xkbPsq94ZfrjZEeE3jh7sDxLMf2B0hqvfXjTqrR-a8k3SL45ZKP6Tdmmlz4QyVHGODFJS37Gmpv-VoQ19Noi5dQkaBKUb142L',
            alt: 'Flores rosas de Clavel en una maceta',
            category: 'flores',
            description: 'Claveles con flores rosas, flores clásicas y fragantes.',
            inStock: true,
            tags: ['flores', 'fragantes', 'clásicas', 'exterior']
        },
        {
            id: 'hortensia',
            name: 'Hortensia',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCcZrdkyESAsTdpV0RBsgwC4m2i-MyPuZ6fZDmGA5-ANBsnP8lM6UtgDnZeaLTSZBgl8xEIgB_rdW8-3rUQfwhxcA3j2Vvnc6iqReBmfZvBr2lyHcpFT8VKYb84JOmSMZsYSMrKZISDvymvw1gqAs_-LCBdb00dXpYKTafGCsdSlS70ZaoK6Q6FCtHNn_IJw0YkOLYUKy1HvIv_Ww6VHnOAeXXJ4K7U_rLNH7vO5KV3DmUFtRzbiFURyVvthCPO13c-Y50b5urelmJc',
            alt: 'Flores rojas de Hortensia en una maceta',
            category: 'flores',
            description: 'Hortensia con flores rojas, arbusto ornamental de gran belleza.',
            inStock: true,
            tags: ['flores', 'arbustos', 'ornamentales', 'exterior']
        },
        {
            id: 'margarita',
            name: 'Margarita',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCz4XEv5V1-zr-jq_xVFy-NPcDtjKSiN6g-bjRp4PVeYgP-0d9qFAmodwH7gfH8-6cwoFFOH__kVfYViNCkkdicDzA4EVdQY9EqBmI4YE15qzgBiiHyk_55IJVVoXtExy4VPI2PwMOOsMLiMQc0HFL9f3OmdNGr_4GrybtkhqfuEQdNRObOCf8noVPK-UzwRnBvZmjescJj76Enz38x7I1tbCxpiG5JxVyDudHjVSD6dgnc8KRc3HTFtdj5l-U5nkULLjfrOS5M8fcv',
            alt: 'Margaritas blancas y amarillas en una maceta',
            category: 'flores',
            description: 'Margaritas blancas y amarillas, flores clásicas y alegres.',
            inStock: true,
            tags: ['flores', 'clásicas', 'alegres', 'exterior']
        },
        {
            id: 'orquidea',
            name: 'Orquídea',
            price: 0.00,
            image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuC9GavvnDCMYKADUzVF4GUQuKszb5j42CIsuhwpgTjWxkIPTFmp5HsWZ60j0DDBY-tAL1rpblcvuYPA192s_pMWfi_x_GifYOx8bN7Rt4xCVStFNpDHQlXY_lbdoYga2OS-ctz64x4vytGMFn7w3DR0g77IC6WXeZEcngcvkoODDRXw9hmBEMQwPv_rfx-rIuXCjlC0lg_f8ZX50nk6n7VSvJqCASpMh_XJuWL2fli1uQfNRvaoS2qZI0h2dQyBHlCH19ZEVK4f9pjp',
            alt: 'Orquídea blanca en una maceta blanca',
            category: 'flores',
            description: 'Orquídea blanca elegante, flor exótica y sofisticada.',
            inStock: true,
            tags: ['flores', 'exóticas', 'elegantes', 'interior']
        }
    ],

    // Categorías de productos
    categories: [
        {
            id: 'flores',
            name: 'Flores',
            description: 'Plantas con flores ornamentales',
            icon: 'local_florist'
        },
        {
            id: 'hierbas',
            name: 'Hierbas',
            description: 'Plantas aromáticas y medicinales',
            icon: 'eco'
        },
        {
            id: 'suculentas',
            name: 'Suculentas',
            description: 'Plantas resistentes y fáciles de cuidar',
            icon: 'park'
        },
        {
            id: 'interior',
            name: 'Interior',
            description: 'Plantas para decorar interiores',
            icon: 'home'
        },
        {
            id: 'silvestres',
            name: 'Silvestres',
            description: 'Plantas nativas y medicinales',
            icon: 'nature'
        }
    ],

    // Métodos de búsqueda
    searchProducts: function(query) {
        const searchTerm = query.toLowerCase().trim();
        
        if (!searchTerm) {
            return this.plants;
        }

        return this.plants.filter(product => {
            return product.name.toLowerCase().includes(searchTerm) ||
                   product.description.toLowerCase().includes(searchTerm) ||
                   product.tags.some(tag => tag.toLowerCase().includes(searchTerm)) ||
                   product.category.toLowerCase().includes(searchTerm);
        });
    },

    // Obtener productos por categoría
    getProductsByCategory: function(categoryId) {
        return this.plants.filter(product => product.category === categoryId);
    },

    // Obtener producto por ID
    getProductById: function(productId) {
        return this.plants.find(product => product.id === productId);
    },

    // Obtener productos en stock
    getInStockProducts: function() {
        return this.plants.filter(product => product.inStock);
    },

    // Obtener productos con precio
    getProductsWithPrice: function() {
        return this.plants.filter(product => product.price > 0);
    }
};

// Exportar datos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ProductsData;
} else {
    window.ProductsData = ProductsData;
}
