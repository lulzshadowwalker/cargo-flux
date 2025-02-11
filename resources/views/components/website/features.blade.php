<section class="max-w-screen-lg mx-auto flex flex-col md:flex-row justify-between gap-4 my-16 md:my-28 px-8">

    <!-- Accordion -->
    <div class="flex-grow basis-0">
        <div class="badge badge-ghost uppercase font-bold py-5 px-6 mb-4">🔥 {{ __('website/features.best-features') }}</div>

        <h2 class="text-4xl font-semibold mb-8">{{ __('website/features.heading') }}</h2>

        <div class="space-y-2">
            <div class="collapse collapse-arrow bg-base-200">
                <input type="radio" name="feature-accordion" checked="checked" />
                <div class="collapse-title text-xl font-medium">{{ __('website/features.real-time-tracking.title') }}</div>
                <div class="collapse-content">
                    <p>{{ __('website/features.real-time-tracking.description') }}</p>
                </div>
            </div>

            <div class="collapse collapse-arrow bg-base-200">
                <input type="radio" name="feature-accordion" />
                <div class="collapse-title text-xl font-medium">{{ __('website/features.transparency.title') }}</div>
                <div class="collapse-content">
                    <p>{{ __('website/features.transparency.description') }}</p>
                </div>
            </div>

            <div class="collapse collapse-arrow bg-base-200">
                <input type="radio" name="feature-accordion" />
                <div class="collapse-title text-xl font-medium">{{ __('website/features.support.title') }}</div>
                <div class="collapse-content">
                    <p>{{ __('website/features.support.description') }}</p>
                </div>
            </div>

            <div class="collapse collapse-arrow bg-base-200">
                <input type="radio" name="feature-accordion" />
                <div class="collapse-title text-xl font-medium">{{ __('website/features.fleet.title') }}</div>
                <div class="collapse-content">
                    <p>{{ __('website/features.fleet.description') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-neutral-100 flex-grow basis-0 rounded-box relative hidden md:block">
        <!-- Mockup phone -->
        <div class="mockup-phone shadow-xl absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2">
            <div class="camera"></div>
            <div class="display">
                <div class="artboard artboard-demo phone-1">
                    Hi.
                </div>
            </div>
        </div>
    </div>
</section>
