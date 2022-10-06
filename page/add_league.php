<div class="p-5">
  <div class="text-center">
    <h1 class="h4 text-gray-900 mb-4">Add a League!</h1>
  </div>
  <form class="user" action="_/add.php" method="post">
    <input type="hidden" name="tbl" value="leagues">
    <div class="form-group">
      <input type="text" class="form-control form-control-user" id="league"
        name="league" placeholder="League Name">
    </div>
    <button type="submit" class="btn btn-primary btn-user btn-block">
      Register League
    </button>
    <input type="hidden" name="msg" value="League Added Successfully!">
  </form>
</div>