@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div style="width: 100%; max-width: 450px; padding: 20px;">
        <div style="background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); padding: 40px; text-align: center;">
            <h1 style="margin: 0 0 10px; color: #333; font-size: 28px;">Verify Your Email</h1>
            <p style="color: #666; margin-bottom: 30px;">Enter the verification code sent to your email</p>
            
            @if ($errors->any())
                <div style="background-color: #fee; border: 1px solid #fcc; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #c33;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            @if (session('success'))
                <div style="background-color: #efe; border: 1px solid #cfc; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #3c3;">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('verify-email.post') }}" method="POST" style="margin-top: 30px;">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <input 
                        type="text" 
                        name="verification_code" 
                        placeholder="Enter 6-digit code" 
                        maxlength="6"
                        required
                        style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 18px; text-align: center; letter-spacing: 2px; font-family: monospace; box-sizing: border-box; transition: border-color 0.3s;"
                        onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                        onfocus="this.style.borderColor = '#667eea'"
                        onblur="this.style.borderColor = '#ddd'"
                    >
                    @error('verification_code')
                        <div style="color: #c33; font-size: 14px; margin-top: 8px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: opacity 0.3s;">
                    Verify Email
                </button>
            </form>
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p style="color: #666; font-size: 14px; margin: 0;">Didn't receive the code? Check your spam folder or</p>
                <form action="{{ route('resend-verification-code') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #667eea; cursor: pointer; text-decoration: underline; font-size: 14px; padding: 0; margin: 0;">
                        request a new code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
