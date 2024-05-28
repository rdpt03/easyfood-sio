// Fonction pour afficher l'animation de confirmation
function showConfirmation() {
    var confirmationAnimation = document.getElementById('confirmationAnimation');
    confirmationAnimation.style.display = 'block';
    setTimeout(function() {
        confirmationAnimation.style.display = 'none';
    }, 1500); // durée de l'animation
}

// Mettre à jour le nombre d'articles dans le panier
function updateCartItemCount(count) {
    var cartItemCount = document.getElementById('cartItemCount');
    cartItemCount.innerText = count;
}
