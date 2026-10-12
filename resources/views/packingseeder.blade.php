@extends('layouts.super_user_layout')

@section('title', 'Packing Seeder')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Packing Seeder</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Packing Seeder</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Packing & Shipment Seeder</h3>
            </div>

            <form id="formSeeder" method="post">
              @csrf
            <!-- Start Page Content -->
            <div class="card-body">
                
              <div class="row">
                  <div class="col-sm-2">
                    <label>P.O. Number</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="id_po_no" name="name_po_no">
                      </div>
                  </div>

                  <div class="col-sm-2">
                    <label>Device Name</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="id_device_name" name="name_device_name">
                  </div>
                </div>


                  <div class="col-sm-2">
                    <label>Packing Code</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="id_packing_code" name="name_packing_code">
                  </div>
                </div>

                  <div class="col-sm-2">
                    <label>Packing Day</label>
                    <div class="input-group">
                        <input type="datetime-local" class="form-control" id="id_packing_day" name="name_packing_day">
                      </div>
                  </div>

                  <div class="col-sm-2">
                    <label>Operator ID</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="id_operator_id" name="name_operator_id">
                  </div>
                </div>

              </div>

              <hr>

               <div class="row">

                  <div class="col-sm-2">
                      <label>Inspector ID</label>
                      <div class="input-group">
                      <input type="text" class="form-control" id="id_inspector_id" name="name_inspector_id">
                    </div>
                  </div>

                  <div class="col-sm-2">
                    <label>Shipment Day</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="id_shipment_day" name="name_shipment_day">
                      </div>
                  </div>

                  <div class="col-sm-2">
                    <label>Shipment Destination</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="id_shipment_destination" name="name_shipment_destination">
                  </div>
                </div>

                <div class="col-sm-2">
                    <label>Shipment Remarks</label>
                    <div class="input-group">
                    <input type="text" class="form-control" id="id_shipment_remarks" name="name_shipment_remarks">
                  </div>
                </div>

              </div>

          

            </div>
            <!-- !-- End Page Content -->
                  <div class="card-footer">
              <button type="submit" class="btn btn-sm btn-success" id="id_submit_seeder">SUBMIT DATA</button>
            </div>


          </form>

      
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('js_content')
<script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();
  });

 $('#formSeeder').submit(function(event){
      event.preventDefault();
      SubmitSeeder();

    });


</script>
@endsection