<main class="c-main">
  <div class="container-fluid">
    <div class="fade-in">
      <!-- /.row-->
      <div class="card">
        <div class="card-body">
          <div class="text-center">
            <h1> WELCOME TO STRATEGIC NEGOTIATION APPS</h1>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          History Game Play
        </div>
        <div class="card-body">
          <table class="table table-responsive-sm table-striped" id="dashboardTable">
            <thead>
              <tr>
                <th>No</th>
                <th>Player Name</th>
                <th>Point</th>
                <th></th>
                <th>Player Name</th>
                <th>Point</th>
                <th>Total Point</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $key => $value) { ?>
                <tr>
                  <td><?php echo $key+1;?></td>
                  <td><?php echo $value['nama_player_1'];?> (<?php echo $value['company_1'];?>)</td>
                  <td><span class="badge badge-warning"><?php echo $value['point_1'];?></span></td>
                  <td><span class="badge badge-danger">VS</span></td>
                  <td><?php echo $value['nama_player_2'];?> (<?php echo $value['company_2'];?>)</td>
                  <td><span class="badge badge-warning"><?php echo $value['point_2'];?></span></td>
                  <td><span class="badge badge-success"><?php echo $value['point_1']+$value['point_2'];?></span></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  $(document).ready(function() {
    $('#dashboardTable').DataTable();
  });
</script>