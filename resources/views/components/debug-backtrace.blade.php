<div {{ $attributes->merge(['class' => 'pj-debug-backtrace']) }}>
    <div class="pj-debug-backtrace-header">
        <p class="pj-debug-backtrace-title">{{ $title ?? 'Stack Trace' }}</p>
        @if($message !== null)
            <p class="pj-debug-backtrace-message">{{ $message }}</p>
        @endif
    </div>

    @if($normalizedLines !== null)
        <div class="pj-debug-backtrace-frames">
            @foreach($normalizedLines as $line)
                <div class="pj-debug-backtrace-frame {{ $line['is_vendor'] ? 'vendor' : '' }}">
                    <div class="pj-frame-number">#{{ $line['number'] }}</div>
                    <div class="pj-frame-content">
                        <div class="pj-frame-file">{{ $line['text'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($trace !== null)
        <pre class="pj-debug-backtrace-raw">{{ $trace }}</pre>
    @else
        <div class="pj-debug-backtrace-frames">
            @foreach($normalizedFrames as $frame)
                <div class="pj-debug-backtrace-frame {{ $frame['is_vendor'] ? 'vendor' : '' }}">
                    <div class="pj-frame-number">#{{ $frame['number'] }}</div>
                    <div class="pj-frame-content">
                        <div class="pj-frame-file">
                            {{ $frame['file'] }}@if($frame['line'] !== null):<span class="pj-frame-line">{{ $frame['line'] }}</span>@endif
                        </div>
                        @if($frame['callable'] !== '')
                            <div class="pj-frame-callable">{{ $frame['callable'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
