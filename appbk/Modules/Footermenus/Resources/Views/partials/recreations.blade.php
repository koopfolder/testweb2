@if ($module_slug && $module_ids)
<?php
$ids = explode(', ', $module_ids);
$idsFlip = array_flip($ids);
?>
<div class="form-group" id="recreations">
    <label>Leisure & Recreations <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="recreations[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Recreations"
            style="width: 100%;">
            @foreach($recreations as $recreation)
                <option value="{{ $recreation->id }}"         
                <?php if(array_key_exists($recreation->id, $idsFlip)): ?>
                	selected="selected"
                <?php endif; ?>
                >{{ $recreation->name }}</option>
            @endforeach
    </select>
</div>
@else
<div class="form-group" id="recreations">
    <label>Leisure & Recreations <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="recreations[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Recreations"
            style="width: 100%;">
            @foreach($recreations as $recreation)
                <option value="{{ $recreation->id }}">{{ $recreation->name }}</option>
            @endforeach
    </select>
</div>
@endif