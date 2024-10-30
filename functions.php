<?php
function get_records($tbl_name, $search_fields = [], $num_per_page = 10)
{
  global $conn;

  // Get the current page number from the URL
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $start_from = ($page - 1) * $num_per_page;

  // Initialize an array to hold search conditions
  $search_conditions = [];
  $search_values = [];

  // Build search conditions based on provided search fields
  foreach ($search_fields as $field => $value) {
    if (!empty($value)) {
      // Sanitize input and add to conditions
      $sanitized_value = mysqli_real_escape_string($conn, $value);
      $search_conditions[] = "$field LIKE ?";
      $search_values[] = "%" . $sanitized_value . "%"; // For partial matching
    }
  }

  // Prepare the WHERE clause
  $where_clause = !empty($search_conditions) ? 'WHERE ' . implode(' AND ', $search_conditions) : '';

  // Count total records matching the search criteria
  $count_query = "SELECT COUNT(*) FROM $tbl_name $where_clause";
  $count_stmt = mysqli_prepare($conn, $count_query);

  // Bind parameters for count query
  if (!empty($search_values)) {
    $types = str_repeat('s', count($search_values)); // Assuming all fields are strings
    mysqli_stmt_bind_param($count_stmt, $types, ...$search_values);
  }

  mysqli_stmt_execute($count_stmt);
  mysqli_stmt_bind_result($count_stmt, $total_records);
  mysqli_stmt_fetch($count_stmt);
  mysqli_stmt_close($count_stmt);

  // Calculate total pages
  $_SESSION['total_pages'] = ceil($total_records / $num_per_page);

  // Prepare the SELECT query
  $select_query = "SELECT * FROM $tbl_name $where_clause LIMIT ?, ?";
  $select_stmt = mysqli_prepare($conn, $select_query);

  // Add start and limit values to the parameters
  $params = array_merge($search_values, [$start_from, $num_per_page]);

  // Build the type string dynamically
  $types = str_repeat('s', count($search_values)) . 'ii'; // 's' for string, 'i' for integer

  // Use mysqli_stmt_bind_param with unpacking
  mysqli_stmt_bind_param($select_stmt, $types, ...$params);

  mysqli_stmt_execute($select_stmt);
  $result = mysqli_stmt_get_result($select_stmt);

  // Fetch results into an array
  $records = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_stmt_close($select_stmt);

  return $records;
}



function fetch_edit_record($table, $id)
{
  // Example code for fetching a record by ID from the database
  global $db; // Assuming $db is your database connection
  $query = "SELECT * FROM $table WHERE id = ?";
  $stmt = $db->prepare($query);
  $stmt->execute([$id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}


function edit_record($tbl_name, $fields, $id_column)
{
  global $conn;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST[$id_column];

    $set_clause = [];
    $params = [];
    $param_types = '';

    foreach ($fields as $field => $value) {
      $set_clause[] = "$field = ?";
      $params[] = $_POST[$value];
      $param_types .= 's';
    }

    $params[] = $id;
    $param_types .= 'i';

    $set_clause_str = implode(', ', $set_clause);
    $query = "UPDATE $tbl_name SET $set_clause_str WHERE $id_column = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
      die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param($param_types, ...$params);

    $result = $stmt->execute();

    if ($result) {
      header("Location: {$_POST['redirect_url']}");
      exit;
    } else {
      $error = "Update failed: (" . $stmt->errno . ") " . $stmt->error;
      echo $error;
    }
  }
}


function delete_record($tbl_name)
{
  global $conn;
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sql = "DELETE FROM $tbl_name WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}



function search_records($table_name, $search_fields)
{
  global $conn; // Assuming $conn is your database connection

  // Build the SQL query dynamically
  $query = "SELECT * FROM " . $table_name . " WHERE 1=1";
  $params = [];
  $types = ""; // To bind parameter types

  // Check for search fields and append to the query
  if (isset($search_fields['hotel_name'])) {
    $query .= " AND hotel_name LIKE ?";
    $params[] = "%" . $search_fields['hotel_name'] . "%";
    $types .= "s"; // 's' for string
  }
  if (isset($search_fields['price >='])) {
    $query .= " AND price >= ?";
    $params[] = $search_fields['price >='];
    $types .= "d"; // 'd' for double (floating point)
  }
  if (isset($search_fields['price <='])) {
    $query .= " AND price <= ?";
    $params[] = $search_fields['price <='];
    $types .= "d"; // 'd' for double (floating point)
  }

  // Prepare the statement
  $stmt = $conn->prepare($query);
  if ($stmt) {
    // Bind the parameters
    if ($params) {
      $stmt->bind_param($types, ...$params);
    }

    // Execute the statement
    $stmt->execute();

    // Fetch results
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  } else {
    throw new Exception("SQL Error: " . $conn->error);
  }
}

function get_luxury_order($tbl_name, $search_fields = [], $num_per_page = 10)
{
  global $conn;

  // Get the current page number from the URL
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $start_from = ($page - 1) * $num_per_page;

  // Initialize an array to hold search conditions
  $search_conditions = [];
  $search_values = [];

  // Build search conditions based on provided search fields
  foreach ($search_fields as $field => $value) {
    if (!empty($value)) {
      // Sanitize input and add to conditions
      $sanitized_value = mysqli_real_escape_string($conn, $value);
      $search_conditions[] = "$field LIKE ?";
      $search_values[] = "%" . $sanitized_value . "%"; // For partial matching
    }
  }

  // Prepare the WHERE clause
  $where_clause = !empty($search_conditions) ? 'WHERE ' . implode(' AND ', $search_conditions) : '';

  // Count total records matching the search criteria
  $count_query = "
        SELECT COUNT(*) AS total_count
        FROM $tbl_name AS l
        JOIN hotel_rooms AS h ON l.product_id = h.id 
        $where_clause
    ";
  $count_stmt = mysqli_prepare($conn, $count_query);

  // Bind parameters for count query
  if (!empty($search_values)) {
    $types = str_repeat('s', count($search_values)); // Assuming all fields are strings
    mysqli_stmt_bind_param($count_stmt, $types, ...$search_values);
  }

  mysqli_stmt_execute($count_stmt);
  mysqli_stmt_bind_result($count_stmt, $total_records);
  mysqli_stmt_fetch($count_stmt);
  mysqli_stmt_close($count_stmt);

  // Calculate total pages
  $_SESSION['total_pages'] = ceil($total_records / $num_per_page);

  // Prepare the SELECT query
  $select_query = "
        SELECT l.id AS luxury_order_id, 
        l.order_id AS order_id, 
               h.id AS hotel_id, 
               h.hotel_name AS hotel_name, 
               h.price AS hotel_price 
        FROM $tbl_name AS l
        JOIN hotel_rooms AS h ON l.product_id = h.id 
        $where_clause 
        LIMIT ?, ?
    ";
  $select_stmt = mysqli_prepare($conn, $select_query);

  // Add start and limit values to the parameters
  $params = array_merge($search_values, [$start_from, $num_per_page]);

  // Build the type string dynamically
  $types = str_repeat('s', count($search_values)) . 'ii'; // 's' for string, 'i' for integer

  // Use mysqli_stmt_bind_param with unpacking
  mysqli_stmt_bind_param($select_stmt, $types, ...$params);

  mysqli_stmt_execute($select_stmt);
  $result = mysqli_stmt_get_result($select_stmt);

  // Fetch results into an array
  $records = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_stmt_close($select_stmt);

  return $records;
}
