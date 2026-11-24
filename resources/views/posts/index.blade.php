<h1>Posts</h1>

@auth
    <a href="/posts/create">New post</a>
    <form method="POST" action="/logout">
        @csrf
        <button>Log out</button>
    </form>
@else
    <a href="/login">Log in</a>
@endauth

@foreach ($posts as $post)
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->body }}</p>

    @auth
        <a href="/posts/{{ $post->id }}/edit">Edit</a>
        <form method="POST" action="/posts/{{ $post->id }}">
            @csrf
            @method('DELETE')
            <button>Delete</button>
        </form>
    @endauth
@endforeach
