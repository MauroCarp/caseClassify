
    @if ($urls)

        @foreach($urls as $image)

            <div style="display: grid;place-items: center;">

                <img src="/storage/{{ $image }}" alt="Image" width="650px">

            </div>

        @endforeach

    @else

        <p>No image available</p>

    @endif

