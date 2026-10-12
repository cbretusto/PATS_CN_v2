<!-- jQuery -->
<script src="{{ asset('public/template/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('public/template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('public/template/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/template/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('public/template/dist/js/demo.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('public/template/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('public/template/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('public/template/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('public/js/sweetalert2@9.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('public/template/plugins/toastr/toastr.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('public/js/my_js/UserLevel.js') }}"></script>
<script src="{{ asset('public/js/my_js/User.js') }}"></script>
<script src="{{ asset('public/js/my_js/Machine.js') }}"></script>
<script src="{{ asset('public/js/my_js/ModeOfDefect.js') }}"></script>
<script src="{{ asset('public/js/my_js/Device.js?n=123') }}"></script>
<script src="{{ asset('public/js/my_js/Station.js') }}"></script>
<script src="{{ asset('public/js/my_js/SubStation.js') }}"></script>
<script src="{{ asset('public/js/my_js/StationSubStation.js') }}"></script>
<script src="{{ asset('public/js/my_js/MaterialProcess.js') }}"></script>
<script src="{{ asset('public/js/my_js/PackingOperator.js') }}"></script>
<script src="{{ asset('public/js/my_js/PackingInspector.js') }}"></script>
<script src="{{ asset('public/js/my_js/ShippingOperator.js') }}"></script>
<script src="{{ asset('public/js/my_js/ShippingInspector.js') }}"></script>
<!-- <script src="{{ asset('public/js/my_js/CNLine.js') }}"></script> -->
<script src="{{ asset('public/js/my_js/AssemblyLine.js') }}"></script>
<script src="{{ asset('public/js/my_js/OQCLotApp.js?n=123') }}"></script>
<script src="{{ asset('public/js/my_js/OQCVIR.js') }}"></script>
{{-- <script src="{{ asset('public/js/my_js/ProductionRuncard.js?n=123123') }}"></script> --}}
<script src="@php echo asset("public/js/my_js/ProductionRuncard.js?".date("YmdHis")) @endphp"></script>
<script src="{{ asset('public/js/my_js/WBSMaterialKitting.js') }}"></script>
<script src="{{ asset('public/js/my_js/WBSSakidashi.js') }}"></script>
{{-- <script src="{{ asset('public/js/my_js/Common.js?n=3') }}"></script> --}}
<script src="@php echo asset("public/js/my_js/Common.js?".date("YmdHis")) @endphp"></script>
<script src="@php echo asset("public/js/my_js/ProdPreAssy.js?".date("YmdHis")) @endphp"></script>
<script src="{{ asset('public/js/my_js/ProdPrelimInspection.js') }}"></script>
<script src="{{ asset('public/js/my_js/OQCPrelimInspection.js') }}"></script>
<script src="{{ asset('public/js/my_js/OQCPackingInspection.js?n=5') }}"></script>

<!--OQC LOT APP TS-->
<script src="{{ asset('public/js/my_js/OQCLotApplicationTS.js') }}"></script>