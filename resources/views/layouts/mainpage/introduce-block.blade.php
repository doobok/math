<section class="uk-section uk-section-muted">

  <div class="uk-container uk-container-large">
    <div class="uk-flex uk-flex-middle" uk-grid>
        <div class="uk-width-2-3@m">
            <h2 class="uk-heading-xlarge">@lang('site.introduce-h2')</h2>

            {{-- large image --}}
            <div class="uk-width-1-3@m uk-text-center uk-hidden@m">
                <img data-src="/tutor1.jpg" alt="@lang('site.introduce-img-alt')" uk-img>
            </div>

            <p class="uk-heading-large">@lang('site.introduce-my-name')</p>

            <p class="uk-text-large">@lang('site.introduce-my-bio')</p>

            <span class="uk-heading-large uk-text-center"><mark>@lang('site.introduce-mark')</mark></span>

            <ul class="uk-list uk-list-bullet uk-margin-medium" style="font-weight: 500;">
              <li>@lang('site.introduce-list-1')</li>
              <li>@lang('site.introduce-list-2')</li>
              <li>@lang('site.introduce-list-3')</li>
              <li>@lang('site.introduce-list-4')</li>
              <li>@lang('site.introduce-list-5')</li>
            </ul>

            <p class="uk-text-meta">*@lang('site.introduce-demotivator')</p>

            <div class="uk-text-center uk-margin-medium-top">

              <button-component
              title="@lang('site.introduce-button')"
              clases="uk-width-1-1 uk-width-2-3@s"
              ></button-component>

            </div>
        </div>
        {{-- large image --}}
        <div class="uk-width-1-3@m uk-visible@m">
            <img data-src="/tutor1.jpg" alt="@lang('site.introduce-img-alt')" uk-img>
        </div>
    </div>
  </div>
</section>
