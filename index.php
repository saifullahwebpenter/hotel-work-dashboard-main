<?php include 'inc/header.php' ?>
<?php include 'inc/authentication.php' ?>
<?php include 'inc/sidebar.php' ?>

 
<!-- RIGHT_CONTAINER -->
  <div class="main-container">
  
  <?php include 'inc/nav.php' ?>

        
            <!-- Navigation Tabs -->
  <div class="bg-white shadow-sm p-4">
    <div class="container mx-auto flex items-center space-x-4">
      <button class="px-4 py-2 bg-blue-500 text-white rounded-md">Home</button>
    </div>
  </div>

  <!-- Summary Cards Section -->
  <div class=" mx-auto mt-8 ml-4 mr-4 grid grid-cols-2 gap-6">
    <!-- Card 1: Total Recharge Amount -->
    <div class="bg-white p-6 shadow rounded-lg text-center">
      <p class="text-xl text-gray-600">¥</p>
      <p class="text-4xl font-bold">0.00</p>
      <p class="text-gray-500">Total recharge amount</p>
    </div>

    <!-- Card 2: Total Withdrawal Amount -->
    <div class="bg-white p-6 shadow rounded-lg text-center">
      <p class="text-xl text-gray-600">¥</p>
      <p class="text-4xl font-bold">603,602.18</p>
      <p class="text-gray-500">Total withdrawal amount</p>
    </div>
  </div>

  <!-- Trend Chart Section -->
  <div class="mx-auto mt-8 ml-4 mr-4">
    <div class="bg-white p-6 shadow rounded-lg">
      <p class="text-gray-600 text-lg mb-4">Recharge amount change trend</p>
      <!-- Placeholder for Chart -->
      <div class="w-full h-64 bg-gray-50 border-2 border-dashed flex items-center justify-center">
        <p class="text-gray-500">📊 Daily recharge amount</p>
      </div>
    </div>
  </div>
  <div id="chart-container">
    <h2>Withdrawal amount change trend</h2>
    <canvas id="withdrawalChart"></canvas>
</div>

<script>
    const ctx = document.getElementById('withdrawalChart').getContext('2d');

    const withdrawalData = {
        labels: [
            '2024-08-21', '2024-08-27', '2024-09-01', '2024-09-08', '2024-09-14', '2024-09-20',
            '2024-09-27', '2024-10-03', '2024-10-08', '2024-10-14', '2024-10-20'
        ],
        datasets: [{
            label: 'Daily withdrawal amount',
            data: [5000, 32000, 8000, 15000, 7000, 25000, 10000, 18000, 12000, 16000, 6000],
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderWidth: 2,
            fill: true,
            pointRadius: 5,
            pointBackgroundColor: 'white',
            pointBorderColor: 'rgba(54, 162, 235, 1)',
            tension: 0.3
        }]
    };

    const config = {
        type: 'line',
        data: withdrawalData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 40000
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    };

    new Chart(ctx, config);

</script>
     </div>
      <script src="assets/js/main.js"></script>
</body>
</html>