document.addEventListener('DOMContentLoaded', function () {
    const starRating = document.getElementById('star-rating');
    if (starRating) {
        const productId = starRating.getAttribute('data-product-id');
        const stars = starRating.querySelectorAll('.fa-star');

        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-rating');
                rateProduct(productId, rating);
            });
        });
    }

    function rateProduct(productId, rating) {
        // Send an AJAX request to update the rating in the database
        // You can use XMLHttpRequest or fetch API for this
        // Example using fetch:
        fetch('rate_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                productId: productId,
                rating: rating,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response if needed
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

// Add this inside your document ready function in script.js
$(document).ready(function () {
    $('.addToCart').on('click', function () {
        const productId = $(this).data('product-id');

        // You can use AJAX to send the product ID to the server for processing
        // For simplicity, let's assume you have a PHP file to handle this (add_to_cart.php)

        $.ajax({
            type: 'POST',
            url: 'add_to_cart.php',
            data: { productId: productId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert('Product added to cart!');
                } else {
                    alert('Failed to add product to cart.');
                }
            },
            error: function () {
                alert('Error adding product to cart.');
            }
        });
    });
});


