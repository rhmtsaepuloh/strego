<main class="c-main">
  <div class="container-fluid">
    <div class="fade-in">
      <!-- /.row-->
      <div class="card">
        <div class="card-header">
          Data Company
          <?php if (count($list) < 2) { ?>
            <button class="btn btn-info float-right" data-toggle="modal" data-target="#myModal">Add Company</button>
          <?php } ?>
        </div>
        <div class="card-body">
          <table class="table table-responsive-sm table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Name</th>
                <th>Date registered</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $key => $value) { ?>
                <tr>
                  <td><?php echo $value->name;?></td>
                  <td><?php echo $value->created_at;?></td>
                  <td class="text-center"><a href="javascript:void(0);" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editModal<?php echo $value->id;?>">Edit</a> <a href="javascript:void(0);" onclick="actionDelete(<?php echo $value->id;?>)" class="btn btn-danger btn-sm">Delete</a></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" action="<?php echo base_url('company/insert');?>" method="post">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Insert Company</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-md-3 col-form-label" for="hf-name">Name</label>
              <div class="col-md-9">
                <input class="form-control" id="hf-name" type="text" name="name" placeholder="Enter Name.." autocomplete="email" required><span class="help-block">Please enter your company name</span>
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

  <?php foreach ($list as $key => $value) { ?>
    <div class="modal fade" id="editModal<?php echo $value->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <form class="form-horizontal" action="<?php echo base_url('company/edit/'.$value->id);?>" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Company</h4>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="hf-name">Name</label>
                <div class="col-md-9">
                  <input class="form-control" id="hf-name" type="text" name="name" placeholder="Enter Name.." autocomplete="email" required value="<?php echo $value->name;?>"><span class="help-block">Please enter your company name</span>
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