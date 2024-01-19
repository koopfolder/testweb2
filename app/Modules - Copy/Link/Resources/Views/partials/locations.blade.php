@if ($moduleSlug && $moduleIds && $moduleSlug == 'location')
<?php
$itemModuleIds = array_flip(explode(', ', $moduleIds));
?>
<div class="form-group" id="locations">
    <label>Locations <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="locations[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Locations"
            style="width: 100%;">
            @foreach($locations as $key => $value)
                <option value="{{ $key }}"
                <?php if(array_key_exists($key, $itemModuleIds)): ?>
                    selected="selected"
                <?php endif; ?>
                >{{ $value }}</option>
            @endforeach            
    </select>
</div>
@else
<div class="form-group" id="locations">
    <label>Locations <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="locations[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Locations"
            style="width: 100%;">
            @foreach($locations as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
    </select>
</div>
@endif