<main class="c-main">
  <div class="container-fluid">
    <div class="fade-in">
      <!-- /.row-->
      <div class="card">
        <div class="card-header">
          Communication Style History
        </div>
        <div class="card-body">
          <table class="table table-responsive-sm table-bordered table-striped table-sm" id="communicationStyle">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>NIM</th>
                <th>Class</th>
                <th>Action</th>
                <th>Process</th>
                <th>People</th>
                <th>Idea</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $key => $value) { ?>
                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $value->user->name; ?></td>
                  <td><?php echo $value->user->nim ? $value->user->nim : '-'; ?></td>
                  <td><?php echo $value->kelas; ?></td>
                  <td><?php echo $value->action; ?></td>
                  <td><?php echo $value->process; ?></td>
                  <td><?php echo $value->people; ?></td>
                  <td><?php echo $value->idea; ?></td>
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
    $('#communicationStyle').DataTable();
  });
</script>