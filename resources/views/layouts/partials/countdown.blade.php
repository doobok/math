<div class="uk-grid-small uk-child-width-auto" uk-grid uk-countdown="date: {{$promo_time}}">
    <div>
        <div class="uk-countdown-number uk-countdown-days"></div>
        <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">@lang('site.days')</div>
    </div>
    <div class="uk-countdown-separator">:</div>
    <div>
        <div class="uk-countdown-number uk-countdown-hours"></div>
        <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">@lang('site.hours')</div>
    </div>
    <div class="uk-countdown-separator">:</div>
    <div>
        <div class="uk-countdown-number uk-countdown-minutes"></div>
        <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">@lang('site.minutes')</div>
    </div>
    <div class="uk-countdown-separator">:</div>
    <div>
        <div class="uk-countdown-number uk-countdown-seconds"></div>
        <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">@lang('site.seconds')</div>
    </div>
</div>
