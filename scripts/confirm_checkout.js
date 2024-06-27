window.addEventListener("load", function() {
    var inputElements = document.querySelectorAll('input');

    inputElements.forEach(function(inputElement) {
        inputElement.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
            event.preventDefault();
            }
        });
    });

    calculateTotal();
});

function updateSubtotal(item_id, quantity, price) {
    var spanElement = document.getElementById("subtotal"+item_id);
    spanElement.textContent = quantity * price;

    calculateTotal();
}

function calculateTotal() {
    var subtotals = document.getElementsByClassName("item_subtotal");
    var fee = document.getElementById("fee").value;
    var total = 0.00;

    for (var i = 0; i < subtotals.length; i++) {
        total += parseFloat(subtotals[i].textContent);
    }

    var total_fee = total * fee;
    var final_total = total_fee + total;

    document.getElementById("cart_subtotal").textContent = total;
    document.getElementById("app_fee").textContent = total_fee.toFixed(2);
    document.getElementById("total").textContent = final_total.toFixed(2);
}

function validateForm() {
    var other_radio = document.getElementById("other");
    var other_text = document.getElementById("other_text");

    console.log("hello");
    if(other_radio.checked) {
        if (other_text.value === "") { 
            alert("Enter name of the other payment method");
            return false;
        }
    }
    
    return confirm("Are you sure you want to confirm payment?");
}