@php $layout = 'layouts.super_user_layout'; @endphp
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)
  @section('title', 'Dashboard')
  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-primary shadow">
              @if(Auth::user()->user_level_id == 1 || Auth::user()->user_level_id == 2)
                <div class="card-header bg-dark">
                  <h3 class="card-title">ADMINISTRATOR MODULES</h3>
                </div>

                <div class="card-body">
                  <div class="row">
                    @if(Auth::user()->user_level_id == 1)
                      <div class="col-lg-3 col-6">
                        <div class="small-box shadow bg-warning">
                          <div class="inner">
                            <h3 id="h3TotalNoOfUsers">-</h3>
                            <p>User</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-users"></i>
                          </div>
                          <a href="{{ route('user') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>

                      <div class="col-lg-3 col-6">
                        <div class="small-box shadow bg-info">
                          <div class="inner">
                            <h3 id="h3TotalNoOfStations">-</h3>
                            <p>Station</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-object-group"></i>
                          </div>
                          <a href="{{ route('station') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>

                      <div class="col-lg-3 col-6">
                        <div class="small-box shadow bg-success">
                          <div class="inner">
                            <h3 id="h3AssemblyLines">-</h3>
                            <p>Assembly Lines</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-list"></i>
                          </div>
                          <a href="{{ route('assembly_line') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>

                      <div class="col-lg-3 col-6">
                        <div class="small-box shadow bg-danger">
                          <div class="inner">
                            <h3 id="h3MaterialProcess">-</h3>
                            <p>Material Process</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-list-ol"></i>
                          </div>
                          <a href="{{ route('materialprocess') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                          </a>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
                @endif
              </div>
              
              <div class="card card-primary shadow">
                <div class="card-header bg-dark">
                  <h3 class="card-title">PRODUCTION MODULES</h3>
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-warning">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Material Issuance</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-file-alt"></i>
                        </div>
                        <a href="{{ route('materialissuancealias') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-info">
                        <div class="inner">
                          <h3 id="h3TotalNoOfStations">-</h3>
                          <p>Parts Preparatory</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-clipboard-check"></i>
                        </div>
                        <a href="{{ route('partspreparatoryalias') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-success">
                        <div class="inner">
                          <h3 id="h3TotalNoOfMachines">-</h3>
                          <p>Production / <i>Runcard</i></p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-cogs"></i>
                        </div>
                        <a href="{{ route('prod_runcard_new') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-danger">
                        <div class="inner">
                          <h3 id="h3TotalNoOfShipConf">-</h3>
                          <p>Shipment Confirmation</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{route('shipmentconfirmationalias')}}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card card-primary shadow">
                <div class="card-header bg-dark">
                  <h3 class="card-title">Production Packing Traceability System v2.1</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-warning">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Packing Matrix</p>
                        </div>
                        <div class="icon">
                          <i class="fab fa-laravel"></i>
                        </div>
                        <a href="{{ route('ppts_ppc_matrix') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-info">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Series</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-tags"></i>
                        </div>
                        <a href="{{ route('ppts_oqc_series') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-success">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Dropdown Maintenance</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-tools"></i>     
                        </div>
                        <a href="{{ route('ppts_dropdown_maintenance') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-danger">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Production Resgistry</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-sitemap"></i>
                        </div>
                        <a href="{{ route('ppts_production_registry') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-warning">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>OQC Lot Application</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-project-diagram"></i>
                        </div>
                        <a href="{{ route('ppts_oqc_lot_app') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-info">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>OQC Inspection</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-microscope"></i>
                        </div>
                        <a href="{{ route('ppts_oqc_inspection') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-success">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Packing and Shipping</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-people-carry"></i>                 
                        </div>
                        <a href="{{ route('ppts_packing_and_shipping') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-3 col-6">
                      <div class="small-box shadow bg-danger">
                        <div class="inner">
                          <h3 id="">-</h3>
                          <p>Export Report</p>
                        </div>
                        <div class="icon">
                          <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('ppts_packing_and_shipping_export') }}" class="small-box-footer">
                          More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <center>
                <iframe width="653" height="490" src="https://www.youtube.com/embed/KWhajPXrhDs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </center> -->
            </div><!-- /.card -->
          </div> <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
  </div><!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    $(document).ready(function () {
      bsCustomFileInput.init();
      CountUserByStatForDashboard(1);
    });
  </script>
  @endsection
@endauth