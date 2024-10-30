<?php include 'inc/header.php'; ?>
<?php include 'inc/authentication.php'; ?>
<?php include 'functions.php'; ?>

<?php


if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];

  $search_fields = [];
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture search inputs from the form
    if (!empty($_POST['Product_name'])) {
      $search_fields['hotel_name'] = $_POST['Product_name'];
    }
    if (!empty($_POST['min_price'])) {
      $search_fields['price >='] = $_POST['min_price'];
    }
    if (!empty($_POST['max_price'])) {
      $search_fields['price <='] = $_POST['max_price'];
    }
  }



  // Handle the product selection
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select'])) {

    // Get the selected product details from the form
    $selected_product_id = $_POST['product_id'];
    $user_id2 = $_POST['user_id'];
    // Prepare the SQL statement for inserting into luxury_orders
    $stmt = $conn->prepare("INSERT INTO luxury_orders (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id2, $selected_product_id);

    // Execute the statement and check if successful
    if ($stmt->execute()) {
      echo "Product added to orders successfully!";
      // Redirect to the same page to prevent form resubmission
      header("Location: " . $_SERVER['PHP_SELF'] . "?user_id=" . urlencode($user_id));
      exit(); // Ensure no further code is executed after the redirect

    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  }



?>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Get the order ID from the form
    $luxury_order_id = (int) $_POST['luxury_order_id'];
    // Perform the delete operation
    $delete_query = "DELETE FROM luxury_orders WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, 'i', $luxury_order_id);

    if (mysqli_stmt_execute($delete_stmt)) {
      // Redirect to the same page to refresh the data
      header("Location: add-continues-order.php?user_id=" . $user_id);
      exit;
    } else {
      echo "Error deleting record: " . mysqli_error($conn);
    }

    mysqli_stmt_close($delete_stmt);
  }
  ?>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $luxury_order_id = $_POST['luxury-order-id'];
    $order_id = $_POST['order_id'];

    $sql = "UPDATE `luxury_orders` SET order_id = '$order_id' WHERE id = '$luxury_order_id'";
    if ($conn->query($sql) === TRUE) {
      echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $conn->error;
    }
  }


  // SQL SELECT query
  $sql = "SELECT * FROM users WHERE id = '$user_id'";
  $result = $conn->query($sql);

  // Check if there are results
  if ($result->num_rows > 0) {
    // Fetch and output data for each row
    $row = $result->fetch_assoc();
  ?>


    <div id="continues-orders-main-container" class="p-5">
      <!-- continues container header -->
      <div class="flex items-center justify-between">
        <h5 class="text-xl">Add</h5>
        <!-- <i><a href="continues-orders.html" class="fa-regular fa-xmark py-3 px-1"></a></i>  -->
      </div>
      <form action="" method="post">
        <!-- continues-orders-container -->
        <div id="continues-orders-container" class="flex pt-5 ">
          <input type="hidden" name="luxury-order-id" value="<?php echo $_SESSION['luxury_order_id'] ?>">
          <div id="continues-orders-container-left">
            <div class="container w-50 p-6 bg-white dark:bg-zinc-800 dark:shadow-lg" style="border-radius: 15px;">
              <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Balance</label>
                <input type="text" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-600 rounded-md p-2"
                  value="<?php echo $row['total_balance']; ?>" readonly disabled style="cursor: not-allowed;" />
              </div>
            <?php } else {
            echo "No results found";
          } ?>
            <div class="mb-4">
              <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">* After how many orders to start
                consecutive orders</label>
              <input type="text" name="order_id" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-600 rounded-md p-2" />
            </div>
            <h2 class="text-lg font-semibold mb-2 text-zinc-700 dark:text-zinc-300">Number of consecutive orders</h2>
            <div style="height: 270px;overflow: auto;">
              <table class="min-w-full border-collapse border border-zinc-300 dark:border-zinc-600">
                <thead class="sticky top-0">

                  <tr class="bg-gray-100">
                    <th class="border border-zinc-300 dark:border-zinc-600 p-2">ID</th>
                    <th class="border border-zinc-300 dark:border-zinc-600 p-2">Product name</th>
                    <th class="border border-zinc-300 dark:border-zinc-600 p-2">Product price</th>
                    <th class="border border-zinc-300 dark:border-zinc-600 p-2">Operate</th>
                  </tr>

                </thead>
                <tbody>
                  <?php
                  $tbl_name = 'luxury_orders';
                  $hotels = get_luxury_order($tbl_name, $search_fields);

                  $total_pages = isset($_SESSION['total_pages']) ? $_SESSION['total_pages'] : 1;
                  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                  if (!empty($hotels)) {
                    foreach ($hotels as $hotel) {
                  ?>
                      <tr>
                        <td class="border border-zinc-300 dark:border-zinc-600 p-2"><?php echo $hotel['hotel_id']; ?></td>
                        <td class="border border-zinc-300 dark:border-zinc-600 p-2"><?php echo $hotel['hotel_name']; ?></td>
                        <td class="border border-zinc-300 dark:border-zinc-600 p-2"><?php echo $hotel['hotel_price']; ?></td>
                        <td class="border border-zinc-300 dark:border-zinc-600 p-2">
                          <form action="" method="post">
                            <input type="hidden" name="luxury_order_id" value="<?php echo $hotel['luxury_order_id']; ?>">
                            <button type="submit" name="delete" class="bg-red-500 hover:bg-red-600 text-white dark:text-white rounded px-4 py-1">Remove</button>
                            <?php $_SESSION['luxury_order_id'] = $hotel['luxury_order_id'];  ?>
                          </form>
                        </td>

                      </tr>

                  <?php
                    }
                  } else {
                    echo "<tr><td colspan='4'>0 results</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
            </div>
          </div>
          <div id="continues-orders-container-right">
            <div class="p-6 bg-background">
              <h2 class="text-xl font-semibold mb-4">Product List</h2>
              <form method="POST" action="#">
                <div class="mb-4 flex items-center">
                  <input type="text" placeholder="Product name" name="Product_name" class="border border-border rounded-md p-2 mr-2 w-full" />
                  <div class="flex items-center">
                    <input type="number" name="min_price" placeholder="Minimum am" class="border border-border rounded-md p-2 mr-2" />
                    <input type="number" name="max_price" placeholder="Maximum am" class="border border-border rounded-md p-2" />
                  </div>
                  <button type="submit" class="bg-blue-500 text-white hover:bg-primary/80 p-2 rounded-md ml-2">Search</button>
                </div>
              </form>
              <div class="" style="height: 360px;overflow: auto;">
                <table class="min-w-full border-collapse border border-border">
                  <thead class="sticky top-0">
                    <tr class="bg-muted text-muted-foreground">
                      <th class="border border-border p-2 bg-gray-100">ID</th>
                      <th class="border border-border p-2 bg-gray-100">Product name</th>
                      <th class="border border-border p-2 bg-gray-100">Product price</th>
                      <th class="border border-border p-2 bg-gray-100">Operate</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Assuming get_records function handles querying based on the conditions in $search_fields
                    $tbl_name = 'hotel_rooms';
                    $users = search_records($tbl_name, $search_fields);

                    $total_pages = $_SESSION['total_pages'] ?? 1;
                    $page = $_GET['page'] ?? 1;

                    if (!empty($users)) {
                      foreach ($users as $hotel) {
                    ?>
                        <tr>
                          <form action="" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $hotel['id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <td class="border border-border p-2"><?php echo $hotel['id']; ?></td>
                            <td class="border border-border p-2"><?php echo $hotel['hotel_name']; ?></td>
                            <td class="border border-border p-2"><?php echo $hotel['price']; ?></td>
                            <td class="border border-border p-2"><button type="submit" name="select"
                                class="bg-gray-100 border text-black hover:bg-gray-200 p-1 rounded">Select</button>
                            </td>
                          </form>
                        </tr>
                    <?php
                      }
                    } else {
                      echo "<tr><td colspan='4'>No results found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <nav>
                <ul class="pagination">
                  <?php
                  for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item " . ($i == $page ? "active" : "") . "'><a class='page-link' href='add-continues-order.php?page=" . $i . "'>" . $i . "</a></li>";
                  }
                  ?>
                </ul>
              </nav>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end pt-3 gap-5">
          <a href="continues-orders.html"><button class="p-2 bg-gray-300 rounded text-black">Cancel</button></a>
          <input type="submit" name="confirm" value="Confirm" class="p-2 bg-blue-500 rounded text-white">
        </div>
      </form>
    </div>
  <?php } ?>
  <script src="assets/js/main.js"></script>

  </body>

  </html>