@if ($module_slug && $module_ids)
<div class="form-group" id="rooms">
    <label>Rooms <span style="color:red;">*</span></label>
    <select class="form-control" name="room">
            @foreach($rooms as $room)
                <option value="{{ $room->id }}" @if ($room->id == $module_ids) selected="selected" @endif>{{ $room->name }}</option>
            @endforeach
    </select>
</div>
@else
<div class="form-group" id="rooms">
    <label>Rooms <span style="color:red;">*</span></label>
    <select class="form-control" name="room">
            @foreach($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }}</option>
            @endforeach
    </select>
</div>
@endif