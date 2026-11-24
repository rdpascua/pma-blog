<h1>Log in</h1>

<form method="POST" action="/login">
    @csrf

    <input name="email" type="email" value="{{ old('email') }}">
    @error('email')
        <p>{{ $message }}</p>
    @enderror

    <input name="password" type="password">
    @error('password')
        <p>{{ $message }}</p>
    @enderror

    <button>Log in</button>
</form>
