<div class="nav">
  <ol>
    <li>{{ Html::linkAction('CarlogController@home','Strona główna') }}</li>
    <li>{{ Html::linkAction('CarlogController@AddCarView','Dodaj auto') }}</li>
    <li>+ Nowy wpis
      <ul>
        <li>{{ Html::linkAction('CarlogController@FuelView','Paliwo') }}</li>
        <li>{{ Html::linkAction('CarlogController@ServiceView','Serwis') }}</li>
        <li>{{ Html::linkAction('CarlogController@ExpenseView','Inne wydatki') }}</li>
        <li>{{ Html::linkAction('CarlogController@ReminderView','Przypomnienie') }}</li>
      </ul>
    </li>
    <li><a href="#">Statystyki</a></li>
    <li>{{ Html::linkAction('CarlogController@logout','Wyloguj mnie') }}</li>
  </ol>
</div>
