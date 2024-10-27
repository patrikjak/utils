<div class="pagination">
    <x-pjutils.table::page-size :pagination-settings="$paginationSettings" />

    <div class="links">
        @foreach($links as $link)
            <x-pjutils.table::pagination-item :pagination-settings="$paginationSettings" :$link />
        @endforeach
    </div>
</div>