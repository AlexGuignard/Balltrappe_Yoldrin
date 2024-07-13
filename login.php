<div>
	<form target="/index.php" method="post" class="main_form">
		<h1>Connexion</h1>
		<div><label>Nom d'utilisateur</label><input type="text" placeholder="Login" class="login" name="login" autocomplete="username"></input></div>
		<div><label>Mot de passe</label><input type="password" placeholder="***********" class="psswd" name="psswd" autocomplete="current-password"></input></div>
		<div><img src="./images/target_icon.png" onClick="send_form(0)"></img></div>
	</form>
</div>


<div>
	<form target="/index.php" method="post" class="main_form form2">
		<h1>Inscription</h1>
		<div><label>Email</label><input type="mail" placeholder="Email" class="email" name="email" autocomplete="email"></input></div>
		<div><label>Login</label><input type="text" placeholder="Nom d'utilisateur" class="login" name="login" autocomplete="username"></input></div>
		<div><label>Mot de passe</label><input type="password" placeholder="***********" class="psswd" name="psswd" autocomplete="current-password"></input></div>
		<div><img src="./images/target_icon.png" onClick="send_form(1)"></img></div>
	</form>
</div>


<script>
	function send_form(x){
		let a = document.getElementsByClassName("psswd")[x].value;
		let b = "tobeencrypted";
		strHash(a,"SHA-256").then(hashed=>{
			document.getElementsByClassName("psswd")[x].value = hashed;
		});

		setTimeout(()=>{document.forms[x].submit()},100);

	}
	if(document.getElementsByClassName("issue_popup").length != 0){
	    document.getElementsByClassName("issue_popup")[0].showModal()
    	setTimeout(()=>{
    		document.getElementsByClassName("issue_popup")[0].close()
    	},2000)
	}
</script>