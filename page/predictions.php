<?php
$market_id = isset($_GET['market_id']) ? (int)test_input($_GET['market_id']) : 0;
$market = $predictions = $rs = [];

$sql = "SELECT * FROM markets WHERE id=$market_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $market = $row;
  }
}
$sql = "SELECT * FROM predictions WHERE market_id=$market_id ORDER BY domain";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $predictions[] = $row;
  }
}
$sql = "SELECT * FROM results WHERE market_id=$market_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $rs = $row;
  }
}
$rs_count = count($rs);

$conn->close();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <?php echo $market['home']; ?>
      <?php if (count($rs) > 0): ?>
        <span class="text-info"><?php echo $rs['home'] . ' : ' . $rs['away']; ?></span>
      <?php else: ?>
        <span class="text-info">v</span>
      <?php endif; ?>
      <?php echo $market['away']; ?></h1>
    <div class="">
      <a href="?page=add_prediction&market_id=<?php echo $market['id']; ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
          class="fas fa-plus fa-sm text-white-50"></i> Add Prediction</a>
      <a href="?page=update_result&market_id=<?php echo $market['id']; ?>" class="d-none d-sm-inline-block btn btn-sm btn-outline-primary shadow-sm">Update Result</a>
    </div>
  </div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Predictions</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>
                          Correct<br>Score
                        </th>
                        <th>
                          Home<br>Goals
                        </th>
                        <th>
                          Away<br>Goals
                        </th>
                        <th>
                          Total<br>Goals
                        </th>
                        <th>
                          1X2
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>
                          Correct<br>Score
                        </th>
                        <th>
                          Home<br>Goals
                        </th>
                        <th>
                          Away<br>Goals
                        </th>
                        <th>
                          Total<br>Goals
                        </th>
                        <th>
                          1X2
                        </th>
                    </tr>
                </tfoot>
                <tbody>
                  <?php $sn = 1; foreach ($predictions as $prediction): ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo $prediction['domain']; ?></td>
                        <td>
                          <?php if ($rs_count > 0): ?>
                            <?php if ($prediction['home'] === $rs['home'] && $prediction['away'] === $rs['away']): ?>
                              <span class="badge badge-success"><?php echo $prediction['home']; ?>:<?php echo $prediction['away']; ?></span>
                            <?php else: ?>
                              <span class="badge badge-danger"><?php echo $prediction['home']; ?>:<?php echo $prediction['away']; ?></span>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="badge"><?php echo $prediction['home']; ?>:<?php echo $prediction['away']; ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($rs_count > 0): ?>
                            <?php if ($prediction['home'] === $rs['home']): ?>
                              <span class="badge badge-success"><?php echo $prediction['home']; ?></span>
                            <?php else: ?>
                              <span class="badge badge-danger"><?php echo $prediction['home']; ?></span>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="badge"><?php echo $prediction['home']; ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($rs_count > 0): ?>
                            <?php if ($prediction['away'] === $rs['away']): ?>
                              <span class="badge badge-success"><?php echo $prediction['away']; ?></span>
                            <?php else: ?>
                              <span class="badge badge-danger"><?php echo $prediction['away']; ?></span>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="badge"><?php echo $prediction['away']; ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php
                          $pre_ttl = $prediction['home'] + $prediction['away'];
                          ?>
                          <?php if ($rs_count > 0): $rs_ttl = $rs['home'] + $rs['away']; ?>
                            <?php if ($rs_ttl === $pre_ttl): ?>
                              <span class="badge badge-success"><?php echo $pre_ttl; ?></span>
                            <?php else: ?>
                              <span class="badge badge-danger"><?php echo $pre_ttl; ?></span>
                            <?php endif; ?>
                          <?php else: ?>
                            <span class="badge"><?php echo $pre_ttl; ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php
                          $pre_dif = $prediction['home'] - $prediction['away'];
                          ?>
                          <?php if ($rs_count > 0): $rs_dif = $rs['home'] - $rs['away']; ?>
                            <?php if ($rs_dif > 0 && $pre_dif > 0): ?>
                              <span class="badge badge-success">1</span>
                            <?php elseif ($rs_dif > 0 && $pre_dif === 0): ?>
                              <span class="badge badge-danger">X</span>
                            <?php elseif ($rs_dif > 0 && $pre_dif < 0): ?>
                              <span class="badge badge-danger">2</span>

                            <?php elseif ($rs_dif === 0 && $pre_dif === 0): ?>
                              <span class="badge badge-success">X</span>
                            <?php elseif ($rs_dif === 0 && $pre_dif < 0): ?>
                              <span class="badge badge-danger">2</span>
                            <?php elseif ($rs_dif === 0 && $pre_dif > 0): ?>
                              <span class="badge badge-danger">1</span>

                            <?php elseif ($rs_dif < 0 && $pre_dif < 0): ?>
                              <span class="badge badge-success">2</span>
                            <?php elseif ($rs_dif < 0 && $pre_dif > 0): ?>
                              <span class="badge badge-danger">1</span>
                            <?php elseif ($rs_dif < 0 && $pre_dif === 0): ?>
                              <span class="badge badge-danger">X</span>
                            <?php endif; ?>
                          <?php else: ?>
                            <?php if ($pre_dif > 0): ?>
                              <span class="badge">1</span>
                            <?php elseif ($pre_dif === 0): ?>
                              <span class="badge">X</span>
                            <?php elseif ($pre_dif < 0): ?>
                              <span class="badge">2</span>
                            <?php endif; ?>
                          <?php endif; ?>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
