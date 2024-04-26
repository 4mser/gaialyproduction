<section>
    {{-- @if (!empty($freeTrialExpiredAt))
        @if ($freeTrialExpiredAt->isPast())
            <div class="flex items-center bg-red-200 px-2 py-1 text-xs font-bold text-red-600" role="alert">
                <p>
                    Warning! Your license has expired {{ $freeTrialExpiredAt->format('F d, Y') }}, please renew & update your license.
                    When your license has expired you will not be able to full access data.
                </p>
            </div>
        @else
            <div class="flex items-center bg-blue-100 px-2 py-1 text-xs font-bold text-blue-400" role="alert">
                <p>
                    Attention! Your TRIAL license will expire {{ $freeTrialExpiredAt->format('F d, Y') }}, please renew & update your license.
                    When your license has expired you will not be able to full access data.
                </p>
            </div>
        @endif
    @endif --}}
</section>
