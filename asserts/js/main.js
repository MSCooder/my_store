// 1. Navbar Search Toggle
const searchInput = document.querySelector('.search-container input');
if(searchInput) {
    searchInput.addEventListener('focus', () => {
        searchInput.parentElement.style.width = '250px';
    });
}

// 2. Quantity Selector Logic (Image 7 Cart)
function updateQuantity(id, change) {
    let qtyInput = document.querySelector(`#qty-${id}`);
    let currentQty = parseInt(qtyInput.value);
    
    if(currentQty + change >= 1) {
        qtyInput.value = currentQty + change;
        // Yahan AJAX call lag sakti hai database update ke liye
    }
}

// 3. Delete Confirmation for Admin (Image 2)
function confirmDelete(id) {
    if(confirm("Are you sure you want to delete this product?")) {
        window.location.href = `delete-product.php?id=${id}`;
    }
}