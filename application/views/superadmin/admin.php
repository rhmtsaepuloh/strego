<main class="c-main">
  <div class="container-fluid">
    <div class="fade-in">
      <!-- /.row-->
      <div class="card">
        <div class="card-header">
          Data Users
        </div>
        <div class="card-body">
          <table class="table table-responsive-sm table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Username</th>
                <th>Role Gameplay</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $key => $value) { ?>
                <tr>
                  <td><?php echo $value->username;?></td>
                  <td><?php echo $value->role ? 'V2' : 'V1';?></td>
                  <td class="text-center"><a href="javascript:void(0);" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editModal<?php echo $value->id;?>">Edit</a></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php foreach ($list as $key => $value) { ?>
    <div class="modal fade" id="editModal<?php echo $value->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <form class="form-horizontal" action="<?php echo base_url('superadmin/adminEdit/'.$value->id);?>" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User</h4>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label class="col-md-4 col-form-label" for="hf-name">Username</label>
                <div class="col-md-8">
                  <input type="text" name="username" class="form-control" required value="<?php echo $value->username; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-4 col-form-label" for="hf-name">Role Gameplay</label>
                <div class="col-md-8">
                  <b><?php echo $value->role ? 'V2' : 'V1';?></b>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-4 col-form-label" for="hf-name">Password</label>
                <div class="col-md-8">
                  <input type="password" name="password" class="form-control">
                  <i class="text-danger" style="font-size: 10px">Kosongkan jika tidak akan mengubah password</i>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="submit">Save</button>
            </div>
          </div>
          <!-- /.modal-content-->
        </div>
      </form>
      <!-- /.modal-dialog-->
    </div>
  <?php } ?>

</main>

<script type="text/javascript">
  function actionDelete(id) {
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this data!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        window.location.href = "<?php echo base_url('company/delete/');?>"+id;
      }
    });
  }
</script>