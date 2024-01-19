@if ($module_slug && $module_ids)
<?php
$ids = explode(', ', $module_ids);
$idsFlip = array_flip($ids);
?>
<div class="form-group" id="restaurants">
    <label>Restaurants <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="restaurants[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Restaurants"
            style="width: 100%;">
            @foreach($restaurants as $restaurant)
                <option value="{{ $restaurant->id }}"         
                <?php if(array_key_exists($restaurant->id, $idsFlip)): ?>
                    selected="selected"
                <?php endif; ?>
                >{{ $restaurant->name }}</option>
            @endforeach
    </select>
</div>
@else
<div class="form-group" id="restaurants">
    <label>Restaurants <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="restaurants[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Restaurants"
            style="width: 100%;">
            @foreach($restaurants as $restaurant)
                <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
            @endforeach
    </select>
</div>
@endif