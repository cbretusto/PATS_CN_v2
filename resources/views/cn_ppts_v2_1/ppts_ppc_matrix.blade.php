
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

@section('title', 'Matrix')

@section('content_page')
<!-- <link href="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" media="all"> -->
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>PPTS v2.1 - Matrix</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active">PPTS - Matrix</li>
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
						<iframe  src="http://rapidx/cn_ppts_v2_1/iframe_request?username={{ Auth::user()->username }}&emp_id={{ Auth::user()->employee_id }}&iframe=ppc_matrix" style="border: none;" no-border height="850" width="100%"></iframe>
						{{-- <iframe  src="http://rapidx/cn_ppts_v2/iframe_request?username={{ Auth::user()->username }}&emp_id={{ Auth::user()->employee_id }}&iframe=ppc_matrix" style="border: none;" no-border height="850" width="100%"></iframe> --}}
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
<script type="text/javascript">
	
</script>
@endsection
@endauth