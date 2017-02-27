@extends('layouts.app')

@section('content')
    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    Your 500px Community
                </h1>
                <h2 class="subtitle">
                    {{--You need to login with your 500px account to use the app.--}}
                </h2>
            </div>
        </div>
    </section>

    <section class="container">
        <top-followers></top-followers>
    </section>
@endsection
