<?php
session_start();

// Enable error reporting for debugging (REMEMBER TO REMOVE IN PRODUCTION)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure this path is correct relative to add_to_cart.php.
// If you're only manipulating the session, db.php isn't strictly needed here,
// but it's fine to keep if you have future plans for DB interaction on this page.
include('./includes/db.php');

$pageTitle = "Item Added to Cart";
$messageHtml = ''; // Initialize an empty string for our HTML message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? null;
    $productName = $_POST['product_name'] ?? 'Unknown Product';
    $productPrice = $_POST['product_price'] ?? 0;

    if ($productId) {
        // Ensure product ID is an integer for security and consistency
        $productId = (int) $productId;

        // Initialize cart session if not set
        if (!isset($_SESSION['cart1'])) {
            $_SESSION['cart1'] = [];
        }

        // --- Corrected: Actual cart logic to add item or increment quantity ---
        // We only store the product ID and its quantity in the session.
        // cart.php will fetch the product's name and price from the database using the product ID.
        //$_SESSION['cart1'][$productId] = ($_SESSION['cart1'][$productId] ?? 0) + 1;
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
        // --- End of corrected cart logic ---

        // Generate the success message HTML
        $messageHtml = "
            <div class='confirmation-card success'>
                <h2>🎉 Item Added to Cart!</h2>
                <p><strong>Product:</strong> " . htmlspecialchars($productName) . "</p>
                <p><strong>Price:</strong> ₹" . htmlspecialchars(number_format($productPrice, 2)) . "</p>
                <p class='small-text'>Quantity in cart: " . htmlspecialchars($_SESSION['cart'][$productId]) . "</p>
                <div class='button-group'>
                    <a href='index.php' class='btn btn-primary'>Continue Shopping</a>
                    <a href='cart.php' class='btn btn-secondary'>View Cart</a> </div>
            </div>";

    } else {
        // Generate the error message HTML if product ID is missing
        $pageTitle = "Error"; // Update page title for error
        $messageHtml = "
            <div class='confirmation-card error'>
                <h2>Oops! Something went wrong.</h2>
                <p>No product ID was received. Please try again.</p>
                <div class='button-group'>
                    <a href='index.php' class='btn btn-primary'>Back to Store</a>
                </div>
            </div>";
    }
} else {
    // Generate the invalid access message HTML if accessed directly without POST
    $pageTitle = "Invalid Access"; // Update page title for invalid access
    $messageHtml = "
        <div class='confirmation-card error'>
            <h2>Invalid Access</h2>
            <p>This page should be accessed by adding a product from the store.</p>
            <div class='button-group'>
                <a href='index.php' class='btn btn-primary'>Go to Store</a>
            </div>
        </div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <?= $messageHtml; ?>
    </div>
</body>
</html>