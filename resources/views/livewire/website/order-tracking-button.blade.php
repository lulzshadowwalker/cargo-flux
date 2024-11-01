<div>
    <button class="outline-none" onclick="tracking_modal.showModal()">Order Tracking</button>

    <dialog id="tracking_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h2 class="text-lg font-bold">Track Your Shipment</h2>
            <p class="py-4">Enter your unique order number below to get the latest updates on your shipment’s journey.</p>

            <form class="flex flex-col justify-center">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Order Number</span>
                    </div>

                    <input type="text" placeholder="e.g., ORDER-1234567890" class="input input-bordered w-full" required />
                </label>

                <button class="btn btn-primary ms-auto mt-6">Find My Shipment <i class="fa-solid fa-truck-fast"></i></button>
            </form>
        </div>
    </dialog>
</div>