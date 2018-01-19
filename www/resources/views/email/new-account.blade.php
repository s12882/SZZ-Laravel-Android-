<!DOCTYPE html>
<html lang="en">
<head>
	<title>Bootstrap Example</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		div.bg { background: linear-gradient(#404040, #393939, #333333, #262626, #202020, #1a1a1a);}
		div.main {
			margin-left:25%;
			margin-right:25%;
			color:white;
			font-size:15px;
			font-family: Verdana;}
		div.stopka {
			font-size:12px;
			color:#d9d9d9;
			text-align: center;}
		.button{
			display:inline-block;
			font-weight:400;
			line-height:1.5;
			text-align:center;
			cursor:pointer;
			border:1px solid #538312;
			text-decoration:none;
			border-radius:4px;
			color: #e8f0de;
			background: linear-gradient(#7db72f, #4e7d0e);
			padding:10px 16px;
			font-size:18px;
			width:100%;
		}
		.button:hover {
			background: #538018;
			background: linear-gradient(#6b9d28, #436b0c);
		}
		.button:active {
			color: #a9c08c;
			background: linear-gradient(#4e7d0e, #7db72f);
		}

	</style>
</head>
<body>
	<div class="bg">
		<div class="main">
			<h2>{{$user->first_name}}, witaj w naszym systemie!</h2>
		
			<p> Podczas zakładania Twojego konta podano następujące dane:</p>
		
			<ul><li>Imie: {{$user->first_name}}</li>
			<li>Nazwisko: {{$user->surname}}</li>
			<li>Numer telefonu: {{$user->phoneNumber}}</li>
			<li>Adres E-mail: {{$user->email}}</li></ul>

			<p>Dzięki naszemu systemowi, w zależności od przypisanych uprawnień, jest możliwe:</p>
			<ul><li>Wyświetlanie listy działów i ich modyfikacja</li>
			<li>Zarządzanie stanem magazynowym</li>
			<li>Dodawanie zadań i przypisywanie ich do pracowników</li>
			<li>Zarządzanie listą pracowników</li>
			<li>Sporządzanie i przeglądanie raportów z zadań</li></ul>
		
			<p>Twoje konto jest od teraz aktywne i możesz już się logować, korzystając z poniższych danych:</p>
		
			<ul><li>Login: {{$user->login}}</li>
			<li>Hasło: {{$password}}</li></ul>
			
			<a href="{{$loginURL}}" class="button">Zaloguj się</a>
		
			<p>Zmiana hasła jest możliwa na stronie edycji profilu.</p>
		
			<p>Dziękujemy za skorzystanie z naszego systemu. Mamy nadzieję, że praca z nim będzie owocna i przyjemna.</p><br><br>

		<div class="stopka">Wiadomość wygenerowana automatycznie. Prosimy na nią nie odpowiadać.</div>

	</div>
</div>

</body>
</html>