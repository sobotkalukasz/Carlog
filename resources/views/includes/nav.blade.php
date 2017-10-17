<div class="nav">
  <ol>
    <li><a href="#">Strona główna</a></li>
    <li><a href="#">Moje auta</a></li>
    <li><a href="#">+ Nowy wpis</a>
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
