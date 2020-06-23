<section class="uk-section uk-section-secondary">

  <div class="uk-container uk-container-large">
    <div class="uk-flex uk-flex-middle" uk-grid>
        <div class="uk-width-2-3@m">
            <h1 class="uk-heading-xlarge">@lang('site.h1')</h1>

            {{-- small imade --}}
            <div class="uk-width-1-3@m uk-text-center uk-hidden@m">
                <img data-src="/math_main.png" alt="@lang('site.h1')" width="400px" uk-img>
            </div>

              <ul class="uk-list uk-list-bullet uk-margin-medium" style="font-weight: 500;">
                <li>@lang('site.main-list-1')</li>
                <li>@lang('site.main-list-2')</li>
                <li>@lang('site.main-list-3')</li>
                <li>@lang('site.main-list-4')</li>
              </ul>


            <span class="uk-heading-large"><mark>@lang('site.main-mark')</mark></span>

            <div class="uk-child-width-1-2 uk-margin-small-top" uk-grid>
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

            <div class="uk-flex-middle uk-margin-remove-top" uk-grid>
              <div class="uk-width-expand">
                <p class="uk-text-meta uk-text-right">* @lang('site.main-get-discount')</p>
              </div>
              <div class="uk-width-small ms-star-s">
                <span><i class="fas fa-certificate uk-text-warning"></i></span>
                <p>-10%</p>
              </div>
            </div>


            <div class="uk-text-center uk-margin-medium-top">

              <button-component
              title="@lang('site.main-button')"
              clases="uk-width-1-1 uk-width-2-3@s"
              ></button-component>

            </div>
        </div>
        {{-- large image --}}
        <div class="uk-width-1-3@m uk-visible@m">
            <img data-src="/math_main.png" alt="@lang('site.h1')" uk-img>
        </div>
    </div>
  </div>
</section>
