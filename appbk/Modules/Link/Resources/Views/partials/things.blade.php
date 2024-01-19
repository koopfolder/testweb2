@if ($moduleSlug && $moduleIds && $moduleSlug == 'thing')
<?php
$itemModuleIds = array_flip(explode(', ', $moduleIds));
?>
<div class="form-group" id="things">
    <label>Things To Do <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="things[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Things To Do"
            style="width: 100%;">
            @foreach($things as $key => $value)
                <option value="{{ $key }}"
                <?php if(array_key_exists($key, $itemModuleIds)): ?>
                    selected="selected"
                <?php endif; ?>
                >{{ $value }}</option>
            @endforeach            
    </select>
</div>
@else
<div class="form-group" id="things">
    <label>Things To Do <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="things[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Things To Do"
            style="width: 100%;">
            @foreach($things as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
    </select>
</div>
@endif