<?php
include 'inc/header.php';
include 'inc/sidebar.php';
include 'functions.php';
?>

<div class="main-container">
    <?php include 'inc/nav.php'; ?>

     <!-- Navigation Tabs -->
     <div class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex items-center space-x-4">
            <button class="px-4 py-2 bg-blue-500 text-white rounded-md"><a href="index.php">Home</a></button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"><a href="order-list.php"> Order list </a><a href="index.php"
                    class="fa-regular fa-xmark"></a></button>
            <!-- <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Withdrawal List</button> -->
        </div>
    </div>

    <div class="container mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Order List</h2>

        <form action="order-list.php" method="GET">
            <div class="flex flex-wrap gap-4 mb-6">
                <input type="text" placeholder="Order number" name="order_number" class="flex-grow border border-gray-300 rounded-lg px-4 py-2">
                <input type="text" placeholder="Member Account" name="member_account"  class="flex-grow border border-gray-300 rounded-lg px-4 py-2">
                <select class="border border-gray-300 rounded-lg px-4 py-2" name="status">
                    <option value="">Select Status</option>
                    <option value="success">Success</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <div class="flex items-center gap-5">
                    <div class="border border-gray-300 rounded-lg flex px-2 items-center">
                        <label for="" class="font-bold">Order Date</label>
                        <input type="date" class="px-4 py-2" name="order_date">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white rounded-lg px-6 py-2 hover:bg-blue-600">Search</button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto" id="scrollable-data">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 text-left border">ID</th>
                        <th class="py-3 px-4 text-left border">Order Number</th>
                        <th class="py-3 px-4 text-left border">Member Account</th>
                        <th class="py-3 px-4 text-left border">Product Name</th>
                        <th class="py-3 px-4 text-left border">Product Price</th>
                        <th class="py-3 px-4 text-left border">Transaction Quantity</th>
                        <th class="py-3 px-4 text-left border">Transaction Amount</th>
                        <th class="py-3 px-4 text-left border">Commission</th>
                        <th class="py-3 px-4 text-left border">Status</th>
                        <th class="py-3 px-4 text-left border">Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $search_fields = [];

                    // Capture search inputs from the form
                    if (!empty($_GET['member_account'])) {
                        $search_fields['u.username'] = $_GET['member_account'];
                    }
                    if (!empty($_GET['member_type'])) {
                        $search_fields['u.member_type'] = $_GET['member_type'];
                    }
                    if (!empty($_GET['status'])) {
                        $search_fields['r.status'] = $_GET['status'];
                    }
                    if (!empty($_GET['order_date'])) {
                        $search_fields['o.order_date'] = $_GET['order_date'];
                    }
                    
                    $tbl_name = 'orders AS o JOIN users AS u ON o.user_id = u.id';

                    $users = get_records($tbl_name, $search_fields);

                    $total_pages = $_SESSION['total_pages'] ?? 1;
                    $page = $_GET['page'] ?? 1;

                    if (!empty($users)) {
                        foreach ($users as $order) {
                            // Fetch username based on the user_id from the order
                            $user_id = $order['user_id'];
                            $sql = "SELECT username FROM users WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $user = $result->fetch_assoc();
                            $username = $user['username'] ?? 'Unknown';
                            ?>
                            <tr>
                                <td class="py-3 px-4 border"><?php echo $order['id']; ?></td>
                                <td class="py-3 px-4 border"><?php echo $order['order_number']; ?></td>
                                <td class="py-3 px-4 border"><?php echo $username; ?></td>
                                <td class="py-3 px-4 border"><?php echo $order['product_name']; ?></td>
                                <td class="py-3 px-4 border">$<?php echo $order['product_price']; ?></td>
                                <td class="py-3 px-4 border"><?php echo $order['transaction_quantity']; ?></td>
                                <td class="py-3 px-4 border">$<?php echo $order['transaction_amount']; ?></td>
                                <td class="py-3 px-4 border">$<?php echo $order['commission']; ?></td>
                                <td class="py-3 px-4 border"><?php echo $order['status']; ?></td>
                                <td class="py-3 px-4 border"><?php echo $order['order_date']; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='10'>No results found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <nav>
                <ul class="pagination">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item " . ($i == $page ? "active" : "") . "'><a class='page-link' href='order-list.php?page=" . $i . "'>" . $i . "</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<script src="assets/js/main.js"></script>
</body>
</html>
