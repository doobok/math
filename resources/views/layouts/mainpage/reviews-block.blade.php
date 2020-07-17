<section class="uk-section uk-section-primary">
  <div class="uk-container uk-container-large">

    <div class="uk-text-center uk-margin-medium-bottom">
      <h2 class="uk-heading-xlarge" uk-scrollspy="cls: uk-animation-fade">@lang('site.reviews-h2')</h2>
      <span class="uk-heading-large" uk-scrollspy="cls: uk-animation-fade; delay: 100"><mark>@lang('site.reviews-mark')</mark></span>
    </div>

    <div data-uk-slider="velocity: 5; autoplay: true" class="uk-slider" uk-scrollspy="cls: uk-animation-fade; delay: 200">
  			<div class="uk-position-relative">
  				<div class="uk-slider-container">
  					<ul class="uk-slider-items uk-child-width-1-2@m uk-child-width-1-3@l  news-slide">

    					@include('components.reviews')

  					</ul>
  				</div>
  				<div class="uk-visible@l">
  					<a class="uk-position-center-left-out uk-position-small uk-slidenav-previous uk-icon uk-slidenav" href="#" data-uk-slidenav-previous data-uk-slider-item="previous"></a>
  					<a class="uk-position-center-right-out uk-position-small uk-slidenav-next uk-icon uk-slidenav" href="#" data-uk-slidenav-next data-uk-slider-item="next"></a>
  				</div>
  			</div>
        <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
  		</div>

      {{-- Star rating --}}
      <div class="uk-h4 uk-text-muted uk-text-center uk-margin-medium-top">@lang('site.set-rating')</div>
      <div class="uk-container uk-flex uk-flex-center">
        <star-rating
        itemid="1"
        model="main"
        ></star-rating>
      </div>


  </div>
</section>
