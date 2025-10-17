<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Nama</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Agensi</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Tarikh Daftar</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Emel</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Status</th>
	</tr>

   <?php $count = 1; ?>
   @foreach($users as $user)
		<tr>
			<td style="border: 1px solid #000;">{{ $count }}</td>
			<td style="border: 1px solid #000;">{{ $user->name }}</td>
			<td style="border: 1px solid #000;">{{ $user->agency ? $user->agency->name : 'No Agency' }}</td>
			<td style="border: 1px solid #000;">{{ $user->created_at}}</td>
			<td style="border: 1px solid #000;">{{ $user->email }}</td>
			<td style="border: 1px solid #000;">{{ $user->status() }}</td>
		</tr>
    	<?php $count++; ?>
   @endforeach
</html>
