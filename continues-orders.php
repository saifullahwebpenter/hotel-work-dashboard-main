<?php include 'inc/header.php' ?>
<?php include 'inc/authentication.php' ?>
<?php include 'inc/sidebar.php' ?>
<?php include 'functions.php' ?>




<?php
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo "<p style='color: red; font-weight: bold;'>Error: User ID is missing or invalid. Please try again.</p>";
    exit();
}

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id']; 
    //var_dump($id); exit;
}

?>
<?php
// $tbl_name = 'users';
// $users = get_records($tbl_name, $search_fields); 

// $total_pages = isset($_SESSION['total_pages']) ? $_SESSION['total_pages'] : 1;
// $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
// if (!empty($users)) {
?>
<!-- RIGHT_CONTAINER -->
<div class="main-container">
    <?php include 'inc/nav.php' ?>

    <!-- Navigation Tabs -->
    <div class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex items-center space-x-4">
            <button class="px-4 py-2 bg-blue-500 text-white rounded-md"><a href="index.php">Home</a></button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Continues Orders <a href="continues-order.php"
                    class="fa-regular fa-xmark"></a></button>
            <!-- <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Withdrawal List</button> -->
        </div>
    </div>

    <div class="container mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Continues Orders</h2>
        <?php
            //  foreach ($users as $user) {}
        ?>

        <div class="mb-4 flex justify-end">
            <a href=""><button class="bg-gray-100 border text-black hover:bg-primary/80 px-4 py-2 rounded">Refresh</button></a>
            <a href="add-continues-order.php?user_id=<?php echo $id; ?>"><button class="bg-blue-500 text-white hover:bg-secondary/80 px-4 py-2 rounded ml-2">+ Add</button></a>
        </div> 
        <!-- Table -->
        <div class="overflow-x-auto" id="scrollable-data">
            <div class="p-4 bg-card rounded-lg">
                <table class="min-w-full border-collapse border border-border">
                    <thead>
                        <tr class="bg-muted text-muted-foreground bg-gray-100">
                            <th class="border border-border px-4 py-2">After how many orders to start consecutive orders</th>
                            <th class="border border-border px-4 py-2">Product Name</th>
                            <th class="border border-border px-4 py-2">Product Price</th>
                        </tr>
                    </thead>
                    <tbody>
    
                         <?php
                         $tbl_name = 'luxury_orders';
                         $hotels = get_luxury_order($tbl_name);
         
                         $total_pages = isset($_SESSION['total_pages']) ? $_SESSION['total_pages'] : 1;
                         $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
         
                         if (!empty($hotels)) {
                           foreach ($hotels as $hotel) {
                         ?>
                        
                        <tr>
                            <td class="border border-border px-4 py-2"><?php echo $hotel['order_id']; ?></td>
                            <td class="border border-border px-4 py-2"><?php echo $hotel['hotel_name']; ?></td>
                            <td class="border border-border px-4 py-2"><?php echo $hotel['hotel_price']; ?></td>
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
    <!--  confirm delete popup-->
    <div id="delete-order" style="height: fit-content;">
        <div class="flex items-center sticky top-0 bg-white" id="form-header" style="border: none;padding:0;line-height: 1;">
            <i class="fa-regular fa-question mx-2"></i>
            <h5>Hint</h5>
        </div>
        <p class="py-3">Are you sure to delete this member's consecutive orders?</p>
        <form action="">
            <div class="gap-5 flex justify-end">
                <button id="delete-order-cancel-btn" class="bg-none py-1 px-2 border-gray-300 rounded" style="border: 1px solid gray;">Cancel</button>
                <input type="submit" name="" id="" value="Delete" class="bg-red-600 py-1 px-2 rounded text-white" style="width:fit-content">
            </div>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const deleteOrderBtn = document.getElementById("delete-order-btn");
                const deleteOrderDiv = document.getElementById("delete-order");
                const cancelBtn = document.getElementById("delete-order-cancel-btn");

                // Show the delete order confirmation when the delete button is clicked
                deleteOrderBtn.addEventListener("click", function() {
                    deleteOrderDiv.style.display = "block";
                });

                // Hide the delete order confirmation when the cancel button is clicked
                cancelBtn.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent form submission
                    deleteOrderDiv.style.display = "none";
                });
            });
        </script>
    </div>
</div>

<script src="assets/js/main.js"></script>

</body>

</html>