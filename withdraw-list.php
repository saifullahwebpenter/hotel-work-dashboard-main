<?php include 'inc/header.php' ?>
<?php include 'inc/sidebar.php' ?>
<?php include 'functions.php' ?>



<!-- RIGHT_CONTAINER -->
<div class="main-container">
    <?php include 'inc/nav.php' ?>

    <!-- Navigation Tabs -->
    <div class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex items-center space-x-4">
            <button class="px-4 py-2 bg-blue-500 text-white rounded-md"><a href="index.php">Home</a></button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"><a href="withdraw-list.php"> Withdrawal List </a><a href="index.php"
                    class="fa-regular fa-xmark"></a></button>
        </div>
    </div>

    <div class="container mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Withdrawal List</h2>

        <form method="get" action="withdraw-list.php" class="flex flex-wrap gap-4 mb-6">
            <div class="flex flex-wrap gap-4 mb-6">
                <input type="text" placeholder="Member account" name="member_account"
                    class="flex-grow border border-gray-300 rounded-lg px-4 py-2">
                <select class="border border-gray-300 rounded-lg px-4 py-2" name="member_type">
                    <option value="">Select Member type</option>
                    <option value="VIP1">VIP1</option>
                    <option value="VIP2">VIP2</option>
                    <option value="VIP3">VIP3</option>
                    <option value="VIP4">VIP4</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-4 py-2" name="status">
                    <option value="">Select Status</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                    <option value="Default">Default</option>
                </select>
                <div class="border border-gray-300 rounded-lg flex px-2 items-center">
                    <label for="" class="font-bold">Withdrawal Date</label>
                    <input type="date" class="px-4 py-2" name="withdrawal_time">
                </div>

                <button type="submit" class="bg-blue-500 text-white rounded-lg px-6 py-2 hover:bg-blue-600">Search</button>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto" id="scrollable-data">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 text-left border">ID</th>
                        <th class="py-3 px-4 text-left border">Member account</th>
                        <th class="py-3 px-4 text-left border">Member phone number</th>
                        <th class="py-3 px-4 text-left border">Member type</th>
                        <th class="py-3 px-4 text-left border">Withdrawal amount</th>
                        <th class="py-3 px-4 text-left border">Status</th>
                        <th class="py-3 px-4 text-left border">Creation time</th>
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
                        $search_fields['w.withdrawal_status'] = $_GET['status'];
                    }
                    if (!empty($_GET['withdrawal_time'])) {
                        // If you're using datetime, ensure that you are comparing correctly
                        $search_fields['w.withdrawal_time'] = $_GET['withdrawal_time']; // This assumes you're using the DATE() function in your SQL query.
                    }

                    // Table name with join query
                    $tbl_name = 'withdrawals AS w JOIN users AS u ON w.user_id = u.id';

                    $users = get_records($tbl_name, $search_fields); 

                    $total_pages = $_SESSION['total_pages'] ?? 1;
                    $page = $_GET['page'] ?? 1;

                    if (!empty($users)) {
                        foreach ($users as $withdrawal) {
                            $username = $withdrawal['username'] ?? 'Unknown';
                            $user_number = $withdrawal['number'] ?? 'N/A';
                            $member_type = $withdrawal['member_type'] ?? 'N/A';
                            $withdrawal_time = $withdrawal['withdrawal_time'] ?? 'N/A';

                    ?>
                            <tr>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($withdrawal['id']); ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($username); ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($user_number); ?></td>
                                <td class="py-3 px-4 border">
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full"><?php echo htmlspecialchars($member_type); ?></span>
                                </td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($withdrawal['withdrawal_amount']); ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($withdrawal['withdrawal_status']); ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($withdrawal_time); ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No results found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <nav>
                <ul class="pagination">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item " . ($i == $page ? "active" : "") . "'><a class='page-link' href='withdraw-list.php?page=" . $i . "'>" . $i . "</a></li>";
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