<?php
$league = isset($_GET['league']) ? $_GET['league'] : '';
$dates = $archives = [];

$sql = "SELECT * FROM leagues WHERE league LIKE '$league'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $league = $row;
  }
}

$sql = "SELECT * FROM markets WHERE league_id=" . $league['id'] . " ORDER BY market_time";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $this_date = strtotime(date('Y-m-d'));
    $this_time = strtotime(date('H:i:s'));
    $market_date = strtotime($row['market_date']);
    $market_time = strtotime($row['market_time']);
    if ($market_date > $this_date || ($market_date === $this_date && $market_time > $this_time)) {
      $date_str = date('D n M', $market_date);
      if (!isset($dates[$date_str])) {
        $dates[$date_str] = [];
      }
      $dates[$date_str][] = $row;
    } else {
      $archive_str = date('D n M', $market_date);
      if (!isset($archives[$archive_str])) {
        $archives[$archive_str] = [];
      }
      $archives[$archive_str][] = $row;
    }
  }
}

$conn->close();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $league['league']; ?></h1>
    <a href="?page=add_market&league_id=<?php echo $league['id']; ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
        class="fas fa-plus fa-sm text-white-50"></i> Add Market</a>
</div>

<?php foreach ($dates as $date => $markets): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo $date; ?></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered data-table" width="100%" cellspacing="0">
                <tbody>
                    <?php foreach ($markets as $market): ?>
                    <tr>
                        <td>
                            <?php echo date('H:i', strtotime($market['market_time'])); ?>
                        </td>
                        <td>
                            <?php echo $market['home'] . '<br>' . $market['away']; ?>
                        </td>
                        <td>
                            <a href="<?php echo $market['url'] ?>" target="_blank">
                                <i class="fas fa-link"></i>
                            </a>
                        </td>
                        <td>
                            <a href="?page=predictions&market_id=<?php echo $market['id']; ?>" class="btn btn-sm btn-outline-primary">Predictions</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach; ?>
<hr>
<details>
    <summary>Archives</summary>
    <?php foreach ($archives as $date => $markets): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo $date; ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered data-table" width="100%" cellspacing="0">
                    <tbody>
                        <?php foreach ($markets as $market): ?>
                        <tr>
                            <td>
                                <?php echo date('H:i', strtotime($market['market_time'])); ?>
                            </td>
                            <td>
                                <?php echo $market['home'] . '<br>' . $market['away']; ?>
                            </td>
                            <td>
                                <a href="<?php echo $market['url'] ?>" target="_blank">
                                    <i class="fas fa-link"></i>
                                </a>
                            </td>
                            <td>
                                <a href="?page=predictions&market_id=<?php echo $market['id']; ?>" class="btn btn-sm btn-outline-primary">Predictions</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</details>