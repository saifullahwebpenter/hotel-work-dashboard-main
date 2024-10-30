<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Admin</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/pagination.css">

     <!-- link with fontawesome -->
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <link
     rel="stylesheet"
     data-purpose="Layout StyleSheet"
     title="Web Awesome"
     href="/css/app-wa-54e7be3a62ca9b7580d7f8c669f59e74.css?vsn=d"
   />

   <link
     rel="stylesheet"
     href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css"
   />

   <link
     rel="stylesheet"
     href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-duotone-solid.css"
   />

   <link
     rel="stylesheet"
     href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-thin.css"
   />

   <link
     rel="stylesheet"
     href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css"
   />

   <link
     rel="stylesheet"
     href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-regular.css"
   />

   <link
     rel="stylesheet"
     href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-light.css"
   />
   <!-- google fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com" />
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
   <link
     href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap"
     rel="stylesheet"
   />
</head>
<body>

<?php session_start(); ?>

<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "hotel-work";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>