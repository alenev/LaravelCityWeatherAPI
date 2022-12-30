@extends('layouts/mainVUE')

@section('content')
<div id="app">
<login-form></login-form> 
</div>
<script src="{{ mix('js/app.js') }}" defer></script>

@endsection