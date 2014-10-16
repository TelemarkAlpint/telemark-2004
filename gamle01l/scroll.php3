<script language="javascript">
<!--

var y = 0;
var bolUp = "upp";
var bolDown;
var movePX = 1;
var moveDown = true;
var moveUp = false;
var ns = (navigator.appName == "Netscape");

function initPage() {
	x = getHeight("tekstfelt");
}
function scrollDown() {
movePX = 4;
skrollNed();
}

function scrollDown1() {
movePX = 1;
skrollNed();
}

function scrollDown2() {
movePX = 8;
skrollNed();
}

function scrollUp() {
movePX = 4;
skrollOpp();
}

function scrollUp1() {
movePX = 1;
skrollOpp();
}

function scrollUp2() {
movePX = 8;
skrollOpp();
}

function skrollNed() {
	if(moveDown) {
		if(ns) document.main.document.tekst.document.tekstfelt.top = y;
		else tekstfelt.style.pixelTop = y;
		
		if(y >=- (x - 200)) {
			moveUp = true;
			y -= movePX;
			//movePX += 0.05 ;
			bolDown = setTimeout("skrollNed()",25)
		} else {
			moveDown = false;
		}
	}
}

function skrollOpp() {
	if(moveUp) {
		if(ns) document.main.document.tekst.document.tekstfelt.top = y;
		else tekstfelt.style.pixelTop = y;
		
		if(y < 0) {
			moveDown = true;
			y += movePX;
			//movePX += 0.05 ;
			bolUp = setTimeout("skrollOpp()",25) 
		} else {
			moveUp = false;
		}
	}
}

function getHeight(lager) {
	if(ns) return document.main.document.tekst.document.layers[lager].clip.bottom;
	else return eval(lager).clientHeight;
}

function reset(this_) {
	clearTimeout(this_);
}

//-->
</SCRIPT>	
	
	
</head>

<body bgcolor="#989898" onLoad="initPage();"onResize="initPage();">
	