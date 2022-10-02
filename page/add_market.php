<?php
$league_id = (int)test_input($_GET['league_id']);
?>
<div class="p-5 col-lg-8 mx-auto">
  <div class="text-center">
    <h1 class="h4 text-gray-900 mb-4">Add a Market!</h1>
  </div>
  <form class="user" action="_/add.php" method="post">
    <input type="hidden" name="tbl" value="markets">
    <input type="hidden" name="league_id" value="<?php echo $league_id; ?>">
    <div class="form-group row">
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="text" name="home" class="form-control form-control-user" placeholder="Home" required>
      </div>
      <div class="col-sm-6">
        <input type="text" name="away" class="form-control form-control-user" placeholder="Away" required>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-6 mb-3 mb-sm-0">
        <input type="date" name="market_date" class="form-control form-control-user" required>
      </div>
      <div class="col-sm-6">
        <input type="time" name="market_time" class="form-control form-control-user" required>
      </div>
    </div>
    <div class="form-group">
      <input type="url" name="url" class="form-control form-control-user" placeholder="URL">
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
      Register Market
    </button>
    <!-- <input type="hidden" name="pg" value="http://localhost/better/?page=markets&league=England%20Premier%20League"> -->
  </form>
</div>