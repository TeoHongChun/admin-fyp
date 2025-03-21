<?php
session_start();
require 'config.php';

// 如果用户未登录，跳转到登录页面
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 读取总销售额
$sales_query = "SELECT SUM(total_price) AS total_sales FROM orders";
$sales_result = mysqli_query($conn, $sales_query);
$sales = mysqli_fetch_assoc($sales_result)['total_sales'] ?? 0;

// 读取订单总数
$orders_count_query = "SELECT COUNT(*) AS total_orders FROM orders";
$orders_count_result = mysqli_query($conn, $orders_count_query);
$total_orders = mysqli_fetch_assoc($orders_count_result)['total_orders'] ?? 0;

// 读取客户总数
$customers_count_query = "SELECT COUNT(*) AS total_customers FROM customers";
$customers_count_result = mysqli_query($conn, $customers_count_query);
$total_customers = mysqli_fetch_assoc($customers_count_result)['total_customers'] ?? 0;

// 读取最近订单
$orders_query = "SELECT orders.order_id, customers.customer_name, products.product_name, orders.total_price, orders.order_status 
                 FROM orders 
                 JOIN customers ON orders.customer_id = customers.customer_id 
                 JOIN order_items ON orders.order_id = order_items.order_id 
                 JOIN products ON order_items.product_id = products.product_id 
                 ORDER BY orders.created_at DESC 
                 LIMIT 5";
$orders_result = mysqli_query($conn, $orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <aside>
        <div class="top">
            <div class="logo">
                <h2><span class="danger">LOGO</span></h2>
            </div>
            <div class="close" id="close_btn">
                <span class="material-symbols-sharp">close</span>
            </div>
        </div>

        <div class="sidebar">
            <a href="#" class="active"><span class="material-symbols-sharp">grid_view</span><h3>Dashboard</h3></a>
            <a href="customers.php"><span class="material-symbols-sharp">person_outline</span><h3>Customers</h3></a>
            <a href="orders.php"><span class="material-symbols-sharp">receipt_long</span><h3>Orders</h3></a>
            <a href="products.php"><span class="material-symbols-sharp">insights</span><h3>Products</h3></a>
            <a href="logout.php"><span class="material-symbols-sharp">logout</span><h3>Logout</h3></a>
        </div>
    </aside>

    <main>
        <h1>Dashboard</h1>

        <div class="insights">
            <div class="sales">
                <span class="material-symbols-sharp">trending_up</span>
                <div class="middle">
                    <div class="left">
                        <h3>Total Sales</h3>
                        <h1>$<?php echo number_format($sales, 2); ?></h1>
                    </div>
                </div>
                <small>Last 24 Hours</small>
            </div>

            <div class="orders">
                <span class="material-symbols-sharp">receipt_long</span>
                <div class="middle">
                    <div class="left">
                        <h3>Total Orders</h3>
                        <h1><?php echo $total_orders; ?></h1>
                    </div>
                </div>
                <small>Last 24 Hours</small>
            </div>

            <div class="customers">
                <span class="material-symbols-sharp">person_outline</span>
                <div class="middle">
                    <div class="left">
                        <h3>Total Customers</h3>
                        <h1><?php echo $total_customers; ?></h1>
                    </div>
                </div>
                <small>Last 24 Hours</small>
            </div>
        </div>

        <div class="recent_order">
            <h2>Recent Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($orders_result)): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td class="<?php echo strtolower($row['order_status']); ?>"><?php echo htmlspecialchars($row['order_status']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="right">
        <div class="top">
            <button id="menu_bar"><span class="material-symbols-sharp">menu</span></button>
            <div class="theme-toggler">
                <span class="material-symbols-sharp active">light_mode</span>
                <span class="material-symbols-sharp">dark_mode</span>
            </div>
            <div class="profile">
                <div class="info">
                    <p><b>Admin</b></p>
                    <p>Administrator</p>
                </div>
                <div class="profile-photo">
                    <img src="uploads/admin/admin.jpg" alt="Admin">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
