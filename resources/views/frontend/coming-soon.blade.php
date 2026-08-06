@extends('layouts.app')

@section('title', 'Coming Soon - JMOR Connection')

@section('content')

    <section class="jm-coming-soon">
        <div class="container">
            <div class="jm-coming-soon__inner">
                <div class="jm-coming-soon__badge">
                    <i class="fa fa-wrench" aria-hidden="true"></i>
                </div>
                <div class="jm-eyebrow jm-coming-soon__eyebrow">Under Construction</div>
                <h1 class="jm-coming-soon__title">This page is coming soon.</h1>
                <p class="jm-coming-soon__copy">We're working hard to bring you this content. In the meantime, explore our IT
                    support plans or get in touch with our New Jersey team.</p>

                <div class="jm-coming-soon__actions">
                    <a href="{{ url()->previous() }}" class="jm-btn jm-btn--orange">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Go back
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
