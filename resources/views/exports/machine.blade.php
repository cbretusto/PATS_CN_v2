
<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Barcode</th>
		</tr>
	</thead>

	<tbody>
	@for($i = 0; $i < count($data); $i++)
		<tr>
			<td>{{ $data[$i]->id }}</td>
			<td>{{ $data[$i]->name }}</td>
			<td>{{ $data[$i]->barcode }}</td>
		</tr>
	@endfor
	</tbody>
</table>