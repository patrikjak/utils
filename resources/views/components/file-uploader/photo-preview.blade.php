<div class="pj-photo-preview">
    <img src="{{ $image->src }}" alt="{{ $image->alt }}" data-file-name="{{ $fileName }}">

    <div class="delete-button">
        <x-pjutils::close-button />
    </div>
</div>