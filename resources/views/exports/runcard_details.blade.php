<html>
<head>
<style>

th,td {
	padding: 10px;
	border: 1px solid black;
}

td {
	padding: 5px;
	border: 1px solid black;
}

table, th, td {
  border: 1px solid black;
}

</style>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th colspan="2" style="text-align: center; font-family:'Arial'; font-size: 15pt; padding: 10px 10px; margin: 10px 10px; background-color:#38CDFF; border: 1px solid black; border: 1px solid black;" ><b>Runcard No.: 
				{{ $records->runcard_no }}</b></th>
			</tr>
		</thead>

		@php
			$device_name = '---';
			$device_code = '---';
			$po_qty = '---';

			for($index = 0; $index < count($records->prod_runcard_material_list); $index++){
				if($records->prod_runcard_material_list[$index]->wbs_sakidashi_issuance != null){
					$device_name = $records->prod_runcard_material_list[$index]->wbs_sakidashi_issuance->device_name;
					$device_code = $records->prod_runcard_material_list[$index]->wbs_sakidashi_issuance->device_code;
					$po_qty = $records->prod_runcard_material_list[$index]->wbs_sakidashi_issuance->po_qty;
				}
			}
		@endphp

		<tbody>
			<tr>
				<td style="border: 1px solid black;"><b>PO #</b></td>
				<td style="border: 1px solid black;">&nbsp;{!! nl2br($records->po_no) !!}</td>
			</tr>
			<tr>
				<td style="border: 1px solid black;"><b>PO Qty</b></td>
				<td style="border: 1px solid black;">&nbsp;{!! nl2br($po_qty) !!}</td>
			</tr>
			<tr>
				<td style="border: 1px solid black;"><b>Device Name</b></td>
				<td style="border: 1px solid black;">&nbsp;{!! nl2br($device_name) !!}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Device Code</b></td>
				<td style="border: 1px solid black;">&nbsp;{!! nl2br($device_code) !!}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Lot Qty</b></td>
				<td style="border: 1px solid black;">&nbsp;{!! nl2br($records->lot_qty) !!}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Assessment #</b></td>
				<td style="border: 1px solid black;">{{ $records->assessment_no }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>A Drawing #</b></td>
				<td style="border: 1px solid black;">{{ $records->a_drawing_no }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Revision </b></td>
				<td style="border: 1px solid black;">{{ $records->a_drawing_rev }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>G Drawing #</b></td>
				<td style="border: 1px solid black;">{{ $records->g_drawing_no }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Revision</b></td>
				<td style="border: 1px solid black;">{{ $records->g_drawing_rev }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Other Docs #</b></td>
				<td style="border: 1px solid black;">{{ $records->other_docs_no }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Revision </b></td>
				<td style="border: 1px solid black;">{{ $records->other_docs_rev }}</td>
			</tr>

			<tr>
				<td style="border: 1px solid black;"><b>Created At </b></td>
				<td style="border: 1px solid black;">{{ $records->created_at }}</td>
			</tr>

			<tr>
				<td style=""></td>
			</tr>

		</tbody>

		<thead>
			<tr>
				<td colspan="9" style="text-align: center; font-family:'Arial'; font-size: 13pt; padding: 10px 10px; margin: 10px 10px; background-color:#38CDFF; border: 1px solid black;" ><b>Runcard 4M (Method, Material, Man and Machine)</b></td>
			</tr>

			<tr>
				<!-- <th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px;"><b>Step</b></th> -->
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Process</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Date Time</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Materials</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Operator</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Machine</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Input</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>Output</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>NG Qty</b></th>
				<th style="text-align: left; font-family:'Arial'; font-size: 11pt; padding: 10px 10px; margin: 10px 10px; border: 1px solid black;"><b>MOD</b></th>
			</tr>

			@for($index = 0; $index < count($records->prod_runcard_station_many_details); $index++)
				<tr>
					<!-- <td>{{ $records->prod_runcard_station_many_details[$index]->step_num }}</td> -->
					<td style="border: 1px solid black;">{{ $records->prod_runcard_station_many_details[$index]->sub_station->name }}</td>
					<td style="border: 1px solid black;">{{ $records->prod_runcard_station_many_details[$index]->updated_at }}</td>

					@php
						$materials = '';
						$arr_materials = [];

						for($index2 = 0; $index2 < count($records->prod_runcard_station_many_details[$index]->prod_runcard_station_material_details); $index2++){
							$arr_materials[] = $records->prod_runcard_station_many_details[$index]->prod_runcard_station_material_details[$index2]->item_desc;
						}

						$materials = implode("\n", array_unique($arr_materials));

						if($materials == ''){
							$materials = '---';
						}
					@endphp

					<td style="border: 1px solid black;">{!! nl2br($materials) !!}</td>

					@php
						$operators = '';
						$arr_operators_name = [];

						for($index2 = 0; $index2 < count($records->prod_runcard_station_many_details[$index]->prod_runcard_station_operator_details); $index2++){
							$arr_operators_name[] = $records->prod_runcard_station_many_details[$index]->prod_runcard_station_operator_details[$index2]->operator_info->name;
						}

						$operators = implode("\n", array_unique($arr_operators_name));

						if($operators == ''){
							$operators = '---';
						}
					@endphp

					<td style="border: 1px solid black;">{!! nl2br($operators) !!}</td>

					@php
						$machines = '';
						$arr_machines_name = [];

						for($index2 = 0; $index2 < count($records->prod_runcard_station_many_details[$index]->prod_runcard_station_machine_details); $index2++){
							$arr_machines_name[] = $records->prod_runcard_station_many_details[$index]->prod_runcard_station_machine_details[$index2]->machine_info->name;
						}

						$machines = implode("\n", array_unique($arr_machines_name));

						if($machines == ''){
							$machines = '---';
						}
					@endphp

					{{-- <td style="border: 1px solid black;">{!! nl2br($machines) !!}</td> --}}
					<td style="border: 1px solid black;">{{ $machines }}</td>
					<td style="border: 1px solid black;">{{ $records->prod_runcard_station_many_details[$index]->qty_input }}</td>
					<td style="border: 1px solid black;">{{ $records->prod_runcard_station_many_details[$index]->qty_output }}</td>
					<td style="border: 1px solid black;">{{ $records->prod_runcard_station_many_details[$index]->qty_ng }}</td>
					
					@php
						$mods = '';
						$arr_mods_name = [];

						for($index2 = 0; $index2 < count($records->prod_runcard_station_many_details[$index]->prod_runcard_station_mod_details); $index2++){
							$arr_mods_name[] = $records->prod_runcard_station_many_details[$index]->prod_runcard_station_mod_details[$index2]->mod_qty . '=' . $records->prod_runcard_station_many_details[$index]->prod_runcard_station_mod_details[$index2]->mod_details->name;
						}

						$mods = implode("\n", array_unique($arr_mods_name));

						if($mods == ''){
							$mods = '---';
						}
					@endphp

					<td style="border: 1px solid black;">{!! nl2br($mods) !!}</td>
				</tr>
			@endfor
		</thead>
	</table>
</body>
</html>