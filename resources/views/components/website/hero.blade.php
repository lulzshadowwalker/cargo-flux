 <section
     class="hero min-h-screen"
     style="background-image: url({{ asset('images/home-cover.webp') }})">
     <div class="hero-overlay bg-opacity-60"></div>
     <div class="hero-content text-neutral-content">
         <div class="max-w-screen-md text-balance text-center self-start">
             <h1 class="mb-5 text-6xl font-bold">{{ __('website/home.seamless-shipping') }}</h1>
             <p class="mb-5 tracking-wide">
                 {{ __('website/home.fast-transparent-reliable') }}
             </p>
             <button class="btn btn-primary">{{ __('website/home.get-started') }} <i class="fa-solid fa-arrow-right rtl:fa-arrow-left"></i></button>
         </div>
     </div>
 </section>
