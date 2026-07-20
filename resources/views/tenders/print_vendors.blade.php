@extends('layouts.report')
@section('content')
<div><strong>{{ $tender->tenderer->name }}</strong></div>
<h4 class="tender-title">{{ $tender->name }}</h4>

@if(count($purchases) > 0)
<?php $count = 1; ?>
<table class="table table-bordered">
    <thead class="bg-blue-selangor">
        <tr>
            <th>Bil.</th>
            <th>Nama Syarikat</th>
            @if(!$tender->only_advertise)<th>Beli Dokumen</th>@endif

            @if($tender->hasBriefing())<th>Taklimat</th>@endif

            @if(count($tender->siteVisits()->get()) > 0)
            <?php $index = 1; ?>
            @foreach($tender->siteVisits()->orderBy('id', 'asc')->get() as $visit)
            <th>LT {{ $index }} <input type="checkbox" class="checker" data-target="visit-{{ $visit->id }}"></th>
            <?php $index++; ?>
            @endforeach
            @endif

            <th>Label</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchases as $purchase)
        <tr>
            <td>{{ $count }}</td>
            <td>
                <strong>{{$purchase->vendor->name}}</strong>
                @if($purchase->ref_number)<br>No. Siri Dokumen: {{$purchase->ref_number}}@endif
                @if($purchase->exception)<br><span class="glyphicon glyphicon-star"></span> <small>Kebenaran Khas</small>@endif
                @if($purchase->winner)<br><b>Penender Berjaya</b>@endif
            </td>

            @if(!$tender->only_advertise)
            <td>
            @if($purchase->participate)
            <span class="glyphicon glyphicon-ok"></span>
            @else
            <span class="glyphicon glyphicon-remove"></span>
            @endif
            </td>
            @endif

            @if($tender->hasBriefing())
            <td>
            @if($purchase->briefing)
            <span class="glyphicon glyphicon-ok"></span>
            @else
            <span class="glyphicon glyphicon-remove"></span>
            @endif
            @endif

            @if(count($tender->siteVisits()->orderBy('id', 'asc')->get()) > 0)
            @foreach($tender->siteVisits()->get() as $visit)
            <td>
            @if(App\TenderVisitor::hasVisit($visit->id, $purchase->vendor_id))
            <span class="glyphicon glyphicon-ok"></span>
            @else
            <span class="glyphicon glyphicon-remove"></span>
            @endif
            </td>
            @endforeach
            @endif

            <td>
            @if(!empty($purchase->label))
            {{ $purchase->label }}
            @else
            <span class="glyphicon glyphicon-remove"></span>
            @endif
            </td>

            <td>
            @if(!empty($purchase->price) && number_format($purchase->label, 2) != '0.00')
            RM {{ number_format($purchase->label, 2) }}
            @else
            <span class="glyphicon glyphicon-remove"></span>
            @endif
            </td>
        </tr>
        <?php $count++; ?>
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-info">Tiada maklumat syarikat</div>
@endif
@stop

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    window.print();
});
</script>
@stop