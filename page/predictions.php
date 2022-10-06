<?php
$market_id = isset($_GET['market_id']) ? (int)test_input($_GET['market_id']) : 0;
$market = $league = $predictions = $rs = [];
$correct_score = $home_goals = $away_goals = $total_goals = $hxa = [];

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
$pre_count = count($predictions);

$sql = "SELECT * FROM results WHERE market_id=$market_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $rs = $row;
  }
}
$rs_count = count($rs);

$sql = "SELECT * FROM leagues WHERE id=" . $market['league_id'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $league = $row;
  }
}

$conn->close();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="?page=markets&league=<?php echo $league['league'] ?>" class="small"><?php echo $league['league'] ?></a>
      <span class="small text-info">&gt;</span>
      <a href="<?php echo $league['url'] ?>">
        <?php echo $market['home']; ?>
        <?php if (count($rs) > 0): ?>
          <span class="text-info"><?php echo $rs['home'] . ' : ' . $rs['away']; ?></span>
        <?php else: ?>
          <span class="text-info">v</span>
        <?php endif; ?>
        <?php echo $market['away']; ?>
      </a>
    </h1>
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
                <tbody>
                  <?php $sn = 1; foreach ($predictions as $prediction):
                    if (isset($home_goals[$prediction['home']])) {
                      $home_goals[$prediction['home']] += 1;
                    } else {
                      $home_goals[$prediction['home']] = 1;
                    }
                    if (isset($away_goals[$prediction['away']])) {
                      $away_goals[$prediction['away']] += 1;
                    } else {
                      $away_goals[$prediction['away']] = 1;
                    }
                    if ( isset( $correct_score[ $prediction['home'] . ':' . $prediction['away'] ] ) ) {
                      $correct_score[ $prediction['home'] . ':' . $prediction['away'] ] += 1;
                    } else {
                      $correct_score[ $prediction['home'] . ':' . $prediction['away'] ] = 1;
                    }
                    if ( isset( $total_goals[ $prediction['home'] + $prediction['away'] ] ) ) {
                      $total_goals[ $prediction['home'] + $prediction['away'] ] += 1;
                    } else {
                      $total_goals[ $prediction['home'] + $prediction['away'] ] = 1;
                    }
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td>
                          <a href="<?php echo $prediction['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $prediction['domain']; ?></a>
                        </td>
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
                            <?php if ($rs_dif > 0 && $pre_dif > 0): $y = 1; ?>
                              <span class="badge badge-success">1</span>
                            <?php elseif ($rs_dif > 0 && $pre_dif === 0): $y = 'X'; ?>
                              <span class="badge badge-danger">X</span>
                            <?php elseif ($rs_dif > 0 && $pre_dif < 0): $y = 2; ?>
                              <span class="badge badge-danger">2</span>

                            <?php elseif ($rs_dif === 0 && $pre_dif === 0): $y = 'X'; ?>
                              <span class="badge badge-success">X</span>
                            <?php elseif ($rs_dif === 0 && $pre_dif < 0): $y = 2; ?>
                              <span class="badge badge-danger">2</span>
                            <?php elseif ($rs_dif === 0 && $pre_dif > 0): $y = 1; ?>
                              <span class="badge badge-danger">1</span>

                            <?php elseif ($rs_dif < 0 && $pre_dif < 0): $y = 2; ?>
                              <span class="badge badge-success">2</span>
                            <?php elseif ($rs_dif < 0 && $pre_dif > 0): $y = 1; ?>
                              <span class="badge badge-danger">1</span>
                            <?php elseif ($rs_dif < 0 && $pre_dif === 0): $y = 'X'; ?>
                              <span class="badge badge-danger">X</span>
                            <?php endif; ?>
                          <?php else: ?>
                            <?php if ($pre_dif > 0): $y = 1; ?>
                              <span class="badge">1</span>
                            <?php elseif ($pre_dif === 0): $y = 'X'; ?>
                              <span class="badge">X</span>
                            <?php elseif ($pre_dif < 0): $y = 2; ?>
                              <span class="badge">2</span>
                            <?php endif; ?>
                          <?php endif; ?>
                          <?php
                          if ( isset( $hxa[$y] ) ) {
                            $hxa[$y] += 1;
                          } else {
                            $hxa[$y] = 1;
                          }
                          ?>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>
                          Correct<br>Score<br>
                          <?php foreach ($correct_score as $score => $count): ?>
                            <span class="small"><?php echo $score; ?></span>
                            <span class="small">(<?php echo number_format(($count * 100) / $pre_count); ?>%)</span> <br>
                          <?php endforeach; ?>
                        </th>
                        <th>
                          Home<br>Goals<br>
                          <?php foreach ($home_goals as $goal => $count): ?>
                            <span class="small"><?php echo $goal; ?></span>
                            <span class="small">(<?php echo number_format(($count * 100) / $pre_count); ?>%)</span> <br>
                          <?php endforeach; ?>
                        </th>
                        <th>
                          Away<br>Goals<br>
                          <?php foreach ($away_goals as $goal => $count): ?>
                            <span class="small"><?php echo $goal; ?></span>
                            <span class="small">(<?php echo number_format(($count * 100) / $pre_count); ?>%)</span> <br>
                          <?php endforeach; ?>
                        </th>
                        <th>
                          Total<br>Goals<br>
                          <?php foreach ($total_goals as $goal => $count): ?>
                            <span class="small"><?php echo $goal; ?></span>
                            <span class="small">(<?php echo number_format(($count * 100) / $pre_count); ?>%)</span> <br>
                          <?php endforeach; ?>
                        </th>
                        <th>
                          1X2<br>
                          <?php foreach ($hxa as $y => $count): ?>
                            <span class="small"><?php echo $y; ?></span>
                            <span class="small">(<?php echo number_format(($count * 100) / $pre_count); ?>%)</span> <br>
                          <?php endforeach; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>