<div class="content">
    <div class="header">
        <h2 class="title">@lang('general.all_data')</h2>
        <x-close-button />
    </div>
    <div class="expanded-data">
        @foreach($data as $key => $value)
            <h4>{{ $key }}</h4>
            <p>{{ $value }}</p>
        @endforeach
    </div>
</div>