
<div class="image-gallery">
    @foreach ($this->getImages() as $image)
        <div class="image-item">
            <img src="{{ asset($image) }}" alt="Image" class="image">
        </div>
    @endforeach
</div>

<style>
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.image-item {
    flex: 1 0 21%; /* Ajusta esto según tus necesidades */
    box-sizing: border-box;
}

.image {
    width: 100%;
    height: auto;
    display: block;
}
</style>