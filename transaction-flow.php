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
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"><a href="transaction-flow.php"> Transaction Flow </a></button>
        </div>
    </div>

    <div class="container mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Transaction Flow</h2>

        <!-- Search Form -->
        <form method="GET" action="transaction-flow.php">
            <div class="flex flex-wrap gap-4 mb-6">
                <input type="text" placeholder="Member account" name="member_account"
                    class="flex-grow border border-gray-300 rounded-lg px-4 py-2">

                <select name="member_type" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Member type</option>
                    <option value="VIP1">VIP1</option>
                    <option value="VIP2">VIP2</option>
                    <option value="VIP3">VIP3</option>
                    <option value="VIP4">VIP4</option>
                </select>
                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Status</option>
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
                <div class="border border-gray-300 rounded-lg flex px-2 items-center">
                    <label for="" class="font-bold">Transaction Date</label>
                    <input type="date" class=" px-4 py-2" name="transaction_time">
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
                        <th class="py-3 px-4 text-left border">Member Type</th>
                        <th class="py-3 px-4 text-left border">Status</th>
                        <th class="py-3 px-4 text-left border">Amount</th>
                        <th class="py-3 px-4 text-left border">Total Transactions</th>
                        <th class="py-3 px-4 text-left border">Freeze balance</th>
                        <th class="py-3 px-4 text-left border">Remark</th>
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
                        $search_fields['t.status'] = $_GET['status'];
                    }
                    if (!empty($_GET['transaction_time'])) {
                        $search_fields['t.transaction_time'] = $_GET['transaction_time'];
                    }

                    // Table name with join query
                    $tbl_name = 'transactions AS t JOIN users AS u ON t.user_id = u.id';

                    // Fetch transaction records
                    $transactions = get_records($tbl_name, $search_fields);

                    if (!empty($transactions)) {
                        foreach ($transactions as $transaction) {
                            $id = $transaction['id'] ?? 'N/A';
                            $username = $transaction['username'] ?? 'Unknown';
                            $member_type = $transaction['member_type'] ?? 'N/A';
                            $status = $transaction['status'] ?? 'N/A';
                            $amount = $transaction['amount'] ?? 'N/A';
                            $total_trans = $transaction['total_transactions'] ?? 'N/A';
                            $freeze_balance = $transaction['freeze_balance'] ?? 'N/A';
                            $remark = $transaction['remark'] ?? 'N/A';
                            $transaction_time = $transaction['transaction_time'] ?? 'N/A';
                    ?>
                            <tr>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($id) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($username) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($member_type) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($status) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($amount) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($total_trans) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($freeze_balance) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($remark) ?></td>
                                <td class="py-3 px-4 border"><?php echo htmlspecialchars($transaction_time) ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7'>No results found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="assets/js/main.js"></script>

</body>
</html>