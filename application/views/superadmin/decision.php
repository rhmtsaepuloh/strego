<main class="c-main">
  <div class="container-fluid">
    <div class="fade-in">
      <!-- /.row-->
      <div class="card">
        <div class="card-header">
          Negotiation Style History
        </div>
        <div class="card-body">
          <table class="table table-responsive-sm table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>NIM</th>
                <th>Class</th>
                <th>Sequential</th>
                <th>Logical</th>
                <th>Global</th>
                <th>Personable</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $key => $value) { ?>
                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $value->user->name; ?></td>
                  <td><?php echo $value->user->nim ? $value->user->nim : '-'; ?></td>
                  <td><?php echo $value->kelas; ?></td>
                  <td><?php echo $value->sequential; ?></td>
                  <td><?php echo $value->logical; ?></td>
                  <td><?php echo $value->global; ?></td>
                  <td><?php echo $value->personable; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>>