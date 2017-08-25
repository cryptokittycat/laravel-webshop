/*
*   This shoppingcart uses Web Storage to store items
*/
localStorageTimeout = 21600; //6 hour session storage

    /* check if epired items exist and delete them */
    function expire() {
        if(localStorage.getItem('items-timestamp')) {
            let timestamp = parseInt(localStorage.getItem('items-timestamp'));
            let now = (+ new Date() / 1000);   // unix timestamp
            if(now - timestamp > localStorageTimeout) {
                removeAllStorage();
            }
        }
    }

    function removeAllStorage() {
        localStorage.removeItem('items');
        localStorage.removeItem('items-timestamp');
        emptyCart();
    }

    function emptyCart() {
        $('#shoppingcart-items').empty();
    }

    /* update html shoppingcart counter */
    function updateCounter() {
        $('#cart-counter').first().text($('.cart-item').length);
    }

    /* update html shoppingcart total */
    function updateTotal() {
        var total = 0;
        $('.cart-item-price').each(function() {
            total += parseInt($(this).text());
        });
        $('#shoppingcart-items').append('<hr><span>Total: ' + total + '$    <a href="/checkout">Go to checkout</a></span>');
    }

    /* remove item from localstorage */
    function removeItem(name) {
        let items = JSON.parse(localStorage.getItem('items'));
        if(items !== null && items.length > 1) {
            /* delete item. if there's only 1, remove all localstorage */
            if(typeof items.forEach === "function") {
                items.forEach(function(item, i) {
                    if(item.name == name) {
                        items.splice(i, 1);
                        localStorage.setItem('items', JSON.stringify(items));
                    }
                })
            }else {
                if(items.name == name) {
                    removeAllStorage();
                }
            }
        }else {
            removeAllStorage();
        }
        updateCart();
    }

    /* generate html cart based on localstorage items */
    function updateCart() {
        emptyCart();
        let items = JSON.parse(localStorage.getItem('items'));
        if(items !== null) {
            if(typeof items.forEach !== "function") {
                items = [items];
            }
            items.forEach(function(item) {
                    $('#shoppingcart-items').append(
                        "<li class='cart-item'>" +
                        "<span>" + item.name + "</span>" +
                        "<span>" + item.size + "</span>" +
                        "<span>" + item.color + "</span>" +
                        "<span>" + item.amount + "</span>" +
                        "<span class='cart-item-price'>" + item.price + "</span>" +
                        "<a class='remove-item' href='#' data-item='" + item.name + "'><i class='fa fa-times' aria-hidden='true'></i></a>" +
                        "</li>");
            });
        localStorage.setItem('items-timestamp', (+ new Date() / 1000) );
        }else {
            removeAllStorage();
            
        }
        updateCounter();
        updateTotal();
    }

$(document).ready(function() {

    $('.add-to-cart').click(function() {
        /* get item info */
        data = $('#item-data').attr('data-item').split('|');
        amount = parseInt($('#amount').val());
        size = $('#select-size option:selected').text();
        color = $('.colorpick-active').text();
        if(color == '') {
            color = $('.colorpick').first().text();
        }
        price = parseFloat(data[1]) * amount;

        var dupe = false;   // duplicate item

        if(localStorage.getItem('items')) {

            let current = JSON.parse(localStorage.getItem('items'));
            if(typeof current.forEach !== "function") {
                current = [current]
            }
            current.forEach(function(item) {
                /* check for duplicate */
                if((item.name == data[0]) && (item.color == color) && (item.size == size)) {
                    item.amount = parseInt(item.amount) + amount;
                    item.price = (parseFloat(item.price) + price).toFixed(2);
                    item.color = color;
                    item.size = size;
                    dupe = true;
                }
            });
            /* no duplicate found. insert as new item */
            if(dupe === false) {
                current = current.concat({"name" : data[0], "amount" : amount, "price" : price, "color" : color, "size" : size});
            }
            localStorage.setItem('items', JSON.stringify(current));
        /* no items found. save current */
        }else {
            localStorage.setItem('items-timestamp', (+ new Date() / 1000) );
            localStorage.setItem('items', JSON.stringify({"name" : data[0], "amount" : amount, "price" : price, "color" : color, "size" : size}));
        }
        $( ".shoppingcart" ).delay(100).fadeOut(150).fadeIn(150); //flicker update effect
        updateCart();
    });

    $(document).on('click', '.remove-item', function(){
        removeItem($(this).attr('data-item'));
    });

    /* check for expiry and update cart */
    expire();
    updateCart();
});