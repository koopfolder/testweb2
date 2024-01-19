@if ($module_slug && $module_ids)
<div class="form-group" id="room-categories">
    <label>Room Category <span style="color:red;">*</span></label>
    <select class="form-control" name="room_category">
            @foreach($room_categories as $room_category)
                <option value="{{ $room_category->id }}" @if ($room_category->id == $module_ids) selected="selected" @endif>{{ $room_category->name }}</option>
            @endforeach
    </select>
</div>
@else
<div class="form-group" id="room-categories">
    <label>Room Category <span style="color:red;">*</span></label>
    <select class="form-control" name="room_category">
            @foreach($room_categories as $room_category)
                <option value="{{ $room_category->id }}">{{ $room_category->name }}</option>
            @endforeach
    </select>
</div>
@endif