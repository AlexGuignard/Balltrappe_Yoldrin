<form method="POST" target="?target=messages">
	<h3>Demande d'ami</h3>
	<label>ID de l'utilisateur<input type="text" name="f_id_req"> </label>
	<input type="submit" value="Envoyer demande">
</form>
<script>
	const current_user_name ="<?php echo (get_first_line_db($db, "SELECT login FROM users where id = '" . $_SESSION["user_id"] . "'")["login"]); ?>"

</script>
<?php
if (isset($_POST["f_id_req"])) {
	if (empty(get_first_line_db($db, "SELECT * FROM friend_requests where to_id = '" . $_POST["f_id_req"] . "'"))) {
		if (!empty(get_first_line_db($db, "SELECT * FROM users where msg_id = '" . $_POST["f_id_req"] . "'"))) {
			if (get_first_line_db($db, "SELECT msg_id FROM users where id = '" . $_SESSION["user_id"] . "'")["msg_id"] == $_POST["f_id_req"]) {
				echo ("<script>alert('Vous ne pouvez pas vous envoyer de demandes a vous meme')</script>");
			} else {
				get_first_line_db($db, "INSERT INTO friend_requests(from_id,to_id) VALUES('" . $_SESSION["user_id"] . "','" . $_POST["f_id_req"] . "')");
			}
		} else {
			echo ("<script>alert('Utilisateur non trouvé')</script>");
		}
	} else {
		echo ("<script>alert('Demande déja envoyée')</script>");
	}
}

$msg_id = get_first_line_db($db, "SELECT msg_id FROM users where id = '" . $_SESSION["user_id"] . "'")["msg_id"];

if (isset($_POST["accept_id"])) {
	get_first_line_db($db, "UPDATE friend_requests SET accepted = 1 where id = '" . $_POST["accept_id"] . "'");
	get_first_line_db($db, "INSERT INTO messages(friendship_id,content,sender_id) VALUES ('" . $_POST["accept_id"] . "','Bienvenue dans la discution','" . $_SESSION["user_id"] . "')");

}

if (isset($_POST["deny_id"])) {
	get_first_line_db($db, "DELETE FROM friend_requests  where id = '" . $_POST["deny_id"] . "'");
}

$result = get_all_lines_db($db, "SELECT u.login,f.id FROM users AS u INNER JOIN friend_requests AS f ON u.id = f.from_id WHERE f.to_id = '" . $msg_id . "' and f.accepted = false");

if (!empty($result)) {
	?>
	<h2>Liste des demandes</h2>
	<div>
		<table>
				<thead>
					<tr>
						<td>Nom</td>
						<td>Accepter</td>
						<td>Refuser</td>
					</tr>
				</thead>
	<?php
foreach ($result as $frequest) {
		?>
			<tr>
				<td><?=$frequest["login"]?></td>
				<td class="hover_button_icon" onclick="accept('<?=$frequest["id"]?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg></td>
				<td class="hover_button_icon" onclick="deny('<?=$frequest["id"]?>')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"/></svg></td>
			</tr>
	<?php
}
	?>
		</table>
	</div>
	<?="\n"?>
	<form hidden target="?target=messages" method ="POST">
		<input type="bool" name="deny" value="true">
		<input id="deny_id" type="text" name="deny_id">
		<input id="submit_deny" type="submit">
	</form>
	<form hidden target="?target=messages" method ="POST">
		<input type="bool" name="accept" value="true">
		<input id="accept_id" type="text" name="accept_id">
		<input id="submit_accept" type="submit">
	</form>

	<?php
}

?>
<script>
	let chats = []
	let user_messages = [];
	async function getChats(a){
		console.log('single_messages.php?id='+a);
	    await $(document).ready(function(){
	        $.ajax({
	            url: 'single_messages.php?id='+a,
	            type: 'GET',
	            dataType: 'json',
	            success: function(data) {
	                data.forEach(e =>{
	                	if(user_messages.hasOwnProperty(e["friendship_id"]) == false){
	                		user_messages[e["friendship_id"]] = []
	                	}
	                	user_messages[e["friendship_id"]].push(e);
	                });
	                if(data == "[]"){
	                	user_messages[a] = []
	                }
	            }
	        });
	    });

	}
</script>
<?php
$result = get_all_lines_db($db, "SELECT id FROM friend_requests AS f  WHERE ( f.from_id = '" . $_SESSION["user_id"] . "' OR f.to_id = '" . $msg_id . "' ) AND accepted = 1");
foreach ($result as $discution) {
	echo ("\n");

	echo "<script>chats.push('" . $discution["id"] . "')</script>\n";
	echo "<script>getChats(`" . $discution["id"] . "`)</script>\n";
}

?>
<div id="discution_container"><div id="button_container"></div>

</div>
<script>
	function accept(a){
		document.getElementById	("accept_id").value=a
		document.getElementById	("submit_accept").click()
	}

	function deny(a){
		document.getElementById	("deny_id").value=a
		document.getElementById	("submit_deny").click()

	}
	let discution_id = 0;
	function show_only_discution(e){
		for(c of document.getElementsByClassName("conversation")){
        		if(c.id != e.target.id){
        				c.hidden = true;
        		}else{
        			c.hidden = false
        		}
        }
	}
	async function send_message(e){
		e.preventDefault()
		b = document.getElementsByClassName("conversation")
		to = b[e.target.id].children[0].id
		a = document.getElementById("bar_"+e.target.id).value
		console.log(a)

		d = null
		for(c of b ){
			if(!c.hidden){
				d = c.id
			}
		}
		a = encodeURI(a)
		let url = ("send_message.php?from=<?=$_SESSION['user_id']?>&to="+to+"&message="+a)
        console.log(url)

    	$.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                	console.log(data)
            }
        });
        while(document.getElementById("button_container").children.length != 0){
			k = document.getElementById("button_container").children
			for(e of k){
			    e.remove()
			}
        }

        while(document.getElementsByClassName("conversation").length != 0){
			for(e of document.getElementsByClassName("conversation")){
				e.remove()
			}
		}

	let user_messages = [];
		setTimeout(()=>{

			user_messages = [];
			for(e of chats){
				getChats(e)
			}
			show_messages()
		},1000)


	}
	let message_send_button;
	async function show_messages(){
		let disc_counter = 0
		let last_other_person = ""
		user_messages.forEach((k)=>{
            let b = document.createElement("div");
            b.classList.add("conversation")
            let button_discution = document.createElement("button");
            button_discution.classList.add("button_discution")
            let d = document.createElement("h1");
			b.appendChild(d)
        	d.classList.add("other_name")
    		k.forEach((x)=>{
    	  		let f = document.createElement("div");
                if(current_user_name == x.sender_name ){
                    f.classList.add("right_message")
                }else{

                    f.classList.add("left_message")
                }
                f.classList.add("message")
                d.innerHTML	= x.other_person_name
                d.id = x.other_person_id
                last_other_person = x.other_person_name
    	  		let g = document.createElement("a");
    	  		let h = document.createElement("a");
    	  		let i = document.createElement("a");
    	  		g.innerHTML = x.content
    	  		if(x.attachements) h.innerHTML = "</br> PJ : " + x.attachements;
    	  		i.innerHTML = "</br> Lu :" + (x.read ? "oui" : "non")
    	  		f.appendChild(g)
    	  		f.appendChild(h)
    	  		f.appendChild(i)

    			b.appendChild(f)
    		})
    		l = document.createElement("button");
    		l.value = last_other_person
    		l.innerHTML = last_other_person
    		b.id = disc_counter
    		send_message_bar = document.createElement("form")
    		send_message_bar.classList.add("send_messsage_form")
    		message_bar = document.createElement("input")
    		message_bar.id = "bar_"+disc_counter
    		message_bar.type="text"

    		message_send_button = document.createElement("input")
    		message_send_button.id = disc_counter
    		message_send_button.data = disc_counter
    		message_send_button.type="submit"
    		message_send_button.addEventListener("click",(e)=>{send_message(e)});

    		send_message_bar.appendChild(message_bar)
    		send_message_bar.appendChild(message_send_button)
    		b.appendChild(send_message_bar)

    		l.id = disc_counter
    		l.classList.add("button_change_disc")
    		l.addEventListener("click",(e)=>{show_only_discution(e)});
			document.getElementById("discution_container").appendChild(b)
			document.getElementById("button_container").appendChild(l)
			disc_counter++
        })
        let c = 0
        for(e of document.getElementsByClassName("conversation")){

        		if(c != 0){
        				e.hidden = true;
        		}c++
        }
	}
	setTimeout	(()=>show_messages(),1000)
</script>
