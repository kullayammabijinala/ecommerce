<?php
// Include the database connection file
include('./includes/db.php'); // Make sure this path is correct for your setup

$products = []; // Initialize products array to prevent errors if no products are found

try {
    // Prepare and execute the SQL query to fetch all products, ordered by ID descending
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
} catch (PDOException $e) {
    // Log the error (to a file, for example) and display a user-friendly message
    error_log("Database Error: " . $e->getMessage());
    echo "<p style='color: red; text-align: center;'>Could not load products at this time. Please try again later.</p>";
    // You might want to stop execution here or display a partial page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Our Awesome Store</title>
    <style>
        /* General Body Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        /* Header Styles */
        h1 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        /* Product Grid Container */
        .product-grid {
            display: grid; /* Enables CSS Grid for layout */
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Responsive columns */
            gap: 25px; /* Space between grid items */
            max-width: 1200px; /* Max width for the grid */
            margin: 0 auto; /* Center the grid */
            padding: 20px 0;
        }

        /* Product Item Styles */
        .product-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center; /* Center content within the card */
            display: flex; /* Use flexbox for internal layout */
            flex-direction: column; /* Stack items vertically */
            justify-content: space-between; /* Distribute space vertically */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; /* Smooth hover effects */
        }

        .product-item:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 6px 12px rgba(0,0,0,0.15); /* Stronger shadow on hover */
        }

        /* Product Image Styles */
        .product-image {
            max-width: 100%; /* Ensure image fits within its container */
            height: 200px; /* Fixed height for uniformity */
            object-fit: contain; /* Ensures the image covers the area without distortion */
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        /* Product Text Styles */
        .product-item h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.5em;
        }

        .product-item .price {
            font-weight: bold;
            color: #e67e22; /* Orange color */
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .product-item .description {
            color: #555;
            font-size: 0.9em;
            flex-grow: 1; /* Allows description to take up available space */
            margin-bottom: 15px;
        }

        /* Add to Cart Button Styles */
        .add-to-cart-form {
            margin-top: auto; /* Pushes the button to the bottom */
        }

        .add-to-cart-btn {
            background-color: #28a745; /* Green color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
            width: 100%; /* Make button full width */
        }

        .add-to-cart-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    <div class="product-grid">
        <?php if (!empty($products)): // Check if there are any products to display ?>
            <?php foreach ($products as $p): ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($p['image'] ?? 'images2/default.png') ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">

                    <h2><?= htmlspecialchars($p['name']) ?></h2>
                    <p class="price">Price: ₹<?= htmlspecialchars(number_format($p['price'], 2)) ?></p>
                    <p class="description"><?= htmlspecialchars($p['description']) ?></p>

                    <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($p['name']) ?>">
                        <input type="hidden" name="product_price" value="<?= htmlspecialchars($p['price']) ?>">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1;">No products found. Please check back later!</p>
        <?php endif; ?>
    </div>
</body>
</html>