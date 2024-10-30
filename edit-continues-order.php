<?php include 'inc/header.php' ?>
<?php include 'inc/authentication.php' ?>

<div id="continues-orders-main-container" class="p-5">
    <!-- continues container header -->
    <div class="flex items-center justify-between">
      <h5 class="text-xl">Edit</h5>
      <i><a href="continues-orders.html" class="fa-regular fa-xmark py-3 px-1"></a></i>
    </div>
    <!-- continues-orders-container -->
    <div id="continues-orders-container" class="flex pt-5 ">
      <div id="continues-orders-container-left">
        <div class="container w-50 p-6 bg-white dark:bg-zinc-800 dark:shadow-lg" style="border-radius: 15px;">
          <div class="mb-4">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Balance</label>
            <input type="text" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-600 rounded-md p-2"
              value="-27,335.11" readonly disabled style="cursor: not-allowed;"/>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">* After how many orders to start
              consecutive orders</label>
            <input type="text" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-600 rounded-md p-2" />
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
                <tr class="">
                  <td class="border border-zinc-300 dark:border-zinc-600 p-2">297</td>
                  <td class="border border-zinc-300 dark:border-zinc-600 p-2">Lotte Hotel Moscow</td>
                  <td class="border border-zinc-300 dark:border-zinc-600 p-2">185,000.00</td>
                  <td class="border border-zinc-300 dark:border-zinc-600 p-2">
                    <button
                      class="bg-red-500 hover:bg-red-600 text-white dark:text-white rounded px-4 py-1">Remove</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="continues-orders-container-right">
        <div class="p-6 bg-background">
          <h2 class="text-xl font-semibold mb-4">Product List</h2>
          <div class="mb-4 flex items-center">
            <input type="text" placeholder="Product name" class="border border-border rounded-md p-2 mr-2 w-full" />
            <div class="flex items-center">
              <input type="number" placeholder="Minimum am" class="border border-border rounded-md p-2 mr-2" />
              <input type="number" placeholder="Maximum am" class="border border-border rounded-md p-2" />
            </div>
            <button class="bg-blue-500 text-white hover:bg-primary/80 p-2 rounded-md ml-2">Search</button>
          </div>
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
                <tr>
                  <td class="border border-border p-2">298</td>
                  <td class="border border-border p-2">Отель The Ritz-Carlton, Moscow - Москва</td>
                  <td class="border border-border p-2">3,450,000.00</td>
                  <td class="border border-border p-2"><button
                      class="bg-gray-100 border text-black hover:bg-gray-200 p-1 rounded">Select</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <form action="">
      <div class="flex items-center justify-end pt-3 gap-5">
        <a href="continues-orders.html"><button class="p-2 bg-gray-300 rounded text-black">Cancel</button></a>
        <input type="submit" value="Confirm" class="p-2 bg-blue-500 rounded text-white">
      </div>
    </form>
  </div>
  <script src="assets/js/main.js"></script>

</body>

</html>