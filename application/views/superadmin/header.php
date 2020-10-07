<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Strategic Negotiation</title>
    <meta name="theme-color" content="#ffffff">
    <!-- Main styles for this application-->
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/vendors/@coreui/chartjs/css/coreui-chartjs.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">
  </head>
  <body class="c-app">
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
      <div class="c-sidebar-brand d-lg-down-none">
        <h3 class="c-sidebar-brand-full">Strego</h3>
        <h3 class="c-sidebar-brand-minimized">Strego</h3>
      </div>
      <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>superadmin">
          <svg class="c-sidebar-nav-icon">
            <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
          </svg> Dashboard</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>superadmin/admin">
          <i class="c-sidebar-nav-icon fa fa-users"></i> Admin Gameplay</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>superadmin/negotiation">
          <svg class="c-sidebar-nav-icon">
            <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-gamepad"></use>
          </svg> Negotiation Style</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>superadmin/decision">
          <svg class="c-sidebar-nav-icon">
            <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-gamepad"></use>
          </svg> Decision Making Style</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>company">
          <svg class="c-sidebar-nav-icon">
            <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-briefcase"></use>
          </svg> Company</a>
        </li>
      </ul>
      <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
    </div>

    <div class="c-wrapper c-fixed-components">
      <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
          <svg class="c-icon c-icon-lg">
            <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
          </svg>
        </button>
        <a class="c-header-brand d-lg-none" href="#">
          <h3>Strego</h3>
        </a>
        <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
          <svg class="c-icon c-icon-lg">
            <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
          </svg>
        </button>
        <ul class="c-header-nav ml-auto mr-4">
          <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <div class="c-avatar"><img class="c-avatar-img" src="<?php echo base_url();?>assets/assets/img/avatars/6.jpg" alt="user@email.com"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
              <div class="dropdown-header bg-light py-2"><strong>Account</strong></div><a class="dropdown-item" href="<?php echo base_url();?>login/superadminLogout">
                <svg class="c-icon mr-2">
                  <use xlink:href="#"></use>
                </svg> Logout</a>
            </div>
          </li>
        </ul>
      </header>

      <div class="c-body">