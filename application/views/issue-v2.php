<main class="c-main">
  <div class="container-fluid">
    <div class="fade-in">
      <!-- /.row-->
      <div class="card">
        <div class="card-header">
          Data Question
        </div>
        <div class="card-body">
          <table class="table table-responsive-sm table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Title</th>
                <th>Params</th>
                <th>Type Form</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($list as $key => $value) { ?>
                <?php
                  $typeForm = ['Decimal', 'Integer', 'Percentage', 'String'];
                ?>
                <tr>
                  <td><?php echo $value->title;?></td>
                  <td><span class="badge badge-primary"><?php echo $value->params;?></span></td>
                  <td><?php echo $typeForm[$value->type-1];?></td>
                  <td class="text-center">
                    <a href="javascript:void(0);" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editModal<?php echo $value->id;?>" data-backdrop="static" data-keyboard="false">Edit</a>
                    <!-- <a href="javascript:void(0);" onclick="actionDelete(<?php echo $value->id;?>)" class="btn btn-danger btn-sm">Delete</a> -->
                  </td>
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
      <form class="form-horizontal" action="<?php echo base_url('issue/editv2/'.$value->id);?>" method="post">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Question</h4>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="title">Title</label>
                <div class="col-md-9">
                  <input class="form-control" id="title" type="text" name="title" placeholder="Enter Name.." required value="<?php echo $value->title;?>">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="title">Params</label>
                <div class="col-md-9">
                  <span class="badge badge-primary mt-2"><?php echo $value->params;?></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label" for="title">Type Form</label>
                <div class="col-md-9">
                  <select name="type" id="type" required class="form-control">
                    <option>-- Select Type Form --</option>
                    <option value="1" <?php echo $value->type == 1 ? 'selected':'';?>>Decimal</option>
                    <option value="2" <?php echo $value->type == 2 ? 'selected':'';?>>Integer</option>
                    <option value="3" <?php echo $value->type == 3 ? 'selected':'';?>>Percentage</option>
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
        window.location.href = "<?php echo base_url('issue/delete/');?>"+id;
      }
    });
  }
</script>