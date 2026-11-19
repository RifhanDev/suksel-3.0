{{-- Kod bidang rows for one body (MOF or CIDB) in tenders/print. Two or more groups get a light panel each --}}
@php $grouped = count($groups) > 1; @endphp

@if ($grouped)
    <tr class="tp-codes-space"><td colspan="4"></td></tr>
@endif

@foreach ($groups as $data)
    @foreach ($data['codes'] as $codeId => $code)
        @if (! $loop->first)
            <tr class="tp-codes-op {{ $grouped ? 'tp-grp' : '' }}"><td></td><td colspan="2" class="tp-codes-opcell">{!! tender_code_rule_html($data['inner_rule'], $innerDefault) !!}</td><td></td></tr>
        @endif
        <tr class="{{ $grouped ? 'tp-grp' : '' }} {{ $grouped && $loop->first ? 'tp-grp-first' : '' }} {{ $grouped && $loop->last ? 'tp-grp-last' : '' }} {{ ! $grouped && $loop->parent->last && $loop->last ? 'tp-codes-end' : '' }}">
            <td class="tp-codes-body">{{ $loop->parent->first && $loop->first ? $label : '' }}</td>
            <td class="tp-codes-code">{{ $code }}</td>
            <td class="tp-codes-name">{{ $names[$codeId] ?? '' }}</td>
            <td></td>
        </tr>
    @endforeach
    @if ($data['join_after'] !== null)
        <tr class="tp-codes-join"><td></td><td colspan="2"><span class="tp-pill tp-pill-outline">{!! tender_code_rule_html($data['join_after'], $joinDefault) !!}</span></td><td></td></tr>
    @endif
@endforeach

@if ($grouped)
    <tr class="tp-codes-space tp-codes-end"><td colspan="4"></td></tr>
@endif
