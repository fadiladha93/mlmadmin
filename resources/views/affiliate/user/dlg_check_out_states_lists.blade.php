<option></option>
@foreach($states as $state)
    <option value="{{$state->name}}">{{$state->name}}</option>
@endforeach
