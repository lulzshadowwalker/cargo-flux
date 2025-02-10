<div>
    <button class="outline-none" onclick="tracking_modal.showModal()">Order Tracking</button>

    <dialog id="tracking_modal" class="modal">
        <div class="modal-box">
            <form wire:submit.prevent="clear()">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h2 class="text-lg font-bold">Track Your Shipment</h2>
            <p class="py-4">Enter your unique order number below to get the latest updates on your shipment’s journey.</p>

            <form class="flex flex-col justify-center" wire:submit.prevent="trackOrder">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Order Number</span>
                    </div>

                    <input type="text" placeholder="e.g., ORDER-1234567890" class="input input-bordered w-full" required wire:model="orderNumber" />
                </label>

                <button class="btn btn-primary ms-auto mt-6">Find My Shipment <i class="fa-solid fa-truck-fast"></i></button>
            </form>
        </div>
    </dialog>

    <dialog id="stages_modal" class="modal modal-bottom sm:modal-middle overflow-visible">
        <div class="modal-box ">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <h2 class="text-lg font-bold">Your Shipment Progress</h2>


            <ul class="timeline timeline-vertical">
                @foreach ($stages ?? [] as $key => $stage)
                <li :key="$key">
                    @unless($key === 0)
                    <!--  note: `px-0` to prevent the lines between the steps to be displayed as a circle inside of a dialog for some reason -->
                    <hr @class([ '!px-0' , 'bg-primary'=> $stage['is_completed']]) />
                    @endunless

                    <div @class([ 'tooltip tooltip-top'=> (boolean) $stage['completed_at'], 'timeline-box' , 'timeline-start'=> $key % 2 === 0, 'timeline-end' => $key % 2 !== 0 ]) data-tip="{{ $stage['completed_at']?->diffForHumans() ?: '' }}">
                        {{ $stage['status']->label()  }}
                    </div>

                    <div class="timeline-middle">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            @class(['h-5 w-5', 'text-primary'=> $stage['is_completed']])>
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    @unless($key === count($stages) - 1)
                    <!--  note: `px-0` to prevent the lines between the steps to be displayed as a circle inside of a dialog for some reason -->
                    <hr @class([ '!px-0' , 'bg-primary'=> $stages[$key + 1]['is_completed']]) />
                    @endunless
                </li>
                @endforeach
            </ul>






            <form wire:submit.prevent="clear()">
                <button class="flex btn btn-primary ms-auto mt-6">Done <i class="fa-solid fa-circle-check"></i></button>
            </form>
        </div>
    </dialog>

    <style>
        /*  NOTE: Hides the annoying line to the side when displayed inside of a modal */
        .menu :where(li ul):before {
            display: none;
        }
    </style>
</div>
