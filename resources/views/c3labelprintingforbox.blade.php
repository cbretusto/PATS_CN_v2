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

@section('title', 'C3 Label Printing for Accessories')

@section('content_page')
<style type="text/css">
	textarea{
		resize: none;
	}
	#mdl_edit_material_details>div{
		/*width: 2000px!important;*/
		/*min-width: 1400px!important;*/
	}
	.hidden_scanner_input{
		position: absolute;
		opacity: 0;
	}

	#div_layout_1, #div_layout_2{
		transition: .5s;
	}

	/*.table th:first-child{
		width: 900px!important;
	*/
	.style_po_details_field{
		background: transparent;
		padding: 0;
		border:none;
		outline: none;
		padding-left: 15px;
	}

	.table{
		min-width: 550px;
	}
/*	.dataTables_filter {
		width: 50%;
		float: left;
		text-align: right;
	}*/
</style>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>C3 Label Printing for Accessories</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
						<li class="breadcrumb-item active">C3 Label Printing for Accessories</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content d-none" style="display: none;">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<div class="col-md-12">
					<!-- general form elements -->
					<div class="card card-primary">
<!--               <div class="card-header">
				<h3 class="card-title">Search P.O.</h3>
			  </div>
			-->
			<!-- Start Page Content -->
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend">
								<button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
							</div>
							<input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" readonly><!-- value="450198990900010" -->
						</div>
					</div>
					<div class="col-3 offset-3">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">PO No.</span>
							</div>
							<input id="txt_po_number" type="text" class="form-control" placeholder="---" readonly>
						</div>
					</div>
					<div class="col-3">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">Device</span>
							</div>
							<input id="txt_device_name" type="text" class="form-control" placeholder="---" readonly>
						</div>
					</div>
				</div>
			</div>
			<!-- !-- End Page Content -->

		</div>
		<!-- /.card -->
	</div>
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->


<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- right column -->
			<div class="col-12">
				<!-- general form elements -->
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Search Packing Code</h3>
					</div>
					<!-- Start Page Content -->
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table class="table table-sm table-bordered table-hover" id="tbl_packing_code">
										<thead>
											<tr>
												<th class="bg-info"></th>
												<th class="bg-info">Packing<br>Code</th>
												<th class="bg-info">PO</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>

								</div>
							</div>
						</div>

					</div>
					<!-- !-- End Page Content -->
				</div>
				<!-- /.card -->
			</div>
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Modal -->
<div class="modal fade" id="mdl_manual_c3" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Manual C3 Label</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">Search PO</span>
							</div>
							<input type="text" class="form-control form-control-sm" id="txt_manual_search_po" name="txt_manual_search_po">
						</div>
					</div>
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">Issuance Number</span>
							</div>
							<input type="text" class="form-control form-control-sm" id="txt_manual_issuance_no" name="txt_manual_issuance_no">
						</div>
					</div>
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">No. of Stickers</span>
							</div>
							<input type="number" class="form-control form-control-sm" id="txt_manual_sticker_ctr" name="txt_manual_sticker_ctr">
						</div>
					</div>
				</div>
				<br>
				<div class="row" hidden>
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">PO</span>
							</div>
							<input type="text" class="form-control form-control-sm" id="txt_manual_po_no" name="txt_manual_po_no" readonly>
						</div>
					</div>
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">Product Code</span>
							</div>
							<input type="text" class="form-control form-control-sm" id="txt_manual_device_code" name="txt_manual_device_code" readonly>
						</div>
					</div>
				</div>
				<div class="row" hidden>
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">Product Name</span>
							</div>
							<input type="text" class="form-control form-control-sm" id="txt_manual_device_name" name="txt_manual_device_name" readonly>
						</div>
					</div>
					<div class="col-6">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-50">
								<span class="input-group-text w-100">Assessed Qty</span>
							</div>
							<input type="text" class="form-control form-control-sm" id="txt_manual_kit_qty" name="txt_manual_kit_qty" readonly>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-primary" id="btn_proceed_as_void">Proceed as Void Print</button>
				<button type="button" class="btn btn-sm btn-success" id="btn_proceed">Proceed to Normal Print</button>
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_to_print" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Sticker Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row mb-1" id="row_alert_print" style="display: none;">
					<div class="col-12">
						<div class="alert alert-warning my-0"><i class="fa fa-info-circle"></i> Printing for Void stickers and NG stickers require scanning of Supervisor or Inspector's ID.</div>
					</div>
				</div>
				<div class="row mb-1">
					<div class="col-4">
					</div>
					<div class="col-8 text-right">
					</div>
				</div>
				<div class="row mb-1">
					<div class="col-12">
						<div class="table-responsive" style="overflow: auto;max-height: 300px;">
							<table class="table table-sm table-bordered table-hover" id="tbl_to_print" style="min-width: auto;">
								<thead>
									<tr>
										<th class="bg-info"><input type="checkbox" class="td_checkall"></th>
										<th class="bg-info">Transfer Slip</th>
										<th>Customer P/N</th>
										<th>Manufacture P/N</th>
										<th>Qty</th>
										<th>Date Code</th>
										<th>Lot Number</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-25">
								<span class="input-group-text w-100">Remarks</span>
							</div>
							<textarea class="form-control form-control-sm" rows="2" id="txt_print_remarks" name="txt_print_remarks"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="input-group input-group-sm w-25">
					<div class="input-group-prepend w-50">
						<span class="input-group-text w-100">Copies</span>
					</div>
					<input type="number" class="form-control form-control-sm w-25" name="txt_print_count" id="txt_print_count" min="1" max="5">
				</div>


				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-sm btn-warning" id="btn_re_print">Reprint for NG</button>
				<button type="button" class="btn btn-sm btn-success" id="btn_print">Normal Print</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_c3_label_history_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Print History</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<div class="table-responsive" style="overflow: auto;max-height: 300px;">
							<table class="table table-sm table-bordered table-hover" id="tbl_c3_label_history_details" style="min-width: auto;">
								<thead>
									<tr>
										<th class="bg-info">#</th>
										<th class="bg-info">Print Type</th>
										<th class="bg-info">Printed by</th>
										<th class="bg-info">Printed at</th>
										<th class="bg-info">Print Remarks</th>
										<th class="bg-info">Copies</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_to_receive" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Receive History</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row mb-1">
					<div class="col-12">
						<div class="table-responsive" style="overflow: auto;max-height: 300px;">
							<table class="table table-sm table-bordered table-hover" id="tbl_to_receive" style="min-width: auto;">
								<thead>
									<tr>
										<th class="bg-info"><input type="checkbox" class="td_checkall"></th>
										<th class="bg-info">Date<br>Code</th>
										<th class="bg-info">Qty</th>
										<th class="bg-info">Lot<br>Number</th>
										<th class="bg-info">Print<br>Type</th>
										<th class="bg-info">Printed<br>by</th>
										<th class="bg-info">Printed at</th>
										<th class="bg-info">Print<br>Remarks</th>
										<th>Received<br>by</th>
										<th>Received at</th>
										<th>Receive<br>Remarks</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="input-group input-group-sm mb-3">
							<div class="input-group-prepend w-25">
								<span class="input-group-text w-100">Remarks</span>
							</div>
							<textarea class="form-control form-control-sm" rows="2" id="txt_receive_remarks" name="txt_receive_remarks"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-sm btn-success" id="btn_receive">Receive</button>
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom-0 pb-0">
				<!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-0">
				<div class="text-center text-secondary">
					Please scan your ID.
					<h1><i class="fa fa-barcode fa-lg"></i></h1>
				</div>
				<input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input" autocomplete="off">
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_qrcode_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom-0 pb-0">
				<!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-0">
				<div class="text-center text-secondary">
					Please scan the code.
					<br>
					<br>
					<h1><i class="fa fa-qrcode fa-lg"></i></h1>
				</div>
				<input type="text" id="txt_qrcode_scanner" class="hidden_scanner_input">
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom-0 pb-1">
				<h5 class="modal-title" id="mdl_alert_title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="mdl_alert_body">
			</div>
		</div>
	</div>
</div>
<!-- /.Modal -->

<textarea id="txt_str" style="opacity: .01;">
</textarea>

</div>
<!-- /.content-wrapper -->
@endsection

@section('js_content')

<script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script>


<script type="text/javascript">
//================= FOR IFRAME OF PACKING AND SHIPPING MODULE
var packingandshipping = 0;
<?php
  if(isset($_GET['packingandshipping'])){
   echo "packingandshipping = 1;";
  }
?>
if(packingandshipping==1){
  $('.main-sidebar').hide();
  $('.main-header').hide();
  $('.content-header .breadcrumb').hide();
  $('.main-footer').hide();
  $('.content-wrapper').removeClass('content-wrapper');
}
//=================

let dt_batches,dt_c3_label_dt;

// var packingandshipping = 0;
// <?php
// isset($_GET['packingandshipping']){
// 	echo "packingandshipping = 1";
// }
// ?>

// // if(packingandshipping==1){
// // 	// $('.main-sidebar').hide();
// // }


//--------------------
//prevent typing of barcode/qr code
//--------------------
$(document).on('keydown',function(e){
	/*
		Enabled search of Employee # for debugging only
		Commented by -JD
	*/
	// if ($("#txt_employee_number_scanner").is(":focus")) {
	//   setTimeout(function() {
	//   	$("#txt_employee_number_scanner").val('');
	//   }, 50);
	// }
});
//--------------------
//prevent typing of barcode/qr code
//--------------------

//-----------------------
//----------------------- LABEL
//-----------------------
var stickers=new Array();//creates an array to store the sticker ZPL string codes
var java_enabled = navigator.javaEnabled();
if(java_enabled){
	deployQZ();//deploys the QZapplet for printing
	$('#txt_str').attr('data-java_enabled',1);
}else{
	var html_java  = '';
	html_java  += '<div class="alert alert-warning p-2">';
	html_java += 'Java is not loaded properly. You will not be able to print labels. ';
	html_java += 'Please contact ISS <b>local 205</b> for assistance.';
	html_java += '</div>';
	$('.content-header .container-fluid').append(html_java);
	$('#txt_str').attr('data-java_enabled',0);
}
function proceed_to_print(){
	var data_str = $('#txt_str').val();
	 stickers=data_str.split(":-:");//the data returned by this ajax would be the sticker ZPL codes. If the sticker is more than one, the stickers are separated by the delimeter ":-:"
	 // findPrinter("ZDesigner ZT220-200dpi");//calls the findprinter function passing the string "ZPL" - ppc
	/**
	 * Use for CN1 Packing only
	*/
	//  findPrinter("ZDesigner ZT230-200dpi ZPL ZT220");//calls the findprinter function passing the string "ZPL" - ppc
	
	/**
	 * Use for CN1 Packing only
	 */
	// findPrinter("ZDesigner ZT220-200dpi ZPL");
	
	
	findPrinter("ZDesigner ZT220-200dpi ZPL ZT220");//calls the findprinter function passing the string "ZPL" - ppc
}
/**
* Deploys different versions of the applet depending on Java version.
* Useful for removing warning dialogs for Java 6.  This function is optional
* however, if used, should replace the <applet> method.  Needed to address 
* MANIFEST.MF TrustedLibrary=true discrepency between JRE6 and JRE7.
*/
function deployQZ() {
	var attributes = {id: "qz", code:'qz.PrintApplet.class', 
	archive:"{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/qz-print.jar') }}", width:1, height:1};
	var parameters = {jnlp_href: "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/qz-print_jnlp.jnlp') }}", 
	cache_option:'plugin', disable_logging:'false', 
	initial_focus:'false'};
	if (deployJava.versionCheck("1.7+") == true) {}
		else if (deployJava.versionCheck("1.6+") == true) {
			attributes['archive'] = "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/jre6/qz-print.jar') }}";
			parameters['jnlp_href'] = "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/jre6/qz-print_jnlp.jnlp') }}";
		}
		deployJava.runApplet(attributes, parameters, '1.5');
	}
/**
* Returns whether or not the applet is not ready to print.
* Displays an alert if not ready.
*/
function notReady() {
  // If applet is not loaded, display an error
  if (!isLoaded()) {
  	return true;
  }
  // If a printer hasn't been selected, display a message.
  else if (!qz.getPrinter()) {
  	console.log('Please select a printer first by using the "Detect Printer" button.');
  	return true;
  }
  return false;
}
/**
* Returns is the applet is not loaded properly
*/
function isLoaded() {
	if (!qz) {
		console.log('Error:\n\n\tPrint plugin is NOT loaded!');
		return false;
	} else {
		try {
			if (!qz.isActive()) {
				console.log('Error:\n\n\tPrint plugin is loaded but NOT active!');
				return false;
			}
		} catch (err) {
			console.log('Error:\n\n\tPrint plugin is NOT loaded properly!');
			return false;
		}
	}
	return true;
}
/***************************************************************************
* Prototype function for finding the closest match to a printer name.
* Usage:
*    qz.findPrinter('zebra');
*    window['qzDoneFinding'] = function() { alert(qz.getPrinter()); };
***************************************************************************/
function findPrinter(name) {
	if (isLoaded()) {
		 // var name = "ZDesigner ZT220-200dpi";//calls the findprinter function passing the string "ZPL" - ppc
		 // name = "ZDesigner ZT230-200dpi ZPL ZT220";//calls the findprinter function passing the string "ZPL" - packing
		// Searches for locally installed printer with specified name
		qz.findPrinter(name);
		// Automatically gets called when "qz.findPrinter()" is finished.
		window['qzDoneFinding'] = function() {
			var printer = qz.getPrinter();
		    printZPL();//printing ZPL
			// Remove reference to this function
			window['qzDoneFinding'] = null;
		};
	}
}
function printZPL() {//function for printing
	if (notReady()) { return; }
	console.log("printing...");

	var ctr = 1;
	for(var i=0;i<stickers.length;i++)
	{
		qz.append(stickers[i]);//sets which printer to print
		qz.print();//prints the stickers

		if(i==stickers.length){
			console.log("Done.");
			setTimeout(function() {   //  call a 3s setTimeout when the loop is called
				ctr++;
				if( ctr <= $('#txt_print_count').val() ){
					i=0;
				}
			},5000)  //  ..  setTimeout()
		}
	}
}
//-----------------------
//----------------------- LABEL
//-----------------------
$(document).ready(function(){
//-----
//-----
//-----
	setTimeout(function() {
		$('.nav-link').find('.fa-bars').closest('a').click();
	}, 100);
	$('input').each(function(i, obj) {
		if (!this.hasAttribute("placeholder")) {
			if( $(this).prop('type') == 'number' ){
				$(this).prop('placeholder','0');
				$(this).prop('min','1');
			}
			if( $(this).prop('type') == 'text' ){
				$(this).prop('placeholder','---');
			}
		}
	});
	$('textarea').each(function(i, obj) {
		if (!this.hasAttribute("placeholder")) {
			$(this).prop('placeholder','Type here...');
		}
	});
	$(document).on('show.bs.modal', '.modal', function () {
		var zIndex = 1040 + (10 * $('.modal:visible').length);
		$(this).css('z-index', zIndex);
		setTimeout(function() {
			$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});
//-----
//-----
//-----

	//-----------------------
	//----------------------- LABEL
	//-----------------------

	dt_batches      = $('#tbl_devices').DataTable({
		"processing" : true,
		"serverSide" : true,
		"ajax" : {
			url: "select_c3_devices",
			data: function (param){
				param.po_number                       = $("#txt_po_number").val();
				param.txt_scan_transfer_slip          = $("#txt_scan_transfer_slip").val();
			}
		},
		bAutoWidth: false,
		"columns":[
		{ "data" : "raw_action", searchable:false, width: '20px' },
		// { "data" : "status", orderable:false, searchable:false },
		{ "data" : "issuance_no" },
		{ "data" : "po_no" },
		{ "data" : "device_code" },
		{ "data" : "device_name" },
		{ "data" : "kit_qty" },
		// { "data" : "received_dt" },
		// { "data" : "received_by" },
		],
		order: [[0, "desc"]],
		paging: true,
		"rowCallback": function(row,data,index ){
		},
		"drawCallback": function(row,data,index ){
		},
	});//end of DataTable


	dt_c3_label_dt      = $('#tbl_packing_code').DataTable({
		"processing" : true,
		"serverSide" : true,
		"ajax" : {
			url: "select_packing_code_dt",
			data: function (param){
			 // param.issue_no          = $("#tbl_batches tbody tr.table-active").find('.tbl_wbs_material_kitting_issuance_no').val();
			}
		},
		bAutoWidth: false,
		"columns":[
		{ "data" : "raw_action", orderable:false, searchable:false, "width":"20px" },
		{ "data" : "pack_code_no" },
		{ "data" : "po_num" },
		],
		order: [[1, "desc"]],
	   // paging: false,
	   "rowCallback": function(row,data,index ){
		 // if( $(row).find('.col_parts_preps_id').val()!=0 ){
		 //   $(row).addClass('table-success');
		 // }
		 // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
		 //   $(row).addClass('table-warning');
		 // }
		},
    });//end of DataTable

	dt_to_receive      = $('#tbl_to_receive').DataTable({
		"processing" : true,
		"serverSide" : true,
		"ajax" : {
			url: "select_to_receive_dt",
			data: function (param){
			 param.c3_label_id          = $("#mdl_to_print").attr('data-c3_label_id');
			}
		},
		bAutoWidth: false,
		"columns":[
		{ "data" : "raw_action", orderable:false, searchable:false, "width":"20px" },
		// { "data" : "c3_label_history.c3_label.issuance_no" },
		// { "data" : "c3_label_history.c3_label.po_no" },
		// { "data" : "c3_label_history.c3_label.device_name" },
		{ "data" : "c3_label_history.date_code" },
		{ "data" : "c3_label_history.lot_qty" },
		{ "data" : "c3_label_history.lot_number" },
		{ "data" : "raw_print_type" },
		{ "data" : "raw_created_by" },
		{ "data" : "created_at" },
		{ "data" : "remarks" },
		{ "data" : "raw_received_by" },
		{ "data" : "received_at" },
		{ "data" : "remarks_received" },
		],
		order: [[1, "desc"]],
	   // paging: false,
	   "rowCallback": function(row,data,index ){
		 // if( $(row).find('.col_parts_preps_id').val()!=0 ){
		 //   $(row).addClass('table-success');
		 // }
		 // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
		 //   $(row).addClass('table-warning');
		 // }
		},
    });//end of DataTable

	$('#tbl_packing_code').on('click','.td_btn_print',function(){
		var arr = [];
		arr['td_packincode'] = $(this).closest('tr').find('.td_packincode').val();
		arr['td_po_no'] = $(this).closest('tr').find('td:eq(2)').text();
		console.log(arr['td_packincode']); // Testing on C3 Label Printing for Accessories by -JD
		console.log(arr['td_po_no']); // Testing on C3 Label Printing for Accessories by -JD
		select_c3_label_content_to_print(arr);``
	});

	$('thead').on('change','.td_checkall',function(){
		var cval = $(this).prop('checked');
		$(this).closest('table').find('input[type="checkbox"]').not('[disabled]').prop('checked',cval);
	});
	$('#tbl_to_print').on('change','.td_ckb',function(){
		var ix = $(this).closest('tbody').find('tr').length;
		console.log('tr length: ', ix);
		var putcheck = 0;
		//---
		for (var i = (ix-1); i > -1; i--) {
			console.log('i: ', i);
			var tr = $(this).closest('tbody').find('tr:eq('+i+')');
			console.log('tr: ', tr.text());
			if( $(tr).find('.td_ckb').is(':checked') ){
				if( $(tr).find('.btn').length==0 ){
					putcheck = 1;
				}
				/*
					Added by -JD
				*/
				if($(this).closest('tr:eq('+i+')').find('td:eq('+i+')').text() == ''){
					putcheck = 0;
					console.log('td:eq 1 is empty ', $(this).closest('tr:eq('+i+')').find('td:eq('+i+')').text());
				}
			}
			if(putcheck){
				if( $(tr).find('.btn').length==0 ){
					$(tr).find('.td_ckb').prop('checked',true);
				}
			}
		}
	});
	$('#btn_print').click(function(){
		print_check('insert_c3_label_history_print');
	});
	$('#btn_re_print').click(function(){
		print_check('insert_c3_label_history_reprint');
	});
	//----------
	//----------
	//----------
	$('#btn_add_partial_qty').click(function(){
		alert_msg = '';
		if( $('#tbl_to_print tbody tr .btn').length < 1 ){
			alert_msg = 'Print the other lot numbers first before adding the partial lot.';
		}
		if(alert_msg){
			show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
			return;
		}
		//---
		$('#txt_employee_number_scanner').val('');
		$('#mdl_employee_number_scanner').attr('data-formid','select_c3_label_content_to_print_partial').modal('show');
	});

	//----------
	//----------
	//----------
	$('#btn_manual_print').on('click',function(){
		$('#txt_manual_search_po').val('');
		$('#txt_manual_issuance_no').val('');
		$('#txt_manual_sticker_ctr').val('');
		$('#mdl_manual_c3').modal('show');
	});
	$('#btn_proceed').on('click',function(){
		manual_proceed(1);
	});
	$('#btn_proceed_as_void').on('click',function(){
		manual_proceed(2);
	});
	function manual_proceed(print_type){
		var alert_msg = '';
		if( $('#txt_manual_sticker_ctr').val() == '' ){
			alert_msg = 'Number of sticker is required.';
		}
		if( $('#txt_manual_issuance_no').val() == '' ){
			alert_msg = 'Issuance no. is required.';
		}
		if( $('#txt_manual_search_po').val() == '' ){
			alert_msg = 'PO is required.';
		}
		if(alert_msg){
			show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
			return;
		}
		//-----
		clear_sticker_details();
		var data = {
			'td_id' 			: 'x',
			'po_no' 			: $('#txt_manual_search_po').val(),
			'issuance_no' 		: $('#txt_manual_issuance_no').val(),
			'ctr_total' 		: $('#txt_manual_sticker_ctr').val(),
		};
		$.ajax({
			'data'      : data,
			'type'      : 'get',
			'dataType'  : 'json',
			'url'       : 'select_c3_label_content_to_print',
			success : function(data){
				if($.trim(data)){
					if(data['c3_label']){
						$('#tbl_to_print tbody').html(data['tbody']);

						$('#mdl_to_print').attr('data-c3_label_id',0);
						$('#mdl_to_print').attr('data-print_type',print_type);

						$('#mdl_to_print').attr('data-issuance_no',data['c3_label']['issuance_no']);
						$('#mdl_to_print').attr('data-po_no',data['c3_label']['po_no']);
						$('#mdl_to_print').attr('data-device_code',data['c3_label']['device_code']);
						$('#mdl_to_print').attr('data-device_name',data['c3_label']['device_name']);
						$('#mdl_to_print').attr('data-kit_qty',data['c3_label']['kit_qty']);
						$('#mdl_to_print').attr('data-customer_pn',data['c3_label']['customer_pn']);
						$('#mdl_to_print').attr('data-boxing',data['c3_label']['boxing']);
						$('#mdl_to_print').attr('data-machine_code',data['c3_label']['machine_code']);
					}
				}
				$('#mdl_manual_c3').modal('hide');
				setTimeout(function() {
					$('#mdl_to_print').modal('show');
				}, 1000);
			}
		});
	}
	//----------
	//----------
	//----------
	$('#tbl_to_print').on('click','.td_btn_open',function(){
		clear_receive_details();
		var data = {
			'td_id' 				: $(this).closest('tr').find('.td_id').val(),
			'module' 				: 2,
		};
		$.ajax({
			'data'      : data,
			'type'      : 'get',
			'dataType'  : 'json',
			'url'       : 'select_c3_label_history_details_accessories_dt',
			success : function(data){
				var html = ''
				if($.trim(data)){
					$('#tbl_c3_label_history_details tbody').html(data['tbody']);
				}
				$('#mdl_c3_label_history_details').modal('show');
			}
		});
	});
	//----------
	$('#btn_receive_history').on('click',function(){
		$('#txt_receive_remarks').val('');
		dt_to_receive.ajax.reload();
		$('#mdl_to_receive').modal('show');

	});
	$('#btn_receive').on('click',function(){
		var alert_msg = '';
		if( $('#tbl_to_receive tbody tr .td_ckb:checked').length < 1 ){
			alert_msg = 'Nothing to receive.';
		}
		if(alert_msg){
			show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
			return;
		}
		//---
		$('#txt_employee_number_scanner').val('');
		$('#mdl_employee_number_scanner').attr('data-formid','insert_receive').modal('show');
	});
	//----------
	//----------
	//----------
	$( ".modal" ).on('shown.bs.modal', function(){
		$(this).find('.hidden_scanner_input').focus();
	});
	$(document).on('keypress',function(e){
		if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
			$('#txt_employee_number_scanner').focus();
			if( e.keyCode == 13 ){
				$('#mdl_employee_number_scanner').modal('hide');
				var formid = $("#mdl_employee_number_scanner").attr('data-formid');

				if ( ( formid ).indexOf('#') > -1){
					$( formid ).submit();
				}
				else{
					switch( formid ){
						case 'insert_c3_label_history_print':
							insert_c3_label_history(1);
							break;
						case 'insert_c3_label_history_reprint':
							insert_c3_label_history(2);
							break;
						case 'select_c3_label_content_to_print_partial':
							select_c3_label_content_to_print_partial();
							break;
						case 'insert_receive':
							insert_receive();
							break;
						default:
						break;
					}
				}
			}
		}
		else if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
			$('#txt_qrcode_scanner').focus();
			if( e.keyCode == 13 ){
				$('#mdl_qrcode_scanner').modal('hide');
				var formid = $("#mdl_qrcode_scanner").attr('data-formid');

				if ( ( formid ).indexOf('#') > -1){
					$( formid ).submit();
				}
				else{
					switch( formid ){
				// case 'fn_scan_d_label':
				// var val = $('#txt_qrcode_scanner').val();
				// $('#txt_scan_d_label').val(val);
				// fn_scan_d_label();
				// break;


				default:
				break;
			}
		}
		  }//key
		}
	});
	//----------
	//----------
	//----------


});//doc

function clear_sticker_details(){
	$('#btn_receive_history .badge').text(0);
	$('#tbl_to_print tbody').val('');
	$('#txt_print_remarks').val('');
	$('#txt_print_count').val('1');

	$('#mdl_to_print').attr('data-print_type',0);

	$('#row_alert_print').hide();
	$('#row_alert_print .alert').removeClass('alert-warning alert-info alert-primary');

	$('#btn_re_print').hide();
	$('#btn_receive_history').hide();



}
function select_c3_label_content_to_print(arr){
	clear_sticker_details();
	var data = {
		'td_packincode' 				: arr['td_packincode'],
		'td_po_no' 						: arr['td_po_no'],
	};
	$.ajax({
		'data'      : data,
		'type'      : 'get',
		'dataType'  : 'json',
		'url'       : 'select_c3_label_content_to_print_accessories',
		success : function(data){
			var html = ''
			if($.trim(data)){
				

				$('#btn_receive_history .badge').text(data['to_receive_total']);
				$('#tbl_to_print tbody').html(data['tbody']);
				$('#mdl_to_print').attr('data-c3_label_id',data['c3_label']['c3_label_id']);
				$('#mdl_to_print').attr('data-packing_code',data['c3_label']['packing_code']);

				$('#btn_receive_history').show();

			}
			$('#mdl_to_print').modal('show');
		}
	});
}
function print_check(action){
	var alert_msg = '';

	if( $('#txt_print_count').val() < 1 || $('#txt_print_count').val() > 5 ){
		alert_msg = 'No. of copies should be between 1 to 5. If you need more than 5 copies, print again after.';
	}

	if($('#mdl_to_print').attr('data-print_type')==2){
		if( $('#txt_print_remarks').val() == '' ){
			alert_msg = 'Remarks is required for void print.';
		}
	}
	if(action=='insert_c3_label_history_reprint'){
		if( $('#txt_print_remarks').val() == '' ){
			alert_msg = 'Remarks is required for reprint.';
		}

		if( $('#tbl_to_print tbody tr .td_ckb:checked').closest('tr').find('.btn').length < 1 ){
			alert_msg = 'Reprint for NG is not allowed for new stickers.';
		}
	}
	if( $('#tbl_to_print tbody tr .td_ckb:checked').length < 1 ){
		alert_msg = 'Nothing to print.';
	}
	if( $('#txt_str').attr('data-java_enabled') == 0 ){
		// alert_msg = 'Can\'t Print. Java is not loaded. Please re-open the browser or contact ISS Local 205.';
	}

	if(alert_msg){
		show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_msg,0);
		return;
	}
	//---
	$('#txt_employee_number_scanner').val('');
	$('#mdl_employee_number_scanner').attr('data-formid',action).modal('show');
}

/*
	Insert and Printing
	Insert in c3_label_history and find .td_lbl_str to pass in txt_str for printing labels after insertion
	Commented by -JD
*/
function insert_c3_label_history(action){
	var data_arr = [];
	var txt_str = '';

	// Collect all the .td_ckb with :checked pseudo class and push to data_arr(array) to insert in history and find .td_lbl_str to pass in txt_str for printing labels after insertion
	$('#tbl_to_print tbody tr').each(function(){
		if( $(this).find('.td_ckb').is(':checked') ){
			if($(this).find('td:eq(1)').text() == ''){
				console.log('No Transfer Slip');
				var rec = {
					'td_id' 				: $(this).find('.td_id').val(),
					'lot_qty' 				: $(this).find('td:eq(4)').text(),
					'date_code' 			: $(this).find('td:eq(5)').text(),
					'lot_number' 			: $(this).find('td:eq(6)').text(),
					// 'td_lot_number_ctr' 	: $(this).find('.td_lot_number_ctr').val(),
					'td_print_type_next' 	: $(this).find('.td_print_type_next').val(),
				};
			}else{
				var rec = {
					'td_id' 				: $(this).find('.td_id').val(),
					'lot_qty' 				: $(this).find('td:eq(4)').text(),
					'date_code' 			: $(this).find('td:eq(5)').text(),
					'lot_number' 			: $(this).find('td:eq(6)').text(),
					'td_lot_number_ctr' 	: $(this).find('.td_lot_number_ctr').val(),
					'td_print_type_next' 	: $(this).find('.td_print_type_next').val(),
				};
			}
			
			data_arr.push( rec );			
			txt_str+=$(this).find('.td_lbl_str').val()+':-:';
		}
	});
	// var txt_str_copy = txt_str;
	// for (var i = 2; i <= $('#txt_print_count').val() ; i++) {
	// 	txt_str+=txt_str_copy;
	// }
	txt_str = txt_str.slice(0,-3); // Remove the last delimiter -JD
	$('#txt_str').val(txt_str);
	

	var data = {
		'data_arr' 			: data_arr,

		'c3_label_id' 		: $('#mdl_to_print').attr('data-c3_label_id'),
		'issuance_no' 		: $('#mdl_to_print').attr('data-issuance_no'),
		'po_no' 			: $('#mdl_to_print').attr('data-po_no'),
		'device_code' 		: $('#mdl_to_print').attr('data-device_code'),
		'device_name' 		: $('#mdl_to_print').attr('data-device_name'),
		'kit_qty' 			: $('#mdl_to_print').attr('data-kit_qty'),
		'customer_pn' 		: $('#mdl_to_print').attr('data-customer_pn'),
		'boxing' 			: $('#mdl_to_print').attr('data-boxing'),
		'machine_code' 		: $('#mdl_to_print').attr('data-machine_code'),

		'print_type' 		: $('#mdl_to_print').attr('data-print_type'),
		'print_mode' 		: action,

		'txt_print_remarks' : $('#txt_print_remarks').val(),
		//---
		'packing_code' 		: $('#mdl_to_print').attr('data-packing_code'),
		'copies' 			: $('#txt_print_count').val(),
		//---
		'_token'                      	: '{{ csrf_token() }}',
		'txt_employee_number_scanner' 	: $("#txt_employee_number_scanner").val(),

	};
	console.log('data ',data);
	$.ajax({
		'data'      : data,
		'type'      : 'post',
		'dataType'  : 'json',
		'url'       : 'insert_c3_label_history',
		success : function(data){
			if($.trim(data)){
				show_alert(data['icon'],data['title'],data['body'],data['i']);
				if( data['i'] == 2 ){
					return;
				}
			  //---
			  $('#mdl_to_print').modal('hide');
			  dt_batches.ajax.reload();
			  dt_c3_label_dt.ajax.reload();
			  console.log('Sticker Labels ',	$('#txt_str').val());
			  proceed_to_print();
			}
		}
	});
}
//-----------------------
function clear_receive_details(){
	$('#tbl_c3_label_history_details tbody').val('');
}
function insert_receive(){
	var data_arr = [];
	$('#tbl_to_receive tbody tr').each(function(){
		if( $(this).find('.td_ckb').is(':checked') ){
			var rec = {
				'td_id' 					: $(this).find('.td_id').val(),
			};
			data_arr.push( rec );
		}
	});

	var data = {
		'data_arr' 				: data_arr,
		'txt_receive_remarks' 	: $('#txt_receive_remarks').val(),

		'_token'                      	: '{{ csrf_token() }}',
		'txt_employee_number_scanner' 	: $("#txt_employee_number_scanner").val(),
	};
	$.ajax({
		'data'      : data,
		'type'      : 'post',
		'dataType'  : 'json',
		'url'       : 'update_c3_label_history_details_receive',
		success : function(data){
			if($.trim(data)){
				show_alert(data['icon'],data['title'],data['body'],data['i']);
				if( data['i'] == 2 ){
					return;
				}
				//---
				$('#mdl_to_receive').modal('hide');
				var arr = [];
				arr['td_id'] = $('#mdl_to_print').attr('data-c3_label_id');
				select_c3_label_content_to_print(arr);
			}
		}
	});
}
 //-----------------------
 //-----------------------
 //-----------------------
 var alert_timeout = '';
 function hide_alert(){
 	alert_timeout = setTimeout(function()
 	{
 		$('#mdl_alert').modal('hide');
 	}, 2000);
 }
 function show_alert(icon,title,body,i){
 	$('#mdl_alert #mdl_alert_title').html(icon+' '+title);
 	$('#mdl_alert #mdl_alert_body').html(body);
 	$('#mdl_alert').modal('show');

   if(i == 1){//if ok, auto hide modal
   	clearTimeout(alert_timeout);
   	hide_alert();
   }
}

</script>
@endsection
@endauth