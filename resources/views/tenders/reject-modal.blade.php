<div id="rejectForm" class="hidden">
    <form id="myForm" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-2">Alasan Penolakan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="reason" name="reason" />
            </div>
        </div>
        @if ($templates)
            <div class="col-sm-12">
                <div class="col-sm-5">
                    <hr>
                </div>
                <div class="col-sm-2 text-center" style="margin: 10px 0">
                    atau</div>
                <div class="col-sm-5">
                    <hr>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Templat Penolakan</label>
                <div class="col-sm-10" {{-- style="height:300px;max-height:350px;overflow:auto;" --}}>
                    @forelse ($templates as $template)
                        <div class="col-sm-12">
                            <label class="checkbox-inline" data-html="true" data-toggle="tooltip" data-placement="right"
                                title="{{ $template->content }}">
                                <input type="checkbox" id="cb{{ $template->id }}" name="template"
                                    value="{{ $template->id }}"> {{ $template->title }}
                            </label>
                        </div>
                    @empty
                    <div class="col-sm-12">
                        -- Tiada templat ditemui. Sila tambah. --
                    </div>
                    @endforelse
                </div>
            </div>
        @endif
    </form>
</div>
