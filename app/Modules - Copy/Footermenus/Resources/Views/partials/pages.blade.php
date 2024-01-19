@if ($module_slug && $module_ids)
<?php
$ids = explode(', ', $module_ids);
$idsFlip = array_flip($ids);
?>
<div class="form-group" id="pages">
    <label>Single Page <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="pages[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Single Page"
            style="width: 100%;">
            @foreach($idsFlip as $key => $value)
                <?php $post = \App\Post::find($key); ?>
                @if ($post)
                    <option value="{{ $post->id }}" selected="selected">{{ $post->title }}</option>
                @endif
            @endforeach
            @foreach($pages as $page)
                @if( ! array_key_exists($page->id, $idsFlip))
                <option value="{{ $page->id }}">{{ $page->title }}</option>
                @endif
            @endforeach
    </select>
</div>
@else
<div class="form-group" id="pages">
    <label>Single Page <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="pages[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Single Page"
            style="width: 100%;">
            @foreach($pages as $page)
                <option value="{{ $page->id }}">{{ $page->title }}</option>
            @endforeach
    </select>
</div>
@endif