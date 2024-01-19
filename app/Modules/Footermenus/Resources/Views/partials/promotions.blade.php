@if ($module_slug && $module_ids)
<?php
$ids = explode(', ', $module_ids);
$idsFlip = array_flip($ids);
?>
<div class="form-group" id="promotions">
    <label>Promotions <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="promotions[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Promotions"
            style="width: 100%;">
            @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}"         
                <?php if(array_key_exists($promotion->id, $idsFlip)): ?>
                    selected="selected"
                <?php endif; ?>
                >{{ $promotion->name }}</option>
            @endforeach              
    </select>
</div>
@else
<div class="form-group" id="promotions">
    <label>Promotions <span style="color:red;">*</span></label>
    <select class="form-control select2"
            name="promotions[]"
            multiple="multiple"
            data-select2order="false"
            data-placeholder="Select Promotions"
            style="width: 100%;">
            @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
            @endforeach
    </select>
</div>
@endif