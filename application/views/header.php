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
    <title>Strategic Negotiation</title>
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
    <link href="<?php echo base_url();?>assets/vendors/@coreui/chartjs/css/coreui-chartjs.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  </head>
  <body class="c-app">
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
      <div class="c-sidebar-brand d-lg-down-none">
        <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
          <use xlink:href="<?php echo base_url();?>assets/assets/brand/coreui.svg#full"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
          <use xlink:href="<?php echo base_url();?>assets/assets/brand/coreui.svg#signet"></use>
        </svg>
      </div>
      <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>dashboard">
            <svg class="c-sidebar-nav-icon">
              <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
            </svg> Dashboard</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>company">
            <svg class="c-sidebar-nav-icon">
              <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-briefcase"></use>
            </svg> Company</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>issue">
            <svg class="c-sidebar-nav-icon">
              <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-book"></use>
            </svg> Question Issue</a>
        </li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?php echo base_url();?>users">
            <svg class="c-sidebar-nav-icon">
              <use xlink:href="<?php echo base_url();?>assets/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
            </svg> User List</a>
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
        </button><a class="c-header-brand d-lg-none" href="#">
          <svg width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="<?php echo base_url();?>assets/assets/brand/coreui.svg#full"></use>
          </svg></a>
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
              <div class="dropdown-header bg-light py-2"><strong>Account</strong></div><a class="dropdown-item" href="<?php echo base_url();?>login/logout">
                <svg class="c-icon mr-2">
                  <use xlink:href="#"></use>
                </svg> Logout</a>
            </div>
          </li>
        </ul>
      </header>

      <div class="c-body">