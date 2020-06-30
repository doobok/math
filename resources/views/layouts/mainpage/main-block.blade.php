<section class="uk-section uk-section-secondary">

  <div class="uk-container uk-container-large">
    <div class="uk-flex uk-flex-middle" uk-grid>
        <div class="uk-width-2-3@m">
            <h1 class="uk-heading-xlarge" uk-scrollspy="cls: uk-animation-fade">@lang('site.h1')</h1>

            {{-- small imade --}}
            <div class="uk-width-1-3@m uk-text-center uk-hidden@m" uk-scrollspy="cls: uk-animation-slide-right-small">
                <img data-src="/math_main.png" alt="@lang('site.h1')" width="400px" uk-img>
            </div>

              <ul class="uk-list uk-list-bullet uk-margin-medium" style="font-weight: 500;" uk-scrollspy="cls: uk-animation-fade; delay: 200">
                <li>@lang('site.main-list-1')</li>
                <li>@lang('site.main-list-2')</li>
                <li>@lang('site.main-list-3')</li>
                <li>@lang('site.main-list-4')</li>
              </ul>


            <span class="uk-heading-large"><mark uk-scrollspy="cls: uk-animation-fade; delay: 300">@lang('site.main-mark')</mark></span>

            <div class="uk-child-width-1-2 uk-margin-small-top" uk-scrollspy="cls: uk-animation-fade; delay: 450" uk-grid>
              <div>
                <p class="uk-heading-medium"> <span class="ms-price-bordered">@lang('site.main-price') <span class="ms-price-small">
                  @if ($options->has('price_1'))
                    {{$options->get('price_1')->value}}
                  @endif
                </span> грн*</span> </p>
              </div>
              <div>
                <p class="uk-heading-medium"> <span class="ms-price-bordered">@lang('site.main-price') <span class="ms-price-small">
                  @if ($options->has('price_2'))
                    {{$options->get('price_2')->value}}
                  @endif
                </span> грн*</span> </p>
              </div>
            </div>

            @if ($options->has('discount_percent'))
            <div class="uk-flex-middle uk-margin-remove-top" uk-scrollspy="cls: uk-animation-slide-left-medium; delay: 2000" uk-grid>
              <div class="uk-width-expand">
                <p class="uk-text-meta uk-text-right">* @lang('site.main-get-discount')</p>
              </div>
              <div class="uk-width-small ms-star-s">
                <span><i class="fas fa-certificate uk-text-warning"></i></span>
                <p>-{{$options->get('discount_percent')->value}}%</p>
              </div>
            </div>
            @endif


            <div class="uk-text-center uk-margin-medium-top" uk-scrollspy="cls: uk-animation-slide-bottom-small; delay: 500">

              <button-component
              title="@lang('site.main-button')"
              clases="uk-width-1-1 uk-width-2-3@s"
              ></button-component>

            </div>
        </div>
        {{-- large image --}}
        <div class="uk-width-1-3@m uk-visible@m" uk-scrollspy="cls: uk-animation-slide-right-small">
            <img data-src="/math_main.png" alt="@lang('site.h1')" uk-img>
        </div>
    </div>
  </div>
</section>
