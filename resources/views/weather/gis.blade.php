@extends('layouts.gis')

@section('content')
<div id="app" class="main-page container-fluid">
    <div class="container-width">
        <gis-weather-component :data='{!! json_encode($data) !!}'></gis-weather-component>        
    </div>
</div>
@endsection