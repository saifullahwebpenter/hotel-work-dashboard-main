<?php include 'inc/header.php' ?>
<?php include 'inc/sidebar.php' ?>
<?php include 'functions.php'; ?>


<!-- RIGHT_CONTAINER -->
<div class="main-container">
    <?php include 'inc/nav.php' ?>

    <!-- Navigation Tabs -->
    <div class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex items-center space-x-4">
            <button class="px-4 py-2 bg-blue-500 text-white rounded-md"><a href="index.php">Home</a></button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"> <a href="recharge-list.php"> Recharge list </a><a href="index.php"
                    class="fa-regular fa-xmark"></a></button>
            <!-- <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Withdrawal List</button> -->
        </div>
    </div>

    <div class="container mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Recharge List</h2>

        <!-- Search Form -->
        <div class="flex flex-wrap gap-4 mb-6">
            <form method="GET" action="recharge-list.php" class="flex flex-wrap gap-4 mb-6">
                <input type="text" name="member_account" placeholder="Member account" class="flex-grow border border-gray-300 rounded-lg px-4 py-2">
                <select name="member_type" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Member type</option>
                    <option value="Real">Real</option>
                    <option value="Real">Guest</option>
                </select>

                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Status</option>
                    <option value="Order Confirmation">Order Confirmation</option>
                    <option value="Order Failed">Order Failed</option>
                </select>
                <div class="border border-gray-300 rounded-lg flex px-2 items-center">
                    <label for="" class="font-bold">Recharge Date</label>
                    <input type="date" name="recharge_time" class=" px-4 py-2">
                </div>
                <button type="submit" class="bg-blue-500 text-white rounded-lg px-6 py-2 hover:bg-blue-600">Search</button>
            </form>
            <!-- Table -->
            <div class="overflow-x-auto" id="scrollable-data">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 text-left border">ID</th>
                            <th class="py-3 px-4 text-left border">Screenshot</th>
                            <th class="py-3 px-4 text-left border">Member account</th>
                            <th class="py-3 px-4 text-left border">Member type</th>
                            <th class="py-3 px-4 text-left border">Recharge amount</th>
                            <th class="py-3 px-4 text-left border">Status</th>
                            <th class="py-3 px-4 text-left border">Creation Tme</th>
                            <!-- <th class="py-3 px-4 text-left border sticky" id="operate">Operate</th> -->
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
                        if (!empty($_GET['recharge_time'])) {
                            $search_fields['r.recharge_time'] = $_GET['recharge_time'];
                        }

                        // Table name with join query
                        $tbl_name = 'recharges AS r JOIN users AS u ON r.user_id = u.id';



                        $users = get_records($tbl_name, $search_fields);

                        $total_pages = $_SESSION['total_pages'] ?? 1;
                        $page = $_GET['page'] ?? 1;

                        if (!empty($users)) {
                            foreach ($users as $recharge) {
                                // Fetch user data directly from the joined result
                                $id = $recharge['id'] ?? 'N/A';
                                $screenshot = $recharge['screenshot'] ?? 'N/A';

                                $username = $recharge['username'] ?? 'Unknown';
                                $member_type = $recharge['member_type'] ?? 'N/A';
                                $recharge_amount = $recharge['recharge_amount'] ?? 'N/A';
                                $status = $recharge['status'] ?? 'N/A';
                                $recharge_time = $recharge['recharge_time'] ?? 'N/A';
                        ?>
                                <tr>
                                    <td class="py-3 px-4 border"><?php echo htmlspecialchars($id) ?></td>
                                    <td class="py-3 px-4 border"><img src="<?php echo htmlspecialchars($screenshot) ?>"></td>

                                    <td class="py-3 px-4 border"><?php echo htmlspecialchars($username) ?></td>
                                    <td class="py-3 px-4 border">
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full"><?php echo htmlspecialchars($member_type) ?></span>
                                    </td>
                                    <td class="py-3 px-4 border"><?php echo htmlspecialchars($recharge_amount) ?></td>
                                    <td class="py-3 px-4 border"><?php echo htmlspecialchars($status) ?></td>
                                    <td class="py-3 px-4 border"><?php echo htmlspecialchars($recharge_time) ?></td>
                                    <!-- <td class="py-3 px-4 border">Order Confirmation</td> -->
                                    <!-- <td class="py-3 px-4 border sticky" id="operate" style="background-color: #fff;">
                                        <form onsubmit="return false;" style="display:inline;">
                                            <input type="hidden" name="status" value="completed">
                                            <input type="hidden" name="id" value="<?php // echo $recharge['id']; ?>">
                                            <button type="button" class="bg-green-500 text-white rounded-lg px-4 py-1 ml-2" onclick="updateStatus(this)">Success</button>
                                        </form>

                                        <form onsubmit="return false;" style="display:inline;">
                                            <input type="hidden" name="status" value="fail">
                                            <input type="hidden" name="id" value="<?php //echo $recharge['id']; ?>">
                                            <button type="button" class="bg-red-500 text-white rounded-lg px-4 py-1 mt-3" onclick="updateStatus(this)">Fail</button>
                                        </form>
                                    </td> -->
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>0 results</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <?php
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo "<li class='page-item " . ($i == $page ? "active" : "") . "'><a class='page-link' href='member-list.php?page=" . $i . "'>" . $i . "</a></li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/main.js"></script>


</body>

</html>