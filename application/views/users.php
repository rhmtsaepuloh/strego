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
                <th>Name</th>
                <th>Company</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $key => $value) { ?>
                <tr>
                  <td><?php echo $value->name;?></td>
                  <td class="text-center"><?php echo $value->name_company;?></td>
                  <td class="text-center"><?php echo $value->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Nonactive</span>';?></td>
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
      <form class="form-horizontal" action="<?php echo base_url('users/edit/'.$value->id);?>" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User</h4>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="hf-name">Status</label>
                <div class="col-md-9">
                  <select name="status" class="form-control">
                    <option value="1" <?php echo $value->status == 1 ? "selected" : "";?>>Active</option>
                    <option value="0" <?php echo $value->status == 0 ? "selected" : "";?>>Nonactive</option>
                  </select>
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