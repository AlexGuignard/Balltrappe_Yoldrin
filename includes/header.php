<html>
<head>
</head>
<body>
	<nav>
		<div>
			<ul>
				<li onclick="clickchild(this)"><a href="/?target=home">Home</a></li>
				<li onclick="clickchild(this)"><a href="/?target=messages">Messages</a></li>
				<li onclick="clickchild(this)"><a href="/?target=records">Records</a></li>
				<li onclick="clickchild(this)"><a href="/?target=library">Librairie</a></li>
				<li onclick="clickchild(this)"  class="lastitem"><a  href="/?target=disconnect" ><img src="./images/power_off.avif"></a></li>
			</ul>
		</div>
	</nav>
	<style>
			nav>div>ul{
			    display: flex;
	    		flex-direction: row;
	    		background:gray;
			}
			nav>div>ul>li:hover{
    			transition:background .5s;
				cursor: pointer;
				background: #5d5b5b;
			}
			nav>div>ul>li{
				margin-left:1rem;
				list-style: none;
				padding:1rem;
				margin:.2rem;
				background: darkgrey;
			}
			nav>div>ul>li>a{
				font:status-bar;
				font-weight:bolder;
				font-size:smaller;
				color:black;
				text-decoration:none;
				text-emphasis: 	none;
			}
			nav>div>ul>li>a>img{
    			width: 30px;
			}
			.lastitem{
				aspect-ratio:1;
				padding:.5rem;
				position:absolute;
				left:93%;
			}
	</style>
	<script>
		function clickchild(a){
			a.children[0].click()

		}
	</script>