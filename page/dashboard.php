<?php
$leagues = [];

$sql = "SELECT * FROM markets";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    if ( strtotime($row['market_date']) >= strtotime($date) ) {
      $leagues[$row['league_id']][] = $row;
    }
  }
}
?>

<?php
  foreach ( $leagues as $league_id => $markets ):
  $league = get_data('leagues', $league_id)[0];
  ?>
  <h2 class="h3 mb-0 text-gray-800"><?php echo $league['league']; ?></h2>
  <div class="table-responsive">
    <table class="table">
      <?php foreach ( $markets as $market ): ?>
        <tr>
          <td>
            <?php echo date('H:i', strtotime($market['market_time'])); ?> <br>
            <?php echo date('D j M', strtotime($market['market_date'])); ?>
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
    </table>
  </div>
<?php endforeach; ?>