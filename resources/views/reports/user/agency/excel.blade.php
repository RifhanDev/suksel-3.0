<html>
	<tr>
		<th style="border: 1px solid #000; background: #f3f3f3;">Bil.</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Nama</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Emel</th>
		<th style="border: 1px solid #000; background: #f3f3f3;">Peranan</th>
	</tr>

   <?php $count = 1; ?>
   @foreach($users as $user)
    	<tr>
        	<td style="border: 1px solid #000;">{{ $count }}</td>
        	<td style="border: 1px solid #000;">{{ $user->name }}</td>
        	<td style="border: 1px solid #000;">{{ $user->email }}</td>
        	<td style="border: 1px solid #000;">
            {{ implode('<br>', $user->roles->pluck('name')->toArray()) }}
        	</td>
    	</tr>
    	<?php $count++; ?>
   @endforeach
</html>
