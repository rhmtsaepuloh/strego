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
                <th>Competing</th>
                <th>Accommodating</th>
                <th>Collaborating</th>
                <th>Avoiding</th>
                <th>Compromising</th>
                <th>Substantive Score</th>
                <th>Relational Score</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $key => $value) { ?>
                <tr>
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $value->user->name; ?></td>
                  <td><?php echo $value->competing; ?></td>
                  <td><?php echo $value->accommodating; ?></td>
                  <td><?php echo $value->collaborating; ?></td>
                  <td><?php echo $value->avoiding; ?></td>
                  <td><?php echo $value->compromising; ?></td>
                  <td><?php echo $value->substantiveConcern; ?></td>
                  <td><?php echo $value->relationalConcern; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>