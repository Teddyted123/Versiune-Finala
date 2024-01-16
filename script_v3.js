// script.js

// Asigură-te că scriptul se execută doar după încărcarea completă a documentului
$(function () {
    // Adaugăm codul necesar pentru adăugarea în coș
    $('.addToCart').click(function (event) {
        event.preventDefault(); // Previne comportamentul implicit al butonului

        var productId = $(this).attr('data-product-id');
        var selectedSize = $(this).closest('form').find('select[name="selected_size"]').val();
        var quantity = $(this).closest('form').find('input[name="quantity"]').val();

        // Trimitem datele prin AJAX pentru a le procesa în add_to_cart.php
        $.ajax({
            type: 'POST',
            url: 'add_to_cart.php',
            data: {
                product_id: productId,
                selected_size: selectedSize,
                quantity: quantity
            },
            success: function (response) {
                // Afișează mesajul de răspuns cu alert
                alert(response);
            
                // În funcție de răspuns, poți face orice alte acțiuni necesare aici
                if (response !== 'Product added to cart successfully!') {
                    // Handle error
                }
            },
            error: function (xhr, status, error) {
                // Afișăm detalii despre eroare în consolă
                console.error(error);

                // Handle error
                alert('Error adding product to cart. Please try again.');
            }
        });
    });

    // Handle star hover effect
    $('.star-rating .star').hover(function () {
        var currentStar = parseInt($(this).attr('data-value'));
        $(this).css('color', 'yellow'); // Change color for the hovered star
        $(this).prevAll('.star').css('color', 'yellow'); // Change color for previous stars
    }, function () {
        $(this).parent().find('.star').css('color', '#ccc'); // Reset all stars to default color
    });

    // Handle star click event
    // Inside the star click event, update the rating UI for the logged-in user
    $('.star-rating .star').click(function () {
        var ratingValue = parseInt($(this).attr('data-value'));
        var productId = $(this).parent().attr('data-product-id');
        var userRating = $(this).parent().attr('data-user-rating');

        // Update the UI based on the selected rating
        $(this).parent().find('.star').css('color', '#ccc'); // Reset all stars to default color
        for (var i = 1; i <= ratingValue; i++) {
            $(this).parent().find('.star[data-value="' + i + '"]').css('color', 'yellow'); // Change color for selected stars
        }

        // Send an AJAX request to update the rating in the database
        $.ajax({
            url: 'rate_product.php',
            method: 'POST',
            data: { rating: ratingValue, product_id: productId },
            success: function (response) {
                // Handle success response (if needed)
                alert(response);
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error);
                alert('Error updating product rating. Please try again.');
            }
        });
    });

    // Adăugăm funcționalitate pentru ștergerea produsului din coș
    $('.removeFromCart').click(function (event) {
        event.preventDefault();

        var productId = $(this).attr('data-product-id');

        $.ajax({
            type: 'POST',
            url: 'remove_from_cart.php',
            data: { product_id: productId },
            success: function (response) {
                // Afișează mesajul de răspuns cu alert
                alert(response);

                // În funcție de răspuns, poți face orice alte acțiuni necesare aici
                if (response === 'Product removed from cart successfully!') {
                    // Refresh pagina după ștergerea produsului
                    location.reload();
                } else {
                    // Handle error
                }
            },
            error: function (xhr, status, error) {
                // Afișăm detalii despre eroare în consolă
                console.error(error);

                // Handle error
                alert('Error removing product from cart. Please try again.');
            }
        });
    });
    
});

