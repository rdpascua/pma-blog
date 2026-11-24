<h1>Edit post</h1>

<form method="POST" action="/posts/{{ $post->id }}">
    @csrf
    @method('PUT')

    <input name="title" value="{{ $post->title }}">
    @error('title')
        <p>{{ $message }}</p>
    @enderror

    <textarea name="body">{{ $post->body }}</textarea>
    @error('body')
        <p>{{ $message }}</p>
    @enderror

    <button>Update</button>
</form>
