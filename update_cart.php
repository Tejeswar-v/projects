// Update Quantity
async function updateQuantity(productId, newQuantity) {
    const quantityInput = document.getElementById(`quantity-${productId}`);
    const originalQuantity = quantityInput.defaultValue;
    
    // Validate input
    newQuantity = parseInt(newQuantity);
    if (isNaN(newQuantity)) {
        quantityInput.value = originalQuantity;
        return;
    }
    
    try {
        quantityInput.disabled = true;
        
        const response = await fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `updateQuantity=true&productId=${productId}&quantity=${newQuantity}`,
        });

        const result = await response.json();

        if (result.success) {
            // Update the UI
            quantityInput.defaultValue = newQuantity;
            updateRowTotal(productId, newQuantity);
            updateCartTotal();
            
            // Update max quantity allowed (based on new stock)
            const newMax = parseInt(quantityInput.max) + (originalQuantity - newQuantity);
            quantityInput.max = newMax;
        } else {
            alert(result.message || 'Failed to update quantity.');
            quantityInput.value = originalQuantity;
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
        alert('An error occurred while updating quantity.');
        quantityInput.value = originalQuantity;
    } finally {
        quantityInput.disabled = false;
    }
}

// Update row total
function updateRowTotal(productId, newQuantity) {
    const row = document.querySelector(`input[id="quantity-${productId}"]`).closest('tr');
    const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace('₹', ''));
    const total = price * newQuantity;
    row.querySelector('td:nth-child(4)').textContent = `₹${total.toFixed(2)}`;
}

// Update cart total
function updateCartTotal() {
    const rows = document.querySelectorAll('#cart-table tbody tr:not(.empty-cart)');
    let total = 0;
    
    rows.forEach(row => {
        const rowTotal = parseFloat(row.querySelector('td:nth-child(4)').textContent.replace('₹', ''));
        total += rowTotal;
    });
    
    document.getElementById('total-price').textContent = `₹${total.toFixed(2)}`;
}