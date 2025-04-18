<div class="pj-photo-uploader">
    <x-pjutils::form.file {{ $attributes->merge(['name' => $name, 'multiple' => $multiple]) }} />

    <div class="previews">
        @foreach($value as $preview)
            <x-pjutils::file-uploader.photo-preview :image="$preview" />
        @endforeach
    </div>
</div>