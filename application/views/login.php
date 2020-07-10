<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v3.0.0
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Login | Strategic Negotiation</title>
    <link rel="manifest" href="<?php echo base_url();?>assets/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url();?>assets/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Main styles for this application-->
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  </head>
  <body class="c-app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
                <h1>Login</h1>
                <p class="text-muted">Sign In to your account</p>
                <form id="loginForm" action="javascript:void(0);">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend"><span class="input-group-text">
                        <svg class="c-icon">
                          <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                        </svg></span></div>
                    <input class="form-control" type="text" name="username" placeholder="Username" required>
                  </div>
                  <div class="input-group mb-4">
                    <div class="input-group-prepend"><span class="input-group-text">
                        <svg class="c-icon">
                          <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                        </svg></span></div>
                    <input class="form-control" name="password" type="password" placeholder="Password" required>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button class="btn btn-primary px-4" type="submit">Login</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?php echo base_url();?>assets/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <!--[if IE]><!-->
    <script src="<?php echo base_url();?>assets/vendors/@coreui/icons/js/svgxuse.min.js"></script>
    <!--<![endif]-->

    <script>
       $(document).ready(function() {
        $('#loginForm').on('submit',(function(e) {
          e.preventDefault();
          var formData = new FormData(this);

          $.ajax({
            method  : 'POST',
            url     : "<?php echo base_url('login/auth'); ?>",
            data    : formData,
            contentType: false,
            processData: false,
            success: function(data, status, xhr) {
              try {
                var result = JSON.parse(xhr.responseText);
                if (result.status == true) {
                  location.reload();
                } else {
                  swal({
                    icon: "warning",
                    title: "",
                    text: result.message,
                  });
                }
              } catch (e) {
                swal({
                  title: "",
                  text: "Sistem error.",
                  icon: "warning"
                });
              }
            },
            error: function(data) {
              // btnlogin.ladda('stop');
              // swal({
              //   title: "",
              //   text: "Terjadi kesalahan sistem.",
              //   type: "warning"
              // });
            }
          });
        }));
       });
    </script>

  </body>
</html>