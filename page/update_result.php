<?php
$market_id = (int)test_input($_GET['market_id']);
$market = $rs = [];
$result_id = 0;
$home_goal = $away_goal = $url = '';

$sql = "SELECT * FROM markets WHERE id=$market_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $market = $row;
  }
}

$sql = "SELECT * FROM results WHERE market_id=$market_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $rs = $row;
  }
  $result_id = $rs['id'];
  $home_goal = $rs['home'];
  $away_goal = $rs['away'];
  $url = $rs['url'];
}
?>
<div class="p-5 col-lg-8 mx-auto">
  <div class="text-center">
    <h1 class="h4 text-gray-900 mb-4"><?php echo $market['home']; ?> <span class="text-info">v</span> <?php echo $market['away']; ?></h1>
  </div>
  <form class="user" action="_/add_update.php" method="post">
    <input type="hidden" name="tbl" value="results">
    <input type="hidden" name="id" value="<?php echo $result_id; ?>">
    <input type="hidden" name="market_id" value="<?php echo $market_id; ?>">
    <div class="form-group row">
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="number" min=0 name="home" class="form-control form-control-user" placeholder="Home Goal" value="<?php echo $home_goal; ?>" required>
      </div>
      <div class="col-sm-6">
        <input type="number" min=0 name="away" class="form-control form-control-user" placeholder="Away Goal" value="<?php echo $away_goal; ?>" required>
      </div>
    </div>
    <div class="form-group">
      <input type="url" name="url" class="form-control form-control-user" placeholder="URL" value="<?php echo $url; ?>">
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
      Update Result
    </button>
    <input type="hidden" name="pg" value="predictions&market_id=<?php echo $market_id; ?>">
  </form>
</div>