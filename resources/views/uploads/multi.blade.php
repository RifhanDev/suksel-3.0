<div id="uploader" class="dropzone card" data-class="{{$class}}" data-id="{{@$id}}" data-size="{{$size}}" data-type="{{$type}}" data-files="{{@$files}}"></div>
{{App\Libraries\Asset::push('js', 'upload')}}
