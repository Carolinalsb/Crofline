// public/js/cart.js
(function () {
    function setupCart() {
        var cartOverlay   = document.getElementById('cart-overlay');
        if (!cartOverlay) return; // se a página não tiver carrinho, ignora

        var cartCloseBtn  = document.getElementById('cart-close-btn');
        var cartContinue  = document.getElementById('cart-continue');
        var cartCheckout  = document.getElementById('cart-checkout');
        var cartToggleBtn = document.getElementById('btn-cart');

        function openCart() {
            cartOverlay.classList.add('active');
        }

        function closeCart() {
            cartOverlay.classList.remove('active');
        }

        // Expor global pra qualquer página usar
        window.croflineCart = {
            open: openCart,
            close: closeCart
        };
        // Compat de nome, se quiser chamar direto
        window.openCart = openCart;

        // Ícone do carrinho no menu
        if (cartToggleBtn) {
            cartToggleBtn.addEventListener('click', openCart);
        }

        // Botões do drawer
        if (cartCloseBtn) {
            cartCloseBtn.addEventListener('click', closeCart);
        }
        if (cartContinue) {
            cartContinue.addEventListener('click', closeCart);
        }

        // Fechar clicando fora do drawer
        cartOverlay.addEventListener('click', function (e) {
            if (e.target === cartOverlay) {
                closeCart();
            }
        });

        // Checkout – placeholder por enquanto
        if (cartCheckout) {
            cartCheckout.addEventListener('click', function () {
                alert('Aqui depois vocês redirecionam para a página de resumo da compra.');
            });
        }

        // Abrir carrinho automaticamente quando vier da sessão
        if (window.CROFLINE_CART_OPEN) {
            openCart();
        }
        if (window.CROFLINE_CART_SUCCESS_MESSAGE) {
            alert(window.CROFLINE_CART_SUCCESS_MESSAGE);
        }
        if (window.CROFLINE_CART_ERROR_MESSAGE) {
            alert(window.CROFLINE_CART_ERROR_MESSAGE);
        }
    }

    document.addEventListener('DOMContentLoaded', setupCart);
})();
