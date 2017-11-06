<div class="nav">
  <ol>
    <li>{{ Html::linkAction('CarlogController@home','Strona główna') }}</li>
    <li>{{ Html::linkAction('CarlogController@AddCarView','Dodaj auto') }}</li>
    <li>+ Nowy wpis
      <ul>
        <li><a href="#">Paliwo</a></li>
        <li><a href="#">Serwis</a></li>
        <li><a href="#">Inne wydatki</a></li>
        <li><a href="#">Przypomnienia</a></li>
      </ul>
    </li>
    <li><a href="#">Statystyki</a></li>
    <li>{{ Html::linkAction('CarlogController@logout','Wyloguj mnie') }}</li>
  </ol>
</div>
