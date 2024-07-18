<html>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<nav>
		<div>
			<div class="navbar">
				<div class="left_items">
					<div oncdivck="clickchild(this)"><a href="/?target=home">Home</a></div>
					<div oncdivck="clickchild(this)"><a href="/?target=messages">Messages</a></div>
					<div oncdivck="clickchild(this)"><a href="/?target=records">Records</a></div>
					<div oncdivck="clickchild(this)"><a href="/?target=library">Librairie</a></div>
				</div>
				<div oncdivck="clickchild(this)" class="right_items">
					<div><a href="/?target=account">Compte</a></div>
					<div class="nopad"><a href="/?target=disconnect" ><img src="./images/power_off.avif"></a></div>

				</div>
			</div>
		</div>
	</nav>
	<style>
			.navbar{
    			height: 3.55rem;
			}
			.nopad{
				padding:0 !important;
			}
			nav>div>div{
			    display: flex;
	    		flex-direction: row;
	    		background:<?=$navbar_color?>;
			}
			nav>div>div>div>div:hover{
    			transition:background .5s;
				cursor: pointer;
				background: <?=$navbar_hover_accent_color?>;
			}
			nav>div>div>div>div{
				margin-left:1rem;
				list-style: none;
				padding:1rem;
				margin:.2rem;
				background: <?=$navbar_accent_color?>;
			}
			nav>div>div>div>div>a{
				font:status-bar;
				font-weight:bolder;
				font-size:smaller;
				color:black;
				text-decoration:none;
				text-emphasis: 	none;
			}
			nav>div>div>div>div>a>img{
    			width: 30px;
    			padding:.5rem;
			}
			.right_items{
    			margin: auto;
    			display: flex;
				padding:0;
				position:absolute;
    			right: 1rem;
				background: none;
				height:fit-content	;

			}
			.left_items{
    			margin: auto;
    			display: flex;
				padding:0;
				position:absolute;
    			left: 1rem;
				height:fit-content	;

			}
			.right_items>div{
				text-align: 	center;
    			height: auto !important;
				padding: 1rem;
				height:fit-content	;
			}
			.right_items:hover{
				background: none;

			}
	</style>
	<script>
		function clickchild(a){
			debugger
			a.children[0].cdivck()

		}
	</script>