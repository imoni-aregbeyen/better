<?php
$market_id = (int)test_input($_GET['market_id']);
$market = [];

$sql = "SELECT * FROM markets WHERE id=$market_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $market = $row;
  }
}
?>
<div class="p-5 col-lg-8 mx-auto">
  <div class="text-center">
    <h1 class="h4 text-gray-900 mb-4"><?php echo $market['home']; ?> <span class="text-info">v</span> <?php echo $market['away']; ?></h1>
  </div>
  <form class="user" action="_/add.php" method="post">
    <input type="hidden" name="tbl" value="predictions">
    <input type="hidden" name="market_id" value="<?php echo $market_id; ?>">
    <div class="form-group row">
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="number" min=0 name="home" class="form-control form-control-user" placeholder="Home Goal" required>
      </div>
      <div class="col-sm-6">
        <input type="number" min=0 name="away" class="form-control form-control-user" placeholder="Away Goal" required>
      </div>
    </div>
    <div class="form-group">
      <input type="text" name="domain" class="form-control form-control-user" placeholder="Who's predicting?" required>
    </div>
    <div class="form-group">
      <input type="url" name="url" class="form-control form-control-user" placeholder="URL">
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
      Add Prediction
    </button>
    <input type="hidden" name="pg" value="predictions&market_id=<?php echo $market_id; ?>">
  </form>
</div>