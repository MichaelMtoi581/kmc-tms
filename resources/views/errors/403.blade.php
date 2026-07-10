@extends('adminlte::master')

@section('title', '403 Forbidden')

@section('adminlte_css')
    <style>
        .error-page { display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #f4f6f9; }
        .error-box { text-align: center; padding: 2rem; max-width: 500px; }
        .error-code { font-size: 6rem; font-weight: 700; color: #dc3545; line-height: 1; margin-bottom: 1rem; }
        .error-icon { font-size: 3rem; color: #dc3545; margin-bottom: 1rem; }
        .error-heading { font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; color: #343a40; }
        .error-text { color: #6c757d; margin-bottom: 1.5rem; }
        .error-text strong { color: #dc3545; }
    </style>
@endsection

@section('body')
    <div class="error-page">
        <div class="error-box">
            <div class="error-icon"><i class="fas fa-lock"></i></div>
            <div class="error-code">403</div>
            <div class="error-heading">Access Denied</div>
            <p class="error-text">
                You do not have permission to access this page.
                @auth
                    Your role (<strong>{{ auth()->user()->role }}</strong>) does not have the required privileges.
                @endauth
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home mr-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
@endsection
