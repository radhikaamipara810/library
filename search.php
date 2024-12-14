<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the config file for database connection
include('includes/config.php');

// Check if the user is logged in
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
?>

    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Techno Library Management System | User Dashboard</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

        <style>
            /* General Styles */
            body {
                font-family: 'Open Sans', sans-serif;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-weight: 600;
                margin: 0;
            }

            a {
                text-decoration: none;
                color: #333;
            }

            p {
                font-size: 16px;
                line-height: 1.6;
                color: #555;
            }

            .empty {
                color: red;
                font-weight: bold;
                text-align: center;
            }

            /* Header */
            header {
                background-color: #333;
                padding: 10px 0;
                color: #fff;
                text-align: center;
            }

            header a {
                color: #fff;
                margin: 0 15px;
                font-size: 18px;
            }

            header a:hover {
                color: #f4f4f9;
            }

            /* Search Form */
            .search-form {
                text-align: center;
                margin: 20px 0;
            }

            .search-form form {
                display: inline-block;
                position: relative;
            }

            .search-form input.box {
                width: 300px;
                padding: 10px;
                border-radius: 25px;
                border: 1px solid #ccc;
                outline: none;
            }

            .search-form input.btn {
                background-color: #007bff;
                color: #fff;
                padding: 10px 20px;
                border-radius: 25px;
                border: none;
                margin-left: 10px;
                cursor: pointer;
            }

            .search-form input.btn:hover {
                background-color: #0056b3;
            }

            /* Product Section */
            .products {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                padding: 20px;
            }

            .box-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }

            .box {
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 10px;
                padding: 15px;
                width: 250px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .box img {
                width: 100%;
                height: 200px;
                object-fit: cover;
                border-radius: 10px;
                margin-bottom: 10px;
            }

            .box p {
                margin: 10px 0;
            }

            .box input.qty {
                width: 60px;
                padding: 5px;
                text-align: center;
                margin-top: 10px;
            }

            .box input[type="submit"] {
                background-color: #28a745;
                color: white;
                padding: 8px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 10px;
            }

            .box input[type="submit"]:hover {
                background-color: #218838;
            }

            /* Footer */
            footer {
                background-color: #333;
                color: white;
                text-align: center;
                padding: 10px 0;
                margin-top: 30px;
            }

            footer p {
                margin: 0;
            }

            /* Responsive Styles */
            @media (max-width: 768px) {
                .box-container {
                    flex-direction: column;
                    align-items: center;
                }

                .search-form input.box {
                    width: 100%;
                }

                .box {
                    width: 90%;
                }
            }
        </style>

    </head>

    <body>

        <?php include('includes/header.php'); ?>

        <section class="search-form">
            <form action="" method="POST">
                <input type="text" class="box" placeholder="Search books..." name="search_box" required>
                <input type="submit" class="btn" value="Search" name="search_btn">
            </form>
        </section>

        <section class="products" style="padding-top: 0;">
            <div class="box-container">

                <?php
                // Check if the search button is clicked
                if (isset($_POST['search_btn'])) {
                    // Sanitize the input
                    $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);

                    // Query to search for books in the database
                    $query = "SELECT * FROM tblbooks WHERE BookName LIKE '%$search_box%'";
                    $select_products = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if (!$select_products) {
                        die('Query Failed: ' . mysqli_error($conn));
                    }

                    // Check if any book is found
                    if (mysqli_num_rows($select_products) > 0) {
                        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                            $bookImage = $fetch_products['bookImage'];
                            $imagePath = "admin/bookimg/" . $bookImage;  // Assuming images are stored in 'uploads/books/'
                ?>

                            <form action="" method="POST" class="box">
                                <!-- Display the book image -->
                                <img src="<?php echo $imagePath; ?>" alt="Book Image" class="book-img">

                                <p><?php echo $fetch_products['BookName']; ?> - Price: <?php echo $fetch_products['BookPrice']; ?></p>

                                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $fetch_products['BookName']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $fetch_products['BookPrice']; ?>">
                            </form>

                <?php
                        }
                    } else {
                        echo '<p class="empty">No result found!</p>';
                    }
                } else {
                    echo '<p class="empty">Please search for a book!</p>';
                }
                ?>

            </div>
        </section>

        <?php include('includes/footer.php'); ?>

        <script src="js/script.js"></script>

    </body>

    </html>

<?php
} // End of session check
?>