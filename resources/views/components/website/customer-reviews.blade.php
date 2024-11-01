    <section class="overflow-hidden">
        <div class="max-w-screen-lg mx-auto">
            <div>
                <h2 class="text-4xl font-semibold mb-2">What our customers say about us</h2>
                <p class="leading-wide">Don't just take our word for it. Here's what our customers have to say about us.</p>
            </div>

            <!-- Avatar group -->
            <div class="avatar-group -space-x-6 rtl:space-x-reverse my-12">
                <div class="avatar">
                    <div class="w-12">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                    </div>
                </div>
                <div class="avatar">
                    <div class="w-12">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                    </div>
                </div>
                <div class="avatar">
                    <div class="w-12">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                    </div>
                </div>
                <div class="avatar placeholder">
                    <div class="bg-neutral text-neutral-content w-12">
                        <span>+99</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="marquee">
            <div class="flex gap-4">
                @foreach(range(1, 10) as $i)
                <div class="card bg-base-100 w-96 shadow-xl my-8">
                    <div class="card-body">
                        <h2 class="card-title">Great experience!</h2>

                        <div class="rating rating-sm">
                            <input type="radio" name="rating-7" class="mask mask-star-2 bg-orange-400" />
                            <input
                                type="radio"
                                name="rating-7"
                                class="mask mask-star-2 bg-orange-400"
                                checked="checked" />
                            <input type="radio" name="rating-7" class="mask mask-star-2 bg-orange-400" />
                            <input type="radio" name="rating-7" class="mask mask-star-2 bg-orange-400" />
                            <input type="radio" name="rating-7" class="mask mask-star-2 bg-orange-400" />
                        </div>

                        <p class="text-neutral-500 tracking-wide">It was a great experience working with this company. They were very professional and delivered my goods on time.</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <script type="module">
        import marquee from 'https://cdn.jsdelivr.net/npm/vanilla-marquee/dist/vanilla-marquee.js';
        new marquee(document.getElementById('marquee')), {
            speed: 1,
            pauseOnHover: true,
        };
    </script>