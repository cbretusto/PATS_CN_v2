@php
    session_start();
    $isLogin = false;
    if(isset( Auth::user()->user_level_id )){
        $isLogin = true;
    }
@endphp

@php
    if(isset($iframe)){
        $layout = 'layouts.iframe_layout';
    }
    else  {
        $layout = 'layouts.super_user_layout';
    }

@endphp

@if($isLogin)
    @extends($layout)
    @section('title', 'Packing and Shipping')

    @php
        $emp_no = Auth::user();
    @endphp

    @section('content_page')
        <style>
            #iframe_packingandshipping{
                position: absolute;
                width: 100%;
                height: 700px;
                /* width: 100%!important;
                height: 100%!important; */    
                border: none;
            }
            
            .scanner {
                position: absolute;
                opacity: 0;
            }

            .modal-xl-custom {
                min-width: 97% !important;
            }

            table{
                color: black;
            }

            table.table tbody td{
                margin: 1px 1px;
                font-size: 16px;
                vertical-align: middle;
            }

            table.table thead th{
                vertical-align: middle;
            }

            .hidden_text {
                position: absolute;
                opacity: 0;
            }
        </style>

        @if (isset($iframe))
            <style>
                /* * FOR IFRAME */
                @media (min-width: 991px){
                    .content-wrapper{
                        margin-left: 0 !important;
                    }
                }
            </style>
        @endif
        
        <div class="content-wrapper">
            @if (!isset($iframe))
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>C3 Labeller Sticker</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">C3 Labeller Sticker</li>
                                </ol>
                            </div>
                        </div>
                        <div><!-- general form elements -->
                            <iframe id="iFrameC3Label" src="http://rapid/C3_Labeller_YPICS4v2/pages/index.php" style="width:100%; height:730px; border:none;"></iframe>
                        </div>   
                    </div><!-- /.container-fluid -->
                </section>
            @endif
        </div>
    @endsection
@else
    <script type="text/javascript">
        window.location = "../cn_ppts_v2";
    </script>
@endif
