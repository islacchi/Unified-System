@extends('layouts.app')

@section('content')
    @livewire('procurement-form', ['procurementId' => $procurement->id])
@endsection