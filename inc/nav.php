<div class="search-bar flex">
  <div class="search-and-zoom flex">
    <div class="search">
      <button class="clear-btn"><a href="index.php"><i class="far fa-times"></i></a></button>
    </div>
    <div class="zoom">
      <button><i class="fa-sharp fa-regular fa-expand"></i></button>
    </div>
  </div>
  <div class="notfication-message-profile">
    <button class="relative">
      <!-- <i class="fa-light fa-bell"></i> -->
      <a href="withdraw-list.php"><span class="text-sm font-semibold">Add Agent</span></a>
    </button>
    <button class="relative">
      <!-- <i class="fa-light fa-bell"></i> -->
      <a href="withdraw-list.php"><span class="text-sm font-semibold">Withdraw</span></a>
      <span class="p-1 rounded-full bg-red-700 text-xs text-white font-medium absolute top-0.5 mx-1">34</span>
    </button>
    <button class="relative">
      <!-- <i class="fa-light fa-message"></i> -->
      <a href="recharge-list.php"><span class="text-sm font-semibold">Deposit</span></a>
      <span class="p-1 rounded-full bg-red-700 text-xs text-white font-medium absolute top-0.5 mx-1">07</span>
    </button>
    <div class="profile flex">
      <img src="<?php echo $_SESSION["profile_photo"]; ?>" alt="profile pic" title="profile" />
      <h4><?php echo $_SESSION["name"]; ?></h4>
      <button class="profile-btn"><i class="fa-sharp fa-regular fa-chevron-down text-sm"></i></button>
      <div class="profile-dropdown" style="display: none;">
        <a href="./change-password.php">Change Password</a>
        <form action="logout.php">
          <a><input type="submit" value="Logout"></a>
        </form>
      </div>
    </div>
  </div>
</div>