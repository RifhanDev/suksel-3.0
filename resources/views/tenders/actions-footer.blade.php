<div class="well">
    @if(isset($has_submit))
        <button class="btn btn-primary">Simpan</button>
    @endif
    @if(!isset($is_list) && App\Tender::canList())
        <a href="{{route('tenders.index')}}" class="btn btn-default pull-right">Senarai Tender / Sebut Harga</a>  
    @endif
    {{Former::close()}}
    @if(isset($tender))
        @if($tender->canShow())
          <a href="{{ route('tenders.show', $tender->id) }}" class="btn btn-default ">Lihat Tender</a>
        @endif
        @if($tender->canDelete())
          {{Former::open(route('tenders.destroy', $tender->id))->class('form-inline')}}
            {{Former::hidden('_method', 'DELETE')}}
            <button type="button" class="btn btn-danger confirm-delete">Padam</button>
          {{Former::close()}}
        @endif
    @endif
</div>
