<h1>New post</h1>

<form method="POST" action="/posts">
    @csrf

    <input name="title" value="{{ old('title') }}">
    @error('title')
        <p>{{ $message }}</p>
    @enderror

    <textarea name="body">{{ old('body') }}</textarea>
    @error('body')
        <p>{{ $message }}</p>
    @enderror

    <button>Save</button>
</form>
