<div class="{{ $openModal ? '' : 'hidden' }} relative z-10"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <!--
      Background backdrop, show/hide based on modal state.
  
      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!--
          Modal panel, show/hide based on modal state.
  
          Entering: "ease-out duration-300"
            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            To: "opacity-100 translate-y-0 sm:scale-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100 translate-y-0 sm:scale-100"
            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        -->
            <div class="relative w-fit transform overflow-hidden bg-white text-left shadow-xl transition-all">
                @if (isset($header))
                    <div class="font-bold modal-header flex items-center bg-gray-100 py-2 px-2">
                        {{ $header }}
                    </div>
                @endif
                <div class="modal-body p-2">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
