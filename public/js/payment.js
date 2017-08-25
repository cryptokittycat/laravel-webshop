	function stripeTokenHandler(token) {
	  // Insert the token ID into the form so it gets submitted to the server
	  var form = document.getElementById('payment-form');
	  var hiddenInput = document.createElement('input');
	  hiddenInput.setAttribute('type', 'hidden');
	  hiddenInput.setAttribute('name', 'stripeToken');
	  hiddenInput.setAttribute('value', token.id);
	  form.appendChild(hiddenInput);

	  // Submit the form
	  form.submit();
	}

	/* get items from localstorage. return Array */
	function getItems() {
		list = [];
		if(localStorage.getItem('items')) {
            let items = JSON.parse(localStorage.getItem('items'));
            if(typeof items.forEach !== "function") {
            	items = [items]	//make sure its an array
            }
            return items;
        }
        return 0;
	}

	/* add items to checkout form */
	function addToForm() {
		let items = getItems();
		for (let i = items.length - 1; i >= 0; i--) {
			let data = {
				'name' : items[i].name,
				'size' : items[i].size,
				'color' : items[i].color,
				'amount' : items[i].amount,
				'price' : items[i].price,
			};
			let jstring = JSON.stringify(data);

			$('#payment-items').append(
				"<input type='hidden' name='items[]' value='"+jstring+"'/>" +
				"<div><span>" + items[i].name + "</span>" +
				"<span>" + items[i].size + "</span>" +
				"<span>" + items[i].color + "</span>" +
				"<span>" + items[i].amount + "</span>" +
				"<span class='item-price'>" + items[i].price + "</span></div>");
		}
		updateItemTotal();
	}

	/* update total in form */
	function updateItemTotal() {
        var total = 0;
        $('.item-price').each(function() {
            total += parseFloat($(this).text());
        });
        /* if total == 0 disable button */
        if(total == 0) {
        	$('#order-btn').attr("disabled",true);
        	$('#order-btn').css("background-color", "#e7e7e7"); 
        }else {
        	$('#order-btn').attr("disabled",false);
        	$('#order-btn').css("background-color", "#337ab7"); 
        }
        $('#payment-items').append('<hr><span>Total: ' + total.toFixed(2) + '$</span>');
    }

$(document).ready(function() {
	
	var stripe = Stripe('pk_test_W0P4yew2XJYObuE9zFy2ovlR');
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
	var style = {
	  base: {
	    // Add your base input styles here. For example:
	    fontSize: '16px',
	    lineHeight: '24px'
	  }
	};

	// Create an instance of the card Element
	var card = elements.create('card', {style: style});

	// Add an instance of the card Element into the `card-element` <div>
	card.mount('#card-element');

	card.addEventListener('change', function(event) {
	  var displayError = document.getElementById('card-errors');
	  if (event.error) {
	    displayError.textContent = event.error.message;
	  } else {
	    displayError.textContent = '';
	  }
	});

	var form = document.getElementById('payment-form');
	form.addEventListener('submit', function(event) {
	  event.preventDefault();

	  stripe.createToken(card).then(function(result) {
	    if (result.error) {
	      // Inform the user if there was an error
	      var errorElement = document.getElementById('card-errors');
	      errorElement.textContent = result.error.message;
	    } else {
	      // Send the token to your server
	      stripeTokenHandler(result.token);
	    }
	  });
	});

    $('#payment-form').submit(function() {
	   removeAllStorage();
	});

	addToForm();
});