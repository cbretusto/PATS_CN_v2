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
@extends($layout)
<style>
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
    select[readonly].select2+.select2-container--default .select2-selection--multiple {
        background-color: #eceeef;
    }

    select[readonly].select2+.select2-container--default .select2-selection--single {
        background-color: #aaa;
        border: 1px solid #aaa;
        border-radius: 4px;
    }

    select[readonly].select2+.select2-container .select2-selection--single {
        background-color: #eceeef;
    }

    select[readonly].select2+.select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eceeef;
    }

    .modal-xl-custom{
        width: 95%!important;
        min-width: 90%!important;
    }
</style>
@section('title', 'Production Pre-Assembly')
@section('content_page')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pre-Assembly</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Production</li>
                        <li class="breadcrumb-item">Pre-Assembly</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>PO Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-primary" id="btnSearchPo"
                                        title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                                </div>
                                <input type="text" class="form-control" id="txtPoNumber" readonly="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Device Name</label>
                            <input type="text" class="form-control" id="txtDeviceName" readonly>
                        </div>
                        <div class="col-sm-3">
                            <label>PO Qty</label>
                            <input type="text" class="form-control" id="txtPoQty" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row d-flex justify-content-between">
                        <div class="col-sm-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1" >Pre-Assembly Name:</span>
                                </div>
                                <select name="" id="selPreAssyName" class="form-control form-control-sm" onchange="dtPreAssy.draw();"></select>
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm mt-1" id="btnAddPreAssy">Add Pre-Assembly</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                   
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="tablePreAssy" class="table table-sm table-bordered table-hover w-100">
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modalPreAssy"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static">
        <div class="modal-dialog modal-xl modal-xl-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPreAssyTitle">Pre-Assembly Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 border px-2 h-100">
                            <form action="post" id="formPreAssy">
                                @csrf
                                <input type="text" id="txtPreAssyId" name="assy_id" hidden>
                                <input type="text" id="txtSelPreAssyCode" name="pre_assy_station_code" hidden>

                                <div class="row">
                                    <div class="col-sm-6 pt-3">
                                        <span class="badge badge-secondary">1.</span> Details
                                    </div>
                                    <div class="col-sm-6 pt-3 d-flex justify-content-end">
                                        <button type="button" id="btnEditPreAssyDetails"
                                            class="btn btn-sm btn-link"><i class="fa fa-edit"></i>
                                            Edit</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">PO #</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtPONumber"
                                                name="po_no" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtPoQty" name="po_qty"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- <br> -->
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Device
                                                    Name</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                id="txtDeviceName" name="device_name" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Device
                                                    Code</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtDeviceCode"
                                                name="device_code" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Lot Qty</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtLotQty"
                                                name="lot_qty" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Pre-Assembly Number</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtPreAssyNumber"
                                                name="txt_pre_assy_number" placeholder="Auto Generated" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Other Docs Number</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOtherDocs"
                                                name="other_docs_number" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtRevision"
                                                name="revision" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                            </div>
                                            <textarea name="remarks" id="txtRemarks" class="form-control form-control-sm" readonly required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Created at</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="txtCreatedAt"
                                                name="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row divDetailsActions" style="display: none;">
                                    <div class="col d-flex justify-content-end">
                                        <button type="button" class="btn btn-sm btn-secondary mr-1" id="btnDetailsActionCancel">Cancel</button>
                                        <button type="button" class="btn btn-sm btn-success" id="btnSavePreAssyDetails">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-12 border">
                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <span class="badge badge-secondary">2.</span> Material List
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-sm small w-100" id="tablePreAssyMaterialList"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 border">
                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <span class="badge badge-secondary">3.</span> Process Stations
                                        </div>
                                        <div class="col-sm-6 d-flex justify-content-end">
                                            <button class="btn btn-sm btn-primary" id="btnPreAssyAddStation">Add Station</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover w-100 table-sm small" id="tablePreAssyStations">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="table-responsive">
                                       
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btnDoneDetails" disabled>Done</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPreAssyStation"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Pre-Assembly Station</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPreAssyStation" method="POST">
                    @csrf
                    <input type="text" id="preAssyStationId" name="pre_assy_station_id" hidden>
                    <input type="text" id="matProcessId" name="mat_process_id" hidden>
                    <input type="text" id="matProcessStationId" name="mat_process_station_id" hidden>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Step</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="steps" id="txtSteps" placeholder="(Auto Generated)" readonly>
                                    {{-- <input type="hidden" name="steps" id="txtSteps"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Process</span>
                                    </div>
                                    <select class="form-control select2 select2bs4"
                                        id="selSubStation" name="substation">
                                        <option value=""> N/A </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Date</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm"
                                        id="txtDate" name="date" readonly
                                        value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                                    </div>
                                    <select class="form-control select2 select2bs4 "
                                        id="selOperators" name="operators[]"
                                        multiple="multiple" readonly>
                                        <option value=""> N/A </option>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" title="Scan code"
                                            id="btnScanStationOperator"><i class="fa fa-qrcode"></i></button>
                                        <button class="btn btn-danger" type="button" title="Remove Selection"
                                            id=""
                                            onClick="removeSelection('#btnScanStationOperator');"><i
                                                class="fa fa-undo"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                                    </div>
                                    <select class="form-control select2 select2bs4 "
                                        id="txtMachine"
                                        name="machine[]" multiple="multiple" readonly>
                                        <option value="N/A"> N/A </option>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" title="Scan code"
                                            id="btnScanStationMachine"><i class="fa fa-qrcode"></i></button>
                                        <button class="btn btn-danger" type="button" title="Remove Selection"
                                            id=""
                                            onClick="removeSelection('#btnScanStationMachine');"><i
                                                class="fa fa-undo"></i></button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Input</span>
                                    </div>
                                    <input type="number" class="form-control form-control-sm inputInOut"
                                        id="txtQty" name="input_qty" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Output</span>
                                    </div>
                                    <input type="number" class="form-control form-control-sm inputInOut"
                                        id="txtOutputQty" name="output_qty" min="0" value="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                                    </div>
                                    <input type="number" class="form-control form-control-sm"
                                        id="txtNGQty" name="ng_qty" min="0" value="0"
                                        >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm"
                                        id="txtRemarks" name="remarks">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-sm btn-primary ml-auto my-2" data-toggle="modal" data-target="#mdlAddMaterial" id="btnAddNewMaterial">
                                        <i class="fa fa-plus"></i> Scan Material</button>
                                    <button type="button" class="btn btn-sm btn-danger my-2" id="btnRemoveMaterial"
                                        disabled><i class="fa fa-times"></i> Remove Last Row</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover small"
                                        id="tblPreAssyMaterial" style="min-width: 900px;">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>Material Code</th>
                                                <th>Material Name</th>
                                                <th>Lot No.</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <div style="float: left;">
                                <label>Total No. of NG: <span id="PreAssyNoOfNG"
                                        style="color: green;">0</span></label>
                            </div>
                            <div style="float: right;">
                                <button type="button" id="btnAddMODPreAssy" class="btn btn-sm btn-info" title="Add MOD" disabled><i
                                        class="fa fa-plus"></i> Add MOD</button>
                            </div>
                            <br><br>
                            <table class="table table-sm" id="tblMODPreAssy">
                                <thead>
                                    <tr>
                                        <th style="width: 55%;">Mode of Defect</th>
                                        <th style="width: 15%;">QTY</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-success" id="btnSavePerAssyStation">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mdlAddMaterial">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-initial-icon"></i> Add Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frmAddMaterial">
                    <input type="hidden" name="issuance_id" id="issuance_id">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Lot No.</label>
                                    <div class="input-group input-group-sm mb-3">
                                        <input type="text" class="form-control form-control-sm" name="lot_no"
                                            id="addProcess_lot_no" readonly="true">
                                        <div class="input-group-append">
                                            <button class="btn btn-info" type="button" title="Scan Mat'l Lot No"
                                                id="btnScanMaterialLotNo"><i class="fa fa-qrcode"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Material Code</label>
                                    <div class="input-group input-group-sm mb-3">
                                        <input type="text" class="form-control" name="material_part_no"
                                            id="material_part_no" readonly="true">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Material Name</label>
                                    <div class="input-group input-group-sm mb-3">
                                        {{-- <input type="text" class="form-control form-control-sm" name="material_id" id="addEditSelMaterial" readonly> --}}
                                        <input type="text" class="form-control" name="material_name" id="material_name"
                                            readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Material Type</label>
                                    <input type="text" class="form-control form-control-sm"
                                        id="addProcess_material_type_holder" readonly="true">
                                    <input type="text" class="form-control form-control-sm" name="material_type"
                                        id="addProcess_material_type" hidden readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="btnAddMaterial" class="btn btn-primary"><i id="btnAddMaterialIcon"
                                class="fa fa-check"></i> Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalScanQRCode" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <span id="ScanQRCodeDesc">Please scan the code.</span>
                        <br>
                        <br>
                        <h1><i class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtQRCodeData" class="hidden_scanner_input" value="450249487700010">
                    {{-- <input type="text" id="txtQRCodeData" class=""> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalScanQREmployee" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <span id="ScanQREmployeeDesc">Please scan the employee ID.</span>
                        <br>
                        <br>
                        <h1><i class="fa fa-qrcode fa-lg"></i></h1>
                    </div>
                    <input type="text" id="txtQREmployeeData" class="hidden_scanner_input">
                    {{-- <input type="text" id="txtQREmployeeData" class=""> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL FOR PRINTING  --}}
    <div class="modal fade" id="modalPreAssyQRCode">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-print"></i> QR Code - Pre Assembly</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPrintPreAssyQrCode">
                    @csrf
                    <div class="modal-body">
                        <input type="text" id="hiddenPreAssyId" name="id" hidden>
                        <div class="row">
                            <!-- PO 1 -->
                            <div class="col-sm-12">
                            
                            <center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->margin(5)->errorCorrection('H')->generate('0')) !!}" id="preAssyQrCode" style="max-width: 200px;"><br></center>
                            <div id="preAssyQrLabel" class="d-flex justify-content-center"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnPrintPreAssyQr" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
      </div>
</div>
@endsection

@section('js_content')
<script>
    let dtPreAssy, dtPreAssyStations, dtPreAssyMaterialList;
    let matKitting = null;
    let splittedMachine = [];
    let splittedEmpId = [];
    let materialArray = [];
    let dtPreAssyMaterials;
    let stepCount;
    let hiddenQrCodePreAssy, hiddenQrCodePreAssyLabel, hiddenQrCodePreAssyRequest;
    let preAssyId,preAssyQty,preAssyNo;
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
    theme: 'bootstrap4'
    });

    GetUserList($("#selOperators"));
    GetCboMachine($("#txtMachine"));

    $(document).ready(function(){
        getAssyStationSelect($('#selPreAssyName'));

        dtPreAssy = $("#tablePreAssy").DataTable({
            "processing" : true,
            "serverSide" : true,
            "bDestroy"  : true, // * This will destroy the datatable
            "ajax" : {
                url: "{{ route('dt_get_pre_assy') }}",
                data: function (param){
                    param.po            = $("#txtPoNumber").val();
                    param.pre_assy_code = $('#selPreAssyName').val();
                }
            },
            "columns":[
                { "data" : "action", orderable:false, searchable:false, 'title': 'Action' },
                { "data" : "raw_status", 'title': 'Status' },
                { 
                    "data" : "pre_assy_no",
                    render: function(data){
                        let result = "---";
                        if(data != null){
                            result = data;
                        }
                        return result;
                    },
                    'title' : 'Pre-Assembly No'
                },
                { 
                    "data"  : "raw_ok_details", 
                    "title" :  "OK - Pre Assy"
                },
                { 
                    "data"  : "raw_ng_details", 
                    "title" :  "NG - Pre Assy"
                }
            ],
            "columnDefs": [
                {"className": "text-center", "targets": "_all"},
                {
                    "targets": [3,4],
                    "data": null,
                    "defaultContent": "--"
                },
            ],
            headerCallback: function (thead) {
                $(thead).find('th').eq(3).addClass('bg-info');
                $(thead).find('th').eq(4).addClass('bg-info');
            }
        });//end of dataTableDevices

        dtPreAssyStations = $("#tablePreAssyStations").DataTable({
            "processing" : true,
            "serverSide" : true,
            "searching"  : false,
            "paging" : false,
            "info" : false,
            "bDestroy"  : true, // * This will destroy the datatable
            "ajax" : {
                url: "{{ route('dt_get_pre_assy_station') }}",
                data: function (param){
                    param.pre_assy_id = $("#txtPreAssyId").val();
                    // param.pre_assy_code = $('#selPreAssyName').val();
                }
            },
            "columns":[
                { "data" : "action", orderable:false, searchable:false, 'title': 'Action' },
                { "data" : "step_num", 'title' : 'Step' },
                { "data" : "sub_station_details.name", "title" : "Process" },
                { "data" : "created_at", "title" : "Created At" },
                { "data" : "qty_input", "title" : "Input" },
                { "data" : "qty_output", "title" : "Output" },
                { "data" : "ng_qty", "title" : "NG" },
                { "data" : "raw_materials", "title" : "Materials"},
                { "data" : "raw_operator", "title" : "Operators"},
                { "data" : "raw_machine", "title" : "Machines"},
                { "data" : "raw_mod", "title" : "MODs"},
            ],
            "columnDefs": [
                {"className": "text-center", "targets": "_all"},
            ],
            "drawCallback": async function ( settings ) {
                $('#btnDoneDetails').prop('disabled', true);

                /**
                 * Station name group for this table
                */
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                let data = api.row(0).data();

                console.log('rows', rows.length);
                console.log('stepCount', stepCount);
                if(stepCount == rows.length){
                    $('#btnDoneDetails').prop('disabled', false);
                }
                if(data){
                    let station_name = data.station_details.name;
                    $(rows[0]).before(
                        `<tr class="bg-info text-uppercase"><td colspan="${api.columns().data().length}" style="text-align:center;"><b>${station_name}</b></td></tr>`
                    );
                }
            }
        });//end of dataTableDevices

        dtPreAssyMaterials = $('#tblPreAssyMaterial').DataTable({
            // rowId: 'id', // optional
            "ordering": false,
            "searching": false,
            "paging": false,
            "info" : false,
            "columns": [
                { "data": "material_part_no" },
                { "data": "material_name" },
                { "data": "lot_no" },
                { 
                    "data": "material_type",
                    render: function (data) {
                    if(data == 1){
                        return "Material Kitting & Issuance";
                    }
                    else if(data == 2){
                        return "Sakidashi Issuance";
                    }

                    }
                },
            ],
        });

        dtPreAssyMaterialList =  $('#tablePreAssyMaterialList').DataTable({
            "processing" : true,
            "serverSide" : true,
            "ordering": false,
            "searching": false,
            "paging": false,
            "info" : false,
            "bDestroy"  : true, // * This will destroy the datatable
            "ajax" : {
                url: "{{ route('dt_get_pre_assy_materials') }}",
                data: function (param){
                    param.pre_assy_id = $("#txtPreAssyId").val();
                }
            },
            "columns": [
                { "data" : "step_num", "title": "Step No."},
                { 
                "data" : "material_details",
                "title" : "Material Name",
                render: function (data) {
                        var result = '';
                        if(data.length > 0){
                            for(let x = 0; x < data.length; x++){
                                result += '<span class="badge badge-pill badge-secondary mb-1">' + data[x]['item_name'] + '</span>';

                                if(x <= parseInt(data.length) - 2){
                                    result += '<br>';
                                }
                            }
                        }
                        else{
                            result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                        }
                        return result;
                    }
                },
                { 
                "data" : "material_details",
                "title" : "Lot No.",
                render: function (data) {

                        var result = '';
                        if(data.length > 0){
                            for(let x = 0; x < data.length; x++){
                                result += '<span class="badge badge-pill badge-secondary mb-1">' + data[x]['item_lot_no'] + '</span>';

                                if(x <= parseInt(data.length) - 2){
                                    result += '<br>';
                                }
                            }
                        }
                        else{
                        result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                        }
                        return result;
                    }
                },
                { 
                "data" : "material_details",
                "title" : "Type",
                render: function (data) {
                        var result = '';
                        if(data.length > 0){
                            for(let x = 0; x < data.length; x++){
                                if(data[x]['tbl_wbs'] == 1){
                                result += '<span class="badge badge-pill badge-secondary mb-1"> Material Kitting & Issuance</span>';
                                }
                                else{
                                result += '<span class="badge badge-pill badge-secondary mb-1"> Sakidashi Issuance</span>';
                                }

                                if(x <= parseInt(data.length) - 2){
                                result += '<br>';
                                }
                            }
                        }
                        else{
                            result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                        }
                        return result;
                    }
                },
            ],
            "columnDefs": [
                {"className": "text-center", "targets": "_all"},
            ],
        });

        $('#btnSearchPo').on('click', function(){
            $('#modalScanQRCode').attr('data-formid', 'scanPONumber').modal('show');
        });

        $('#modalScanQRCode').on('shown.bs.modal', function(){
            $(this).find('#txtQRCodeData').focus();
        });

        $('#modalScanQRCode').on('hidden.bs.modal', function(){
            $(this).find('#txtQRCodeData').val('');
            $(this).find('#ScanQRCodeDesc').html('Please scan the code.');
        });
        $('#modalScanQREmployee').on('shown.bs.modal', function(){
            $(this).find('#txtQREmployeeData').focus();
        });

        $('#modalScanQREmployee').on('hidden.bs.modal', function(){
            $(this).find('#txtQREmployeeData').val('');
            $(this).find('#ScanQREmployeeDesc').html('Please scan the employee ID.');
        });

        $('#modalPreAssyStation').on('hidden.bs.modal', function(){
            $('#formPreAssyStation')[0].reset();
            materialArray = [];
            removeErrors($('#formPreAssyStation'));
            $('#tblMODPreAssy tbody').empty();
            $('#selOperators').val([]).trigger('change');
            $('#txtMachine').val([]).trigger('change');
            dtPreAssyMaterials.clear().draw();
        });

        $('#btnEditPreAssyDetails').on('click', function(){
            $('.divDetailsActions').show();
            $(this).prop('disabled', true);
            $('#txtOtherDocs').prop('readonly', false);
            $('#txtRevision').prop('readonly', false);
            $('#txtRemarks').prop('readonly', false);
            // $('#selPreAssyName').prop('disabled', false);
        })
        $('#btnDetailsActionCancel').on('click', function(){
            $('.divDetailsActions').hide();
            $('#btnEditPreAssyDetails').prop('disabled', false);
            $('#txtOtherDocs').prop('readonly', true);
            $('#txtRevision').prop('readonly', true);
            $('#txtRemarks').prop('readonly', true);
            // $('#selPreAssyName').prop('disabled', true);
        });

        $('#btnSavePreAssyDetails').on('click', function(e){
            $('#modalScanQREmployee').attr('data-formid', 'savePreAssyDetails').modal('show');
        });

        $('#modalPreAssy').on('hidden.bs.modal', function(){
            $('.divDetailsActions').hide();
            $('#btnEditPreAssyDetails').prop('disabled', false);
            $('#txtOtherDocs').prop('readonly', true);
            $('#txtRevision').prop('readonly', true);
            $('#txtRemarks').prop('readonly', true);
            // $('#selPreAssyName').prop('disabled', true);
            $('#formPreAssy')[0].reset();
            removeErrors($('#formPreAssy'));
            dtPreAssyStations.draw();
            dtPreAssyMaterialList.draw();
        });

        $('#btnAddPreAssy').on('click', async function(){
            if(matKitting == null){
                toastr.error('Please scan PO.');
                return;
            }
            if($('#selPreAssyName').val() == null){
                toastr.error('Please select Pre-Assembly Name.');
                return;
            }
            $('#txtPONumber', $('#formPreAssy')).val(matKitting.po_no)
            $('#txtPoQty', $('#formPreAssy')).val(matKitting.po_qty)
            $('#txtDeviceName', $('#formPreAssy')).val(matKitting.device_name)
            $('#txtDeviceCode', $('#formPreAssy')).val(matKitting.device_code)
            $('#txtLotQty', $('#formPreAssy')).val(matKitting.device_info.boxing)
            $('#txtSelPreAssyCode', $('#formPreAssy')).val($('#selPreAssyName').val());
            $('#modalPreAssy').modal('show');
        });

        // Add Station
        $('#btnPreAssyAddStation').on('click', function(){
            getDataForAddingProcessPreAssy();
            $('#modalPreAssyStation').modal('show');

        });

        $('#selSubStation').on('change', function(){
            let selectedProcessMachine = $('#selSubStation', $('#formPreAssyStation')).find(':selected').attr('data-arr-machine');

            $('#matProcessId').val($('#selSubStation', $('#formPreAssyStation')).find(':selected').attr('data-mat-process-id'));
            $('#matProcessStationId').val($('#selSubStation', $('#formPreAssyStation')).find(":selected").attr('data-mat-process-station-id'));

            if(selectedProcessMachine != ''){
                splittedMachine = selectedProcessMachine.split(',');
            }
            let selectedProcessEmployee = $('#selSubStation', $('#formPreAssyStation')).find(':selected').attr('data-arr-emp-id');
            splittedEmpId = selectedProcessEmployee.split(',');
            console.log('splittedMachine', splittedMachine);
            console.log('splittedEmpId', splittedEmpId);
        });

        $('#btnScanStationOperator').on('click', function(){
            $('#modalScanQRCode').attr('data-formid', 'scanOperatorForPreAssyStation').modal('show');
        })
        $('#btnScanStationMachine').on('click', function(){
            $('#modalScanQRCode').attr('data-formid', 'scanMachineForPreAssyStation').modal('show');
        });

        
        
        $('.inputInOut').keyup(function(){
            let inputQty = $('#txtQty').val();
            let outputQty = $('#txtOutputQty').val();
            let ngQty = inputQty - outputQty;
            $('#txtNGQty').val(ngQty);

            if(ngQty > 0){
                $('#btnAddMODPreAssy').prop('disabled', false);
                $('#btnSavePerAssyStation').prop('disabled', true);
            }
            else{
                $('#btnAddMODPreAssy').prop('disabled', true);
                $('#btnSavePerAssyStation').prop('disabled', false);
            }
        });

        $('#txtNGQty').change(function(){
            let ngQty = $(this).val();

            if(ngQty > 0){
                $('#btnAddMODPreAssy').prop('disabled', false);
                $('#btnSavePerAssyStation').prop('disabled', true);
            }
            else{
                $('#btnAddMODPreAssy').prop('disabled', true);
                $('#btnSavePerAssyStation').prop('disabled', false);
            }
        });
        


        $('#btnAddMODPreAssy').on('click', function(){

            let appendHTML = '<tr>';
                appendHTML += '<td>';
                appendHTML += '<select class="form-control select2 select2bs4 selectMOD" name="mod[]">';
                    appendHTML += '<option value="">N/A</option>';
                appendHTML += '</select>';
                appendHTML += '</td>';
                appendHTML += '<td>';
                appendHTML += '<input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]" value="1" min="1">';
                appendHTML += '</td>';
                appendHTML += '<td>';
                appendHTML += '<center><button class="btn btn-xs btn-danger btnRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>';
                appendHTML += '</td>';
                appendHTML += '</tr>'
            $("#tblMODPreAssy tbody").append(appendHTML);
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
            GetCboMOD($("#tblMODPreAssy tr:last").find('.selectMOD'));
            let totalNoOfMOD = 0;

            $('#tblMODPreAssy .txtEditProdRunStaMODQty').each(function(i, obj) {
                console.log('this', $(this));
                if($(this).val() != null || $(this).val() != ""){
                    totalNoOfMOD += parseFloat($(this).val());
                }
            });

            if($("#txtNGQty").val() != totalNoOfMOD){
                toastr.warning('MOD NG Qty not Tally!');
                $("#PreAssyNoOfNG").css({color: 'red'});
                $("#btnSavePerAssyStation").prop('disabled', true);
                $("#PreAssyNoOfNG").css({color: 'red'});
            }
            else{
                $("#PreAssyNoOfNG").css({color: 'green'});
                $("#btnSavePerAssyStation").prop('disabled', false);
                $("#PreAssyNoOfNG").css({color: 'green'});
            }

            $("#PreAssyNoOfNG").text(totalNoOfMOD);
        })

        $("#tblMODPreAssy").on('click', '.btnRemoveMOD', function(){
            $(this).closest ('tr').remove();
            let totalNoOfMOD = 0;
            $("#PreAssyNoOfNG").text(totalNoOfMOD);

        });

        $("#tblMODPreAssy").on('keyup', '.txtEditProdRunStaMODQty', function(){
            let totalNoOfMOD = 0;

            if($(this).val() > $("#txtNGQty").val()){
                $("#PreAssyNoOfNG").css({color: 'red'});
                $("#btnSavePerAssyStation").prop('disabled', true);
            }
            else{
                $('#tblMODPreAssy .txtEditProdRunStaMODQty').each(function(i, obj) {
                    console.log($(this).val());
                    if($(this).val() != null || $(this).val() != ""){
                        if(!isNaN(parseFloat( $(this).val() ))){
                            totalNoOfMOD += parseFloat($(this).val());
                        }
                    }
                });

                if($("#txtNGQty").val() != totalNoOfMOD){
                    toastr.warning('MOD NG Qty not Tally!');
                    $("#PreAssyNoOfNG").css({color: 'red'});
                    $("#btnSavePerAssyStation").prop('disabled', true);
                }
                else{
                    $("#PreAssyNoOfNG").css({color: 'green'});
                    $("#btnSavePerAssyStation").prop('disabled', false);
                }
                $("#PreAssyNoOfNG").text(totalNoOfMOD);
            }
        });

        $('#btnScanMaterialLotNo').on('click', function(){
            $('#modalScanQRCode').attr('data-formid', 'scanMaterialLotNo').modal('show');
        })

        $('#btnAddMaterial').on('click', function(){
            var data = {};
            let result = false;
            $("#frmAddMaterial").serializeArray().map(function (x) {
                data[x.name] = x.value;
            });

            console.log('data', data);
            console.log('materialArray before push', materialArray);

            $.each(materialArray, function (key, value) {
                if (value.lot_no == data.lot_no) {
                    result = true;
                }
            })

            if (result) {
                toastr.error('Lot Number already exist!');
                return;
            }
            materialArray.push(data);
            console.log('materialArray after push', materialArray);

            dtPreAssyMaterials.clear().draw();
            dtPreAssyMaterials.rows.add(materialArray).draw();

            if (materialArray.length > 0) {
                $('#btnRemoveMaterial').prop('disabled', false);
            }
            $('#mdlAddMaterial').modal('hide');
        })

        $('#btnRemoveMaterial').on('click', function(){
            materialArray.pop();
            dtPreAssyMaterials.clear().draw();
            dtPreAssyMaterials.rows.add(materialArray).draw();

            if (materialArray.length == 0) {
                $('#btnRemoveMaterial').prop('disabled', true);
            }
        });

        $('#btnSavePerAssyStation').on('click', function(e){
            e.preventDefault();
            if($('#selSubStation', $('#formPreAssyStation')).find(':selected').attr('data-mat-process-step') == $('#txtSteps').val()){
                $('#modalScanQREmployee').attr('data-formid', 'savePerAssyStation').modal('show');
            }
            else{
                toastr.error('Process step not tally!');
            }
        });

        $('#btnDoneDetails').on('click', function(){
            $('#modalScanQREmployee').attr('data-formid', 'donePreAssy').modal('show');
        });

        /**
         * Qr Code Generation for pre-assembly
         */
        $('#formPrintPreAssyQrCode').submit(function(e){
            e.preventDefault();
            Swal.fire({
                icon: 'question',
                title: "Are you sure?",
                html: "Reprint will not be available without supervisor.",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Yes",
                denyButtonText: `Cancel`,
                confirmButtonColor: '#43a82a',
                cancelButtonColor: '#d33',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    changePrintStatus($(this));
                    popup = window.open();
                    let content = '';
                    content += '<html>';
                    content += '<head>';
                    content += '<title></title>';
                    content += '<style type="text/css">';
                    content += '@media print { .pagebreak { page-break-before: always; } }';
                    content += '</style>';
                    content += '</head>';
                    content += '<body>';
                    content += '<table style="margin-left: -5px; margin-top: 5px;">';
                    content += '<tr style="width: 290px;">';
                    content += '<td>';
                    content += `<img src="${hiddenQrCodePreAssy}" style="min-width: 85px; max-width: 85px;">`;
                    content += '</td>';
                    content += `<td style="font-size: 10px; font-family: Calibri;">${hiddenQrCodePreAssyRequest.pre_assy_no}<br>${hiddenQrCodePreAssyRequest.qty}</td>`;
                    content += '</tr>';
                    content += '</table>';
                    content += '<br>';
                    content += '</body>';
                    content += '</html>';
                    popup.document.write(content);
                    popup.focus(); //required for IE
                    popup.print();
                    popup.close();
                } 
            });
          
        })
    });
    /**
     * Handles the scanning process when a QR code is entered.
     * 
     * Listens for the "Enter" keypress (keyCode 13) on the input field `#txtQRCodeData`.
     * Based on the `data-formid` attribute of `#modalScanQRCode`, it triggers the appropriate action.
     *
     * @event keyup
     * @param {Event} e - The keyup event object
    */
    $(document).on('keyup', '#txtQRCodeData', function(e){
        if(e.keyCode == 13){
            let dataForm = $('#modalScanQRCode').attr('data-formid');
            let scannedData = $(this).val();
            switch (dataForm) {
                case 'scanPONumber':
                    GetMaterialKittingDetails(scannedData);
                    break;
                case 'scanOperatorForPreAssyStation':
                    console.log(scannedData);
                    console.log('$.inArray(scannedData, splittedEmpId)', $.inArray(scannedData, splittedEmpId));
                    if($.inArray(scannedData, splittedEmpId) !== -1){
                      $('#selOperators option[data-code="'+scannedData+'"]', $('#formPreAssyStation')).prop('selected', true).trigger('change');

                    }
                    else{
                        toastr.error('Operator not certified for this process.');
                    }
                    break;
                case 'scanMachineForPreAssyStation':
                    console.log(scannedData);
                    console.log('$.inArray(scannedData, splittedMachine)', $.inArray(scannedData, splittedMachine));
                    if($.inArray(scannedData, splittedMachine) !== -1){
                      $('#txtMachine option[data-code="'+scannedData+'"]', $('#formPreAssyStation')).prop('selected', true).trigger('change');

                    }
                    else{
                        toastr.error('Invalid Machine!');
                    }
                    break;
                case 'scanMaterialLotNo':
                    console.log(scannedData);
                    getScannedMaterial(scannedData);
                    break;
                default:
                    break;
            }
            $('#modalScanQRCode').attr('data-formid', '').modal('hide');
        }
    });

    /**
     * Event listener for the keyup event on the #txtQREmployeeData input field.
     * 
     * - Listens for the "Enter" key press (keyCode 13).
     * - Retrieves the data-formid attribute from the #modalScanQREmployee modal.
     * - Captures the scanned QR code data from the input field.
     * - Executes specific actions based on the value of data-formid:
     * - Clears the data-formid attribute and hides the modal after processing.
     * @function validateScannedUser() accept only 2 parameters.
     * @params {scannedData, array} - array will be manually set based on position on table users
     * 
     */
    $(document).on('keyup', '#txtQREmployeeData', async function(e){
        if(e.keyCode == 13){
            let dataForm = $('#modalScanQREmployee').attr('data-formid');
            let scannedId = $(this).val();
            let response
            switch (dataForm) {
                case 'savePreAssyDetails':
                    response = await validateScannedUser(scannedId, [0,1,4]);
                    console.log(response);
                    if(!response.result){
                        toastr.error(`${response.msg}`);
                        $(this).val('')
                        return;
                    }
                    savePreAssemblyDetails($('#formPreAssy'),response.user_id);
                    getDataForAddingProcessPreAssy();
                    break;
                case 'savePerAssyStation':
                    if($.inArray(scannedId, splittedEmpId) !== -1){
                        savePreAssemblyStation($('#formPreAssyStation'), scannedId);
                    }
                    else{
                        toastr.error('Operator not certified for this process.');
                    }
                    break;
                case 'donePreAssy': 
                    // Pre assembly details can be done by anyone with position 1-Prod'n Supervisor and 4-Operator
                    let token = "{{ csrf_token() }}";
                    response = await validateScannedUser(scannedId, [0,1,4]);
                    if(!response.result){
                        toastr.error(`${response.msg}`);
                        $(this).val('')
                        return;
                    }
                    donePreAssy($('#txtPreAssyId').val(), scannedId, token);
                    break;
                case 'reprintPreAssySticker':
                    response = await validateScannedUser(scannedId, [0,1,2]);
                    if(!response.result){
                        toastr.error('Please scan supervisor ID.');
                        $(this).val('')
                        return;
                    }
                    generateQRCode(preAssyId, preAssyQty, preAssyNo)

                    break;
                default:
                    break;
            }
            $('#modalScanQREmployee').attr('data-formid', '').modal('hide');
        }
    });

    $(document).on('click', '.btnViewPreAssy',  function(){
        let preAssyDetailsID = $(this).attr('data-id');
        let disabled = $(this).attr('data-disable');
        $.ajax({
            type: "get",
            url: "{{ route('get_pre_assy_details_by_id') }}",
            data: {
                id : preAssyDetailsID
            },
            dataType: "json",
            beforeSend: function(){
            },
            success: async function (response) {
                $('#txtPreAssyId', $('#formPreAssy')).val(response.id);
                $('#txtSelPreAssyCode', $('#formPreAssy')).val(response.pre_assy_station_code);
                $('#txtPONumber', $('#formPreAssy')).val(response.po_no);
                $('#txtPoQty', $('#formPreAssy')).val(matKitting.po_qty);
                $('#txtDeviceName', $('#formPreAssy')).val(matKitting.device_name);
                $('#txtDeviceCode', $('#formPreAssy')).val(matKitting.device_code);
                $('#txtLotQty', $('#formPreAssy')).val(response.lot_qty);
                $('#txtPreAssyNumber', $('#formPreAssy')).val(response.pre_assy_no);
                $('#txtOtherDocs', $('#formPreAssy')).val(response.other_docs_number);
                $('#txtRevision', $('#formPreAssy')).val(response.revision);
                $('#txtRemarks', $('#formPreAssy')).val(response.remarks);
                $('#txtCreatedAt', $('#formPreAssy')).val(response.created_at);

                await getDataForAddingProcessPreAssy();
                dtPreAssyStations.draw();
                dtPreAssyMaterialList.draw();
                $('#modalPreAssy').modal('show');
            },
            error: function(data, xhr, status){
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });

        $('#modalPreAssy .modal-body button').prop('disabled', disabled);
    });

    $(document).on('click', '.btnEditPreAssyProcessDetails', function(){
        let preAssyStationID = $(this).attr('data-station-id');
        $.ajax({
            type: "get",
            url: "{{ route('get_pre_assy_station_details_by_id') }}",
            data: {
                id : preAssyStationID
            },
            dataType: "json",
            beforeSend: function(){
                materialArray = [];
            },
            success: function (response) {
                let processDetails = response.prodPreAssyStation;
                $('#preAssyStationId', $('#formPreAssyStation')).val(processDetails.id);
                $('#selSubStation', $('#formPreAssyStation')).val(processDetails.sub_station_id).trigger('change');
                $('#txtSteps', $('#formPreAssyStation')).val(processDetails.step_num);
                $('#txtQty', $('#formPreAssyStation')).val(processDetails.qty_input);
                $('#txtOutputQty', $('#formPreAssyStation')).val(processDetails.qty_output).trigger('keyup');
                // $('#txtNGQty', $('#formPreAssyStation')).val(processDetails.ng_qty);
                $('#txtRemarks', $('#formPreAssyStation')).val(processDetails.remarks);

                let operatorArray = [];
                $.each(processDetails.operators, function(i, obj){
                    operatorArray.push(obj.operator_id);
                    $('#selOperators', $('#formPreAssyStation')).val(operatorArray).trigger('change');
                })

                let machineArray = [];
                $.each(processDetails.machines, function(i, obj){
                    machineArray.push(obj.machine_id);
                    $('#txtMachine', $('#formPreAssyStation')).val(machineArray).trigger('change');
                })

                let data = {};
                $.each(processDetails.material_details, function(e, obj){
                    data['lot_no'] = obj.item_lot_no
                    data['material_name'] = obj.item_name
                    data['material_part_no'] = obj.item_code
                    data['material_type'] = obj.tbl_wbs

                    materialArray.push(data);
                });

                dtPreAssyMaterials.clear().draw();
                dtPreAssyMaterials.rows.add(materialArray).draw();

                $('#tblMODPreAssy tbody').empty();
                $.each(processDetails.m_o_d, async function(i, obj){
                    // console.log('mod obj', obj);
                    let appendHTML = '<tr>';
                        appendHTML += '<td>';
                        appendHTML += '<select class="form-control select2 select2bs4 selectMOD" name="mod[]">';
                            appendHTML += '<option value="" selected>N/A</option>';
                        appendHTML += '</select>';
                        appendHTML += '</td>';
                        appendHTML += '<td>';
                        appendHTML += '<input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]" value="'+obj.mod_qty+'" min="1">';
                        appendHTML += '</td>';
                        appendHTML += '<td>';
                        appendHTML += '<center><button class="btn btn-xs btn-danger btnRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>';
                        appendHTML += '</td>';
                        appendHTML += '</tr>'
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                    $("#tblMODPreAssy tbody").append(appendHTML);
                    let lastRow = $("#tblMODPreAssy tr:last");
                    lastRow.find('.selectMOD').select2({
                        theme: 'bootstrap4'
                    });
                    await GetCboMOD(lastRow.find('.selectMOD'));
                    setTimeout(() => {
                        lastRow.find('.selectMOD').val(obj.mod_id).trigger('change')
                    }, 200);
                });

                $('.txtEditProdRunStaMODQty').trigger('keyup');
                $('#modalPreAssyStation').modal('show');
            }
        });
    });

    $(document).on('click', '.btnPrintPreAssySticker', function(){
        preAssyId = $(this).attr('data-pre-assy-id');
        preAssyQty = $(this).attr('data-qty');
        preAssyNo = $(this).attr('data-pre-assy-no');
        let preAssyPrintStatus = $(this).attr('data-pre-assy-print-status');
        if(preAssyPrintStatus == 1){
            $('#modalScanQREmployee').attr('data-formid', 'reprintPreAssySticker').modal('show');
            $('#ScanQREmployeeDesc').html('Please scan supervisor ID.')
        }
        else{
            generateQRCode(preAssyId, preAssyQty, preAssyNo)
        }
    });

    /**
     * Fetches material kitting details based on the provided PO number.
     * 
     * This asynchronous function sends an AJAX request to `get_wbs_material_kitting`
     * and updates the UI with the retrieved data. If no data is found, an error message is displayed.
     * 
     * @async
     * @function GetMaterialKittingDetails
     * @param {string} po - The PO number used to fetch material kitting details.
     * @returns {void}
    */
    const GetMaterialKittingDetails = async (po) => {
        await $.ajax({
            url: "get_wbs_material_kitting",
            method: 'get',
            dataType: 'json',
            data: {
                po_number: po
            },
            beforeSend: function(){
                $('#txtPoNumber').val('')
                $('#txtDeviceName').val('')
                $('#txtPoQty').val('')
                matKitting = null;
            },
            success: function (data) {
                matKitting = data['material_kitting'];
                if(matKitting != null){
                    $('#txtPoNumber').val(po)
                    $('#txtDeviceName').val(matKitting.device_name)
                    $('#txtPoQty').val(matKitting.po_qty)

                    dtPreAssy.draw();
                }
                else{
                    toastr.error('No Details Found!')
                }
                
            },
            error: function(){
                toastr.error('Something went wrong.<br> Please try again.')
            }
        });
    }

    const removeSelection = (id) => {
        console.log(id);
    }

    const getScannedMaterial = (scannedMaterial) => {
        $.ajax({
        type: "get",
        url: "get_scanned_material_details",
        data: {
            'scanned_mat_lot_no': scannedMaterial,
            'po_number': $('#txtPONumber', $('#formPreAssy')).val(),
            // 'po_number': '450250956700010',
            'selected_process_id': $('#selSubStation', $('#formPreAssyStation')).find(':selected').attr('data-mat-process-id')
            // 'selected_process_id': '430'
        },
        dataType: "json",
        beforeSend: function () {
            Swal.fire({
                width: '20rem',
                // html: '<div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div>',
                allowOutsideClick: false,
                onRender: function () {
                    $('.swal2-content').prepend('<div class="spinner-border text-dark" role="status" style="width: 3rem; height: 3rem;"><span class="sr-only">Loading...</span></div>');
                },
                showConfirmButton: false,
            });
        },
        success: function (response) {
            if (response['result']) {
              $('#addProcess_lot_no', $('#frmAddMaterial')).val(scannedMaterial);
              $('#material_part_no', $('#frmAddMaterial')).val(response['material_code']);
              $('#material_name', $('#frmAddMaterial')).val(response['material_name']);
              $('#issuance_id', $('#frmAddMaterial')).val(response['issuance_id']);
              let type = "";
              if(response['type'] == 1){
                type = "Material Kitting & Issuance";
              }
              else{
                type = "Sakidashi Issuance";
              }
              $('#addProcess_material_type', $('#frmAddMaterial')).val(response['type']);
              $('#addProcess_material_type_holder', $('#frmAddMaterial')).val(type);

              swal.fire({
                  icon: "success",
                  text: "Success",
                  timer: 800,
                  showConfirmButton: false,
              });
            } else {
                swal.fire({
                    icon: "error",
                    text: response['msg'],
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
        },
        error: function (data, xhr, status) {
            swal.fire({
                icon: "error",
                text: "error",
                timer: 800,
                showConfirmButton: false,
            });
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
      });
    }

    const savePreAssemblyStation = (form, scannedId) => {
        let data = form.serialize() + "&" +
        $.param({ 
            materialArray,
            'scanned_id': scannedId,
            'po_number' : $('#txtPONumber', $('#formPreAssy')).val(),
            'device_name' : $('#txtDeviceName', $('#formPreAssy')).val(),
            'device_code' : $('#txtDeviceCode', $('#formPreAssy')).val(),
            'pre_assy_id' : $('#txtPreAssyId', $('#formPreAssy')).val(),
            
        });

        $.ajax({
            type: "post",
            url: "save_pre_assembly_station",
            data: data,
            dataType: "json",
            beforeSend: function(){
            },
            success: function (response) {
                if(response.result == true){
                    toastr.success(response.msg);
                    dtPreAssyStations.draw();
                    $('#modalPreAssyStation').modal('hide');
                }
            },
            error: function(data, xhr, status){
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    }
</script>
@endsection