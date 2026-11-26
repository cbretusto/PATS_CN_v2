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

@section('title', 'D Label Printing')

@section('content_page')
<!-- <link href="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" media="all"> -->

<style type="text/css">
	#iframe_d_label_printing{
		position: absolute;
		width: 100%;
		height: 1200px;
/*		width: 100%!important;
		height: 100%!important;
*/		border: none;
	}
 </style>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header" style="display: none;">
 		<div class="container-fluid">
 			<div class="row mb-2">
 				<div class="col-sm-6">
 					<h1>DLABEL Printing</h1>
 				</div>
 				<div class="col-sm-6">
 					<ol class="breadcrumb float-sm-right">
 						<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
 						<li class="breadcrumb-item active">DLABEL Printing</li>
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
 				<div class="col-12">
 					<!-- general form elements -->
 					<div>
						<iframe id="iframe_d_label_printing" src="http://rapidx/dlabelv2"></iframe>
						<!-- <iframe id="iframe_d_label_printing" src="http://rapid/dlabelv2_test/index.php?systemname=pats"></iframe> -->
 					</div>
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

<!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
<!-- <script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script> -->
<!-- <script type="text/javascript" src="dist/qz-print-free_1.8.0_src/qz-print/js/deployJava.js"></script>
<script type="text/javascript" src="dist/jquery/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="dist/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
-->

<script type="text/javascript">
//=================
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

	$(document).ready(function(){
		setTimeout(function() {
			$('.nav-link').find('.fa-bars').closest('a').click();
		}, 100);
	});
</script>
@endsection
@endauth