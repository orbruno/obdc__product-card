document.addEventListener("DOMContentLoaded", function(){
    console.log('obdc__product-card__update-form-action.js loaded');
    var element = document.querySelector('.variation-id');
    if (element) {
        document.addEventListener("change", function(event){
            var target = event.target;
            if(target.matches('.variation-id')){
                var selectedOption = target.options[target.selectedIndex];
                var variation_id = selectedOption.value;
                var sku = selectedOption.dataset.sku;  // get SKU from selected option
                var form = target.closest('form');
                var action = target.options[target.selectedIndex].getAttribute('data-action');
                form.action = action;
                var variation_id_input = form.querySelector('.variation-id-input');
                if(variation_id_input){
                    variation_id_input.value = variation_id;
                }
                // Check if the default option is selected
                // Get the product_id input
                var product_id_input = form.querySelector('.product-id-input');
                if(product_id_input){
                    var product_id = product_id_input.value;
                    // Update the form action
                    form.action = window.location.href + "?add-to-cart=" + product_id + "&variation_id=" + variation_id + "&quantity=1" + "&sku=" + sku;
                }
            }
        }, false);
        element.selectedIndex = 0;
    }
});