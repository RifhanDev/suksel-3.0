@php
    $perananLabels = ['1' => 'Pengerusi', '2' => 'Setiausaha', '3' => 'Ahli'];
    $rows = ($membersByJenis[$jenis] ?? collect());
@endphp
@forelse($rows as $member)
    <tr>
        <td class="text-center">{{ $member->user->ic_number ?? '-' }}</td>
        <td>{{ $member->user->name ?? '-' }}</td>
        <td>{{ $member->user->jawatan ?? '-' }}</td>
        <td>{{ $member->user->email ?? '-' }}</td>
        <td class="text-center">{{ $member->user->gred ?? '-' }}</td>
        <td class="text-center">{{ (string) $member->p_p === '1' ? 'Ya' : 'Tidak' }}</td>
        <td>{{ $perananLabels[(string) $member->peranan] ?? 'Ahli' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-3">Tiada ahli jawatankuasa.</td>
    </tr>
@endforelse
