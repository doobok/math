<section class="uk-section uk-section-secondary">
  <div class="uk-container uk-container-large">
    <div class="uk-text-center uk-margin-medium-bottom">
      <h2 class="uk-heading-xlarge">@lang('site.courses-h2')</h2>
      <span class="uk-heading-large"><mark>@lang('site.courses-mark')</mark></span>
    </div>

    <div class="uk-child-width-1-2@m" uk-grid>
      <div class="uk-text-center">

        <button-course-component
        title="@lang('site.courses-elementary')"
        ></button-course-component>

        @for ($i=5; $i < 12; $i++)
          <button-course-component
          title="{{__('site.courses-for-class', ['clas' => $i])}}"
          ></button-course-component>
        @endfor

      </div>

      <div>

        <button-course-component
        title="@lang('site.courses-olympiad')"
        ></button-course-component>

        <button-course-component
        title="@lang('site.courses-dpa')"
        ></button-course-component>

        <button-course-component
        title="@lang('site.courses-zno')"
        discount="10"
        ></button-course-component>

        <button-course-component
        title="@lang('site.courses-online')"
        discount="10"
        ></button-course-component>

        <p>@lang('site.courses-group-desc')*</p>

        <button-course-component
        title="@lang('site.courses-group')"
        clases="uk-button-large"
        ></button-course-component>

        <div class="uk-flex-middle uk-margin-remove-top" uk-grid>
          <div class="uk-width-expand">
            <p class="uk-text-meta uk-text-right">* @lang('site.courses-group-discount')</p>
          </div>
          <div class="uk-width-small ms-star-m">
            <span><i class="fas fa-certificate uk-text-warning"></i></span>
            <p>до 30%</p>
          </div>
        </div>

      </div>



    </div>

  </div>
</section>
