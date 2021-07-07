@extends('layouts.app')

@section('content')
<div id="pusher-data" pusher-key="{{ config('broadcasting.connections.pusher.key') }}" pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster') }}"></div>
<div id="app"></div>
@endsection
