<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link">
    <img src="{{ asset('public/images/pricon_logo2.png') }}"
         alt="CNPTS"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">PATS - CN v2</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-header">ADMINISTRATOR</li>
        <li class="nav-item">
          <a href="{{ route('user') }}" class="nav-link">
            <i class="fas fa-users"></i>
            <p>
              User
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('station') }}" class="nav-link">
            <i class="fas fa-object-group"></i>
            <p>
              Station
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('machine') }}" class="nav-link">
            <i class="fas fa-hdd"></i>
            <p>
              Machine
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('mode_of_defect') }}" class="nav-link">
            <i class="fas fa-trash"></i>
            <p>
              Mode of Defect
            </p>
          </a>
        </li>
        {{-- <li class="nav-item">
          <a href="{{ route('assembly_line') }}" class="nav-link">
            <i class="fas fa-list"></i>
            <p>
              Assembly Lines
            </p>
          </a>
        </li> --}}
        <li class="nav-item">
          <a href="{{ route('materialprocess') }}" class="nav-link">
            <i class="fas fa-list-ol"></i>
            <p>
              Process Matrix
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('processtimeline') }}" class="nav-link">
            <i class="fas fa-calendar-alt"></i>
            <p>
              Process Timeline
            </p>
          </a>
        </li>

        <li class="nav-header">WAREHOUSE</li>
        <li class="nav-item">
          <a href="{{ route('warehouse') }}" class="nav-link">
            <i class="fas fa-qrcode"></i>
            <p>
              QR Code Issuance
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('kitting') }}" class="nav-link">
            <i class="fas fa-qrcode"></i>
            <p>
              Kitting
            </p>
          </a>
        </li>

        <li class="nav-header">RAPID</li>
        <li class="nav-item">
          <a href="{{ route('rapid_acdcs') }}" class="nav-link">
            <i class="fas fa-file-pdf"></i>
            <p>
              ACDCS Reference
            </p>
          </a>
        </li>

        <li class="nav-header">EXPORT REPORT</li>
        <li class="nav-item">
          <a href="{{ route('exportshippingrecord') }}" class="nav-link">
            <i class="fa fa-file-excel"></i>
            <p>
              Shipment Record
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('exportpartslotrecord') }}" class="nav-link">
            <i class="fa fa-file-excel"></i>
            <p>
              Parts Lot Record
            </p>
          </a>
        </li>

        <li class="nav-header">PRODUCTION</li>
        <li class="nav-item">
          <a href="{{ route('materialissuancealias') }}" class="nav-link">
            <i class="fas fa-file-alt"></i>
            <p>
              Material Input
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('partspreparatoryalias') }}" class="nav-link">
            <i class="fas fa-clipboard-check"></i>
            <p>
              Parts Preparatory
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-cogs"></i>
            <p>
              Production
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('prod_pre_assy') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pre-Assembly</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('prod_runcard_new') }}" class="nav-link">
                {{-- <i class="fas fa-user-secret"></i> --}}
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Runcard
                </p>
              </a>
            </li>
          </ul>
        </li> 

        <li class="nav-header">PPC</li>
        <li class="nav-item">
          <a href="{{ route('shipmentconfirmationalias') }}" class="nav-link">
            <i class="fas fa-file-excel"></i>
            <p>
              Shipment Confirmation
            </p>
          </a>
        </li>

        <li class="nav-header">LABEL PRINTING</li>
        <li class="nav-item">
          <a href="{{ route('packingandshippingalias') }}" class="nav-link">
            <i class="fa fa-qrcode"></i>
            <p>
              C3 and D Label Printing
            </p>
          </a>
        </li>

         {{-- <li class="nav-header">CN PPTS v2</li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-cogs"></i>
            <p>
              CN PPTS MODULES
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('ppts_oqc_series') }}" class="nav-link">
                &nbsp;<i class="fas fa-cubes"></i>
                <p>&nbsp;PPTS Series</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('ppts_production_registry') }}" class="nav-link">
                &nbsp;<i class="fas fa-sitemap"></i>
                <p>&nbsp;PPTS Production Registry</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('ppts_oqc_lot_app') }}" class="nav-link">
                &nbsp;<i class="fas fa-ticket-alt"></i>
                <p>&nbsp;PPTS OQC Lot Application</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('ppts_oqc_inspection') }}" class="nav-link">
                &nbsp;<i class="fa fa-swatchbook"></i>
                <p>&nbsp;PPTS OQC Inspection</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('ppts_packing_and_shipping') }}" class="nav-link">
                &nbsp;<i class="fas fa-truck-loading"></i>
                <p>&nbsp;PPTS Packing and Shipping</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('ppts_packing_and_shipping_export') }}" class="nav-link">
                &nbsp;<i class="fas fa-file-excel"></i>
                <p>&nbsp;PPTS Export Report</p>
              </a>
            </li>
          </ul>
        </li> --}}
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>