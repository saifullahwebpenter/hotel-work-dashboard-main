<?php include 'inc/header.php' ?>
<?php include 'inc/sidebar.php' ?>
<?php include 'functions.php' ?>

<?php
// status of user
if (isset($_SESSION['status'])) {
?>
  <div class="alert <?php echo $_SESSION['status_code']; ?>">
    <?php echo $_SESSION['status']; ?>
  </div>
<?php
  unset($_SESSION['status']);
  unset($_SESSION['status_code']);
}

// delete function
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'];

  $delete_query = "DELETE FROM users WHERE id='$id'";
  $delete_query_run = mysqli_query($conn, $delete_query);
  if ($delete_query_run) {

    $_SESSION['status'] = "Data Deleted Successfully";
    $_SESSION['status_code'] = "success";
    header("location: member-list.php");
  } else {
    //echo "Data Updated";
    $_SESSION['status'] = "Data is not Successfully Deleted";
    $_SESSION['status_code'] = "error";
    header("location:member-list.php");
  }
}


// disable button
if (isset($_POST['disable_user'])) {
  $id = $_POST['user_id'];

  // Update the user status to disabled (is_active = 0) and member_status to 'inactive'(
  $disable_query = "UPDATE users SET is_active = 0, member_status = 'inactive' WHERE id = '$id'";
  $disable_query_run = mysqli_query($conn, $disable_query);

  if ($disable_query_run) {
    $_SESSION['status'] = "User Disabled Successfully";
    $_SESSION['status_code'] = "success";
    header("Location: member-list.php");
    exit();
  } else {
    $_SESSION['status'] = "Failed to Disable User";
    $_SESSION['status_code'] = "error";
    header("Location: member-list.php");
    exit();
  }
}

if (isset($_POST['enable_user'])) {
  $id = $_POST['user_id'];

  // Update the user status to enabled (is_active = 1) and member_status to 'active'
  $enable_query = "UPDATE users SET is_active = 1, member_status = 'active' WHERE id = '$id'";
  $enable_query_run = mysqli_query($conn, $enable_query);

  if ($enable_query_run) {
    $_SESSION['status'] = "User Enabled Successfully";
    $_SESSION['status_code'] = "success";
    header("Location: member-list.php");
    exit();
  } else {
    $_SESSION['status'] = "Failed to Enable User";
    $_SESSION['status_code'] = "error";
    header("Location: member-list.php");
    exit();
  }
}
// )end of the enable/disable code

// reset transaction  button code

if (isset($_SESSION['status'])) {
  echo "<div class='alert alert-{$_SESSION['status_code']}'>{$_SESSION['status']}</div>";
  unset($_SESSION['status']); // Clear the message after displaying it
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // SQL query to reset the total_transaction column
  $reset_query = "UPDATE transactions SET total_transactions	 = 0";

  if (mysqli_query($conn, $reset_query)) {
    $_SESSION['status'] = "Transaction count reset successfully.";
    $_SESSION['status_code'] = "success";
  } else {
    $_SESSION['status'] = "Error resetting transaction count: " . mysqli_error($conn);
    $_SESSION['status_code'] = "error";
  }

  // Redirect to the page you want to go after the reset
  header("Location: transaction-flow.php"); // Change this to the appropriate page
  exit();
}
?>

<!-- RIGHT_CONTAINER -->
<div class="main-container">
  <?php include 'inc/nav.php' ?>

  <!-- Navigation Tabs -->
  <div class="bg-white shadow-sm p-4">
    <div class="container mx-auto flex items-center space-x-4">
      <button class="px-4 py-2 bg-blue-500 text-white rounded-md"><a href="index.php">Home</a></button>
      <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"><a href="member-list.php"> Member List </a> <a href="index.php" class="fa-regular fa-xmark"></a></button>
      <!-- <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Withdrawal List</button> -->
    </div>
  </div>

  <div class="container mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-semibold mb-4 ">Member List</h1>

    <!-- Search Filters -->
    <form action="member-list.php" method="GET">
      <div class="grid grid-cols-4 gap-4 mb-6">
        <input type="text" name="member_account" placeholder="Member account" class="border p-2 rounded w-full">
        <input type="text" name="member_phone" placeholder="Member phone number" class="border p-2 rounded w-full">

        <!-- Dropdown Filters -->
        <select name="member_level" class="border p-2 rounded w-full">
          <option value="">Member Level</option>
          <option value="">VIP 1</option>
          <option value="">VIP 2</option>
          <option value="">VIP 3</option>
          <option value="">VIP 4</option>
        </select>

        <select name="member_status" class="border p-2 rounded w-full">
          <option value="">Member status</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>

        <!-- Search Button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
      </div>
    </form>

    <!-- Scrollable Member Table -->
    <div id="scrollable-data">
      <div class="scrollable-table">
        <table class="min-w-full bg-white border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 p-2">ID</th>
              <th class="border border-gray-300 p-2">Member name</th>
              <th class="border border-gray-300 p-2">Member phone number</th>
              <th class="border border-gray-300 p-2">Member Level</th>
              <th class="border border-gray-300 p-2">Balance</th>
              <th class="border border-gray-300 p-2">Superior ID</th>
              <th class="border border-gray-300 p-2">Number of subordinate members</th>
              <th class="border border-gray-300 p-2">Maximum amount of daily statements</th>
              <th class="border border-gray-300 p-2">Today’s order quantity</th>
              <th class="border border-gray-300 p-2">Today's commission</th>
              <th class="border border-gray-300 p-2">Freeze balance</th>
              <th class="border border-gray-300 p-2">Invitation code</th>
              <th class="border border-gray-300 p-2">Member type</th>
              <th class="border border-gray-300 p-2">Member status</th>
              <th class="border border-gray-300 p-2">Creation Time</th>
              <th class="border border-gray-300 p-2 sticky" id="operate">Operate</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $search_fields = [
              'username' => $_GET['member_account'] ?? '',
              'number' => $_GET['member_phone'] ?? '',
              'member_level' => $_GET['member_level'] ?? '',
              'member_status' => $_GET['member_status'] ?? '',
            ];

            $tbl_name = 'users';
            $users = get_records($tbl_name, $search_fields);

            $total_pages = isset($_SESSION['total_pages']) ? $_SESSION['total_pages'] : 1;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            if (!empty($users)) {
              foreach ($users as $user) {


            ?>
                <tr>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['id'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['username'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['number'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['member_level'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['total_balance']; ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['superior_id'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['num_subordinate_members'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['max_daily_statements'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['today_order_quantity'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['today_commission'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['total_commission'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['ref_code'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><button class="border px-1 rounded text-green-600 bg-lime-100 border-green-600"><?php echo $user['member_type'] ?></button></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['member_status'] ?></td>
                  <td class="border border-gray-300 p-2 text-center"><?php echo $user['creation_time'] ?> </td>
                  <?php $_SESSION['total_balance'] = $user['total_balance'];  ?>

                  <!-- buttons -->
                  <td class="border border-gray-300 p-2 text-center sticky space-y-2" id="operate-btns">
                    <button
                      class="bg-gray-200 px-2 py-1 rounded text-sm edit-btn"
                      data-id="<?php echo $user['id']; ?>"
                      data-username="<?php echo htmlspecialchars($user['username']); ?>"
                      data-number="<?php echo htmlspecialchars($user['number']); ?>"
                      data-memberLevel="<?php echo htmlspecialchars($user['member_level']); ?>"
                      data-membertype="<?php echo htmlspecialchars($user['member_type']); ?>"
                      data-memberstatus="<?php echo htmlspecialchars($user['member_status']); ?>">
                      Edit
                    </button>
                    <a href="continues-orders.php?user_id=<?php echo $user['id'];?>" class="py-1"><button class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Continuous orders</button></a>
                    <!-- Button to Open Modal -->
                    <button id="system-increase-decrease-btn" class="bg-blue-500 text-white px-2 py-1 rounded text-sm" data-system-id="<?php echo $user['id']; ?>">System Increase or Deduction</button>
                    <button id="withdrawl-method-btn" class="bg-gray-200 px-2 py-1 rounded text-sm withbtn" data-withdrawal-id="<?php echo $user['id'] ?>">Withdrawal method</button>
                    <!-- withdrawal-btn -->
                    <!-- <a href="fetch_withdrawal_data.php?user_id=" id="withdrawl-method-btn" class="bg-gray-200 px-2 py-1 rounded text-sm">Withdrawal method</a> -->

                    <button id="reset-transaction-count-btn" class="bg-yellow-400 text-white px-2 py-1 rounded text-sm">Reset transaction count</button>
                    <form action="member-list.php" method="post">
                      <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                      <?php if ($user['is_active'] == 1) : ?>
                        <!-- Show Disable button if user is active -->
                        <button type="submit" name="disable_user" class="bg-orange-500 text-white px-2 py-1 rounded text-sm" onclick="return confirmDisable();">Disable</button>
                      <?php else : ?>
                        <!-- Show Enable button if user is inactive -->
                        <button type="submit" name="enable_user" class="bg-green-500 text-white px-2 py-1 rounded text-sm" onclick="return confirmEnable();">Enable</button>
                      <?php endif; ?>
                    </form>

                    <button type="submit" id="confirm-delete-btn" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Delete</button>


                  </td>
                </tr>
            <?php
              }
            } else {
              echo "<tr><td colspan='4'>0 results</td></tr>";
            }
            ?>
            <!-- Add more rows here if needed -->
          </tbody>
        </table>
      </div>

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
  <!-- edit dropdown -->
  <div id="edit-form" class="edit-form" style="display: none;"> <!-- Make sure it starts as hidden -->
    <div class="flex items-center justify-between sticky top-0 bg-white" id="form-header">
      <h5>Edit</h5>
      <i class="fa-sharp fa-solid fa-xmark" id="edit-close-btn"></i>
    </div>
    <form action="update_user.php" method="post" id="editUserForm">
      <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
      <div class="flex items-center mb-3">
        <label for="">ID</label>
        <input type="text" name="user_id" placeholder="123" value="" disabled style="cursor: not-allowed;">
      </div>
      <div class="flex items-center mb-3">
        <label for="">Member Account</label>
        <input type="text" name="member_account" value="" placeholder="Crcry" required>
      </div>
      <div class="flex items-center mb-3">
        <label for="">Member phone number</label>
        <input type="tel" name="member_phone" placeholder="3994756505" required>
      </div>
      <div class="flex items-center mb-3">
        <label for="">Member level</label>
        <select name="member_level">
          <option value="VIP 1">VIP 1</option>
          <option value="VIP 2">VIP 2</option>
          <option value="VIP 3">VIP 3</option>
          <option value="VIP 4">VIP 4</option>
        </select>
      </div>

      <div class="flex items-center mb-3">
        <label for="">Member status</label>
        <select name="member_status">
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
      <div class="mt-10 border-t pt-10 gap-5 flex justify-end">

        <input type="submit" value="Confirm" class="bg-blue py-2 px-3 rounded text-white" style="width:fit-content">
    </form>

  </div>

</div>


<!-- Modal Structure for system-increase-decrease-->
<!-- Modal Structure with Backdrop -->
<div id="modal-backdrop" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999; background: rgba(0, 0, 0, 0.5);">
  <div id="system-increase-decrease" style="display: none; max-width: 600px; max-height: 80vh; overflow-y: auto; background: white; padding: 20px; border-radius: 8px; margin: auto; position: relative; top: 50%; transform: translateY(-50%);">
    <div class="flex items-center justify-between sticky top-0 bg-white" id="form-header">
      <h5>System Increase or Deduction</h5>
      <i class="fa-sharp fa-solid fa-xmark" id="sid-close-btn" style="cursor: pointer;"></i>
    </div>
    <form action="insert_data.php" method="post">
      <div class="flex items-center mb-3">
        <label for="transaction-id">ID</label>
        <input type="text" placeholder="947477479474" id="transaction-id" disabled style="cursor: not-allowed;">
      </div>
      <div class="flex items-center mb-3">
        <label for="systemusername">Member Account</label>
        <input type="text" placeholder="" id="systemusername" disabled style="cursor: not-allowed;">
      </div>
      <div class="flex items-center mb-3">
        <label for="transaction-type">Type</label>
        <select name="transaction_type" id="transaction-type" required>
          <option value="System Increase">System Increase</option>
          <option value="System Deduction">System Deduction</option>
          <!-- <option value="Bonus">Bonus</option> -->
        </select>
      </div>
      <div class="flex items-center mb-3">
        <label for="amount">Amount</label>
        <input type="number" name="amount" id="amount" required style="border: 1px solid gray;">
      </div>
      <div class="mt-10 border-t pt-10 gap-5 flex justify-end">
        <button type="button" id="sid-cancel-btn" class="bg-none py-2 px-3 border-gray-300 rounded" style="border: 1px solid gray;">Cancel</button>
        <input type="submit" value="Confirm" class="bg-blue py-2 px-3 rounded text-white" style="width: fit-content;">
      </div>
    </form>
  </div>
</div>



<!-- withdrawal model -->
<div id="withdrawal-modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background-color: white; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); width: 400px; border-radius: 8px;">
  <div class="flex items-center justify-between sticky top-0 bg-white" id="form-header">
    <h5>Bank Card</h5>
    <i class="fa-sharp fa-solid fa-xmark" id="withdrawl-close-btn" style="cursor: pointer;"></i>
  </div>
  <form action="">
    <div class="flex items-center mb-3 mt-3">
      <label for="withdrawal-id" style="flex: 1; font-weight: bold;">ID:</label>
      <input type="text" id="withdrawal-id" placeholder="947477479474" disabled style="cursor: not-allowed; flex: 2; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div class="flex items-center mb-3">
      <label for="username" style="flex: 1; font-weight: bold;">Member Account:</label>
      <input type="text" id="username" placeholder="Crcry" disabled style="cursor: not-allowed; flex: 2; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div class="flex items-center mb-3">
      <label for="withdrawal-type" style="flex: 1; font-weight: bold;">Type:</label>
      <select id="withdrawal-type" style="flex: 2; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
        <option value="Credit Card">Credit Card</option>
        <option value="Debit Card">Debit Card</option>
        <option value="Jazzcash">Jazzcash</option>
        <option value="Bank Account">Bank Account</option>
      </select>
    </div>
    <div class="flex items-center mb-3">
      <label for="account" style="flex: 1; font-weight: bold;">Account:</label>
      <input type="text" id="account" required style="flex: 2; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div class="flex items-center mb-3">
      <label for="accountant-name" style="flex: 1; font-weight: bold;">Name:</label>
      <input type="text" id="accountant-name" required style="flex: 2; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div class="mt-10 border-t pt-10 gap-5 flex justify-end">
      <input type="submit" value="Confirm" class="bg-blue py-2 px-3 rounded text-white">
    </div>
  </form>
</div>
<div id="modal-backdrop" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;  z-index: 999;"></div>


<!--  confirm delete popup-->
<div id="confirm-delete" style="height: fit-content;">
  <div class="flex items-center sticky top-0 bg-white" id="form-header" style="border: none;padding:0;line-height: 1;">

    <h5>Alert!</h5>
  </div>
  <p class="py-3">Are you sure you want to delete this member?</p>

  <div class="gap-5 flex justify-end">
    <button id="delete-cancel-btn" class="bg-none py-1 px-2 border-gray-300 rounded" style="border: 1px solid gray;">Cancel</button>
    <form action="member-list.php" method="post">
      <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
      <input type="submit" name="" id="" value="Delete" class="bg-red-600 py-1 px-2 rounded text-white" style="width:fit-content">
    </form>
  </div>

</div>

<!-- reset transaction count -->
<div id="reset-transaction-count" style="height: fit-content;">
  <div class="flex items-center sticky top-0 bg-white" id="form-header" style="border: none;padding:0;line-height: 1;">
    <!-- <i class="fa-regular fa-question mx-2"></i> -->
    <h5>Alert!</h5>
  </div>
  <p class="py-3">Are you sure to reset this member's transaction number?</p>

  <div class="gap-5 flex justify-end">
    <button id="reset-transaction-count-cancel-btn" class="bg-none py-1 px-2 border-gray-300 rounded" style="border: 1px solid gray;">Cancel</button>
    <form action="member-list.php" method="post">
      <input type="submit" name="" id="" value="Reset" class="bg-red-600 py-1 px-2 rounded text-white" style="width:fit-content">
    </form>
  </div>

</div>
</div>


<script>
  // reset and delete button
  document.addEventListener("DOMContentLoaded", () => {
    // Select all buttons that should open a modal
    const buttons = document.querySelectorAll("#reset-transaction-count-btn, #confirm-delete-btn");

    // Add click event listeners to each button
    buttons.forEach(button => {
      button.addEventListener("click", () => {
        // Close all modals first
        closeAllModals();

        // Check which button was clicked and open the corresponding modal
        if (button.id === "edit-btn") {
          document.getElementById("edit-form").style.display = "block";
        } else if (button.id === "reset-transaction-count-btn") {
          document.getElementById("reset-transaction-count").style.display = "block";
        } else if (button.id === "confirm-delete-btn") {
          document.getElementById("confirm-delete").style.display = "block";
        }
      });
    });

    // Function to close all modals
    function closeAllModals() {
      document.getElementById("edit-form").style.display = "none";
      document.getElementById("reset-transaction-count").style.display = "none";
      document.getElementById("confirm-delete").style.display = "none";
    }

    // Add event listeners to close buttons in each modal
    const closeButtons = document.querySelectorAll("#edit-close-btn, #sid-close-btn, #delete-cancel-btn, #reset-transaction-count-cancel-btn");
    closeButtons.forEach(closeButton => {
      closeButton.addEventListener("click", closeAllModals);
    });
  });



  // system increase and decrease
  // document.addEventListener("DOMContentLoaded", function() {
  //     const openModalButton = document.getElementById("open-system-modal");
  //     const modal = document.getElementById("system-increase-decrease");
  //     const closeModalButton = document.getElementById("sid-close-btn");
  //     const cancelButton = document.getElementById("sid-cancel-btn");

  //     // Function to open the modal
  //     openModalButton.addEventListener("click", function() {
  //         modal.style.display = "block"; // Show the modal
  //     });

  //     // Function to close the modal
  //     const closeModal = function() {
  //         modal.style.display = "none"; // Hide the modal
  //     };

  //     closeModalButton.addEventListener("click", closeModal);
  //     cancelButton.addEventListener("click", closeModal);
  // });




  // Select all edit buttons

  const editButtons = document.querySelectorAll('.edit-btn');
  const editForm = document.getElementById('edit-form');

  // Add click event listener to each button
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      console.log('Edit button clicked'); // Debugging line

      // Get user data from data attributes
      const userId = this.getAttribute('data-id');
      const username = this.getAttribute('data-username');
      const number = this.getAttribute('data-number');
      const memberLevel = this.getAttribute('data-memberLevel');

      const memberStatus = this.getAttribute('data-memberstatus');

      // Populate modal fields
      editForm.querySelector('input[name="user_id"]').value = userId || '';
      editForm.querySelector('input[name="member_account"]').value = username || '';
      editForm.querySelector('input[name="member_phone"]').value = number || '';
      editForm.querySelector('select[name="member_level"]').value = memberLevel || '';

      editForm.querySelector('select[name="member_status"]').value = memberStatus || '';

      // Show the edit form
      editForm.style.display = 'block'; // Ensure this is correct
    });
  });

  // Handle form submission with async/await
  document.getElementById('editUserForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent the form from submitting the traditional way

    // Collect form data
    const formData = new FormData(this);
    const jsonData = {};

    try {
      // Send the form data using fetch
      const response = await fetch('update_user.php', {
        method: 'POST',
        body: formData,
        header: {
          'Content-Type': 'application/json'
        }
      });

      // Check if the response is ok (status in the range 200-299)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const text = await response.text(); // Read response as text
      console.log('Raw Response:', text); // Log raw response

      const data = JSON.parse(text); // Attempt to parse as JSON
      if (data.success) {
        alert('User updated successfully!');
        document.getElementById('edit-form').style.display = 'none'; // Close modal
      } else {
        alert('Error updating user: ' + data.message);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('User data updated successfully.');
    }
  });
</script>
<!-- disable the user alert -->
<script>
  function confirmDisable() {
    return confirm("Are you sure you want to disable this user?");
  }

  function confirmEnable() {
    return confirm("Are you sure you want to enable this user?");
  }
</script>

<script>
  const withdrawalButtons = document.querySelectorAll('#withdrawl-method-btn'); // Adjust selector to match your button class or ID
  const withdrawalModal = document.getElementById('withdrawal-modal');
  const modalBackdrop = document.getElementById('modal-backdrop');

  // Add click event listener to each withdrawal button
  withdrawalButtons.forEach(button => {
    button.addEventListener('click', async function() {
      const userId = this.getAttribute('data-withdrawal-id'); // Ensure this attribute is set on your button

      // Fetch withdrawal data based on user ID
      try {
        const response = await fetch(`fetch_withdrawal_data.php?user_id=${userId}`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const text = await response.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error('Failed to parse JSON:', e);
          return;
        }

        // Populate modal fields with fetched data
        if (data) {
          withdrawalModal.querySelector("#withdrawal-id").value = data.id || '';
          withdrawalModal.querySelector("#username").value = data.username || '';
          withdrawalModal.querySelector("#withdrawal-type").value = data.withdrawal_method || '';
          withdrawalModal.querySelector("#account").value = data.account_number || '';
          withdrawalModal.querySelector("#accountant-name").value = data.accountant_name || '';

          // Show the modal and backdrop
          withdrawalModal.style.display = 'block';
          modalBackdrop.style.display = 'block';
        } else {
          console.error('No data found for this user.');
        }
      } catch (error) {
        console.error('Error fetching withdrawal data:', error);
        alert('Error fetching withdrawal data: ' + error.message);
      }
    });
  });

  // Close modal when the close button is clicked
  document.getElementById('withdrawl-close-btn').addEventListener('click', function() {
    withdrawalModal.style.display = 'none';
    modalBackdrop.style.display = 'none';
  });
</script>

<script>
  // document.addEventListener("DOMContentLoaded", function() {
  //   const openSystemModalButtons = document.querySelectorAll("#system-increase-decrease-btn");
  //   const systemModal = document.getElementById("system-increase-decrease");
  //   const closeSystemModalButton = document.getElementById("sid-close-btn");
  //   const cancelButton = document.getElementById("sid-cancel-btn");
  //   const modalBackdrop = document.getElementById('modal-backdrop');

  //   // Open the modal and fetch data based on user ID
  //   openSystemModalButtons.forEach(button => {
  //     button.addEventListener("click", async function() {
  //       const userId = this.getAttribute('data-system-id');

  //       try {
  //         const response = await fetch(`fetch_withdrawal_data.php?user_id=${userId}`);

  //         if (!response.ok) {
  //           throw new Error(`HTTP error! status: ${response.status}`);
  //         }

  //         // Verify JSON response
  //         const contentType = response.headers.get("content-type");
  //         if (!contentType || !contentType.includes("application/json")) {
  //           throw new Error("The server response is not in JSON format.");
  //         }

  //         const data = await response.json();

  //         if (data.error) {
  //           console.error(data.error);
  //           alert('Error: ' + data.error);
  //           return;
  //         }

  //         // Populate modal fields (example, adjust based on your data structure)
  //         document.getElementById('transaction-id').value = data.id || ''; // Adjust based on your data
  //         document.getElementById('systemusername').value = data.username || ''; // Adjust based on your data

  //         // Show the modal and backdrop
  //         systemModal.style.display = "block";
  //         modalBackdrop.style.display = "block";
  //       } catch (error) {
  //         console.error('Error fetching user data:', error);
  //         alert('Error fetching user data: ' + error.message);
  //       }
  //     });
  //   });

  //   // Close modal functions
  //   closeSystemModalButton.addEventListener("click", closeModal);
  //   cancelButton.addEventListener("click", closeModal);
  //   modalBackdrop.addEventListener('click', closeModal);

  //   function closeModal() {
  //     systemModal.style.display = "none";
  //     modalBackdrop.style.display = "none";
  //   }
  // });

  document.addEventListener("DOMContentLoaded", function() {
    const openSystemModalButtons = document.querySelectorAll("#system-increase-decrease-btn");
    const systemModal = document.getElementById("system-increase-decrease");
    const closeSystemModalButton = document.getElementById("sid-close-btn");
    const cancelButton = document.getElementById("sid-cancel-btn");
    const modalBackdrop = document.getElementById('modal-backdrop');
    const amountInput = document.getElementById('amount');
    const typeInput = document.getElementById('transaction-type');
    let userId = null; // Define userId here for broader scope

    // Open the modal and fetch data based on user ID
    openSystemModalButtons.forEach(button => {
      button.addEventListener("click", async function() {
        userId = this.getAttribute('data-system-id'); // Assign userId correctly

        try {
          const response = await fetch(`fetch_withdrawal_data.php?user_id=${userId}`);
          if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

          const data = await response.json();
          if (data.error) {
            alert('Error: ' + data.error);
            return;
          }

          // Populate modal fields
          document.getElementById('transaction-id').value = data.id || '';
          document.getElementById('systemusername').value = data.username || '';
          document.getElementById('amount').value = data.total_balance || '';

          // Show the modal and backdrop
          systemModal.style.display = "block";
          modalBackdrop.style.display = "block";
        } catch (error) {
          alert('Error fetching user data: ' + error.message);
        }
      });
    });

    // Close modal functions
    closeSystemModalButton.addEventListener("click", closeModal);
    cancelButton.addEventListener("click", closeModal);
    modalBackdrop.addEventListener("click", closeModal);

    // Prevent closing the modal when clicking inside it
    systemModal.addEventListener('click', function(event) {
      event.stopPropagation();
    });

    // Close modal function
    function closeModal() {
      systemModal.style.display = "none";
      modalBackdrop.style.display = "none";
    }

    // Form submission handler
    document.querySelector("form").addEventListener("submit", async function(event) {
      event.preventDefault(); // Prevent form reload

      const amount = amountInput.value;
      const type = typeInput.value;

      console.log("User ID before submit:", userId); // Log user ID for confirmation

      try {
        const response = await fetch('insert_data.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            user_id: userId,
            amount,
            transaction_type: type
          })
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const result = await response.json();
        if (result.success) {
          alert('Data inserted successfully');
          closeModal();
        } else {
          alert('Error inserting data: ' + result.error);
        }
      } catch (error) {
        alert('Error inserting data: ' + error.message);
      }
    });
  });
</script>
<script src="assets/js/main.js"></script>
</body>

</html>