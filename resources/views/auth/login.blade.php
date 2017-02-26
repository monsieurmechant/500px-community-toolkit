@extends('layouts.app')

@section('content')
    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    Login with your 500px Account
                </h1>
                <h2 class="subtitle">
                    You need to login with your 500px account to use the app.
                </h2>
            </div>
        </div>
    </section>
    <section class="hero is-large">
        <div class="hero-body">
            <div class="container has-text-centered">
                <a class="button is-large is-outlined">
                    <span class="icon is-medium">
                      <i class="fa fa-500px"></i>
                    </span>
                    <span>Login With 500px</span>
                </a>
            </div>
        </div>
    </section>
@endsection
