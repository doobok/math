<section class="uk-section uk-section-secondary">
  <div class="uk-container uk-container-large">
    <div class="uk-text-center uk-margin-medium-bottom">
      <h2 class="uk-heading-xlarge">Что именно вы ищете?</h2>
      <span class="uk-heading-large"><mark>Выбирайте направление и начнем</mark></span>
    </div>

    <div class="uk-child-width-1-2@m" uk-grid>
      <div class="uk-text-center">

        <button-course-component
        title="репетитор по математике для начальных классов"
        ></button-course-component>

        @for ($i=5; $i < 12; $i++)
          <button-course-component
          title="репетитор по математике для {{$i}} класса"
          ></button-course-component>
        @endfor

      </div>

      <div class="">

        <button-course-component
        title="Подготовка к олимпиаде с математики"
        ></button-course-component>

        <button-course-component
        title="Подготовка к ДПА Математика"
        ></button-course-component>

        <button-course-component
        title="Подготовка к ЗНО Математика"
        discount="10"
        ></button-course-component>

        <button-course-component
        title="Онлайн занятия по математике"
        discount="10"
        ></button-course-component>

        <p>Для кого-то более привычными являются занятия в коллективе. Мы предоставляем и такую возможность. Занятия в группах до 4-х человек - они вам действительно понравятся. А еще это не плохой способ сэкономить*</p>

        <button-course-component
        title="Груповые занятия по математике"
        clases="uk-button-large"
        ></button-course-component>

        <div class="uk-flex-middle uk-margin-remove-top" uk-grid>
          <div class="uk-width-expand">
            <p class="uk-text-meta uk-text-right">* скидка на груповые занятия оформленные на сайте</p>
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
