@extends('layouts.app')

@section('content')
    <section class="hero is-primary page-header">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    Your Comments
                </h1>
                <h2 class="subtitle">
                    {{--You need to login with your 500px account to use the app.--}}
                </h2>
            </div>
        </div>
    </section>

    <section class="container">
        <photos-grid></photos-grid>
    </section>
@endsection
