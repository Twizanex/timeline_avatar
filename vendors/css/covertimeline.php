body {
	background: url(../images/bg.gif);
	min-width: 860px;
}

p {
	padding: 0;
}

img {
	position: relative;
	margin : 0px;
	padding: 0px;
}

/***** MAIN CONTAINER *****/

.maincontainer {
	padding: 10px;
	width: 860px;
	margin-left: auto;
	margin-right: auto;
	
}

/***** END MAIN CONTAINER *****/



/***** TOP BANNER CONTAINER *****/


.topcontainer {
	width: 100%;
	height: 150px;
	
}

/***** END TOP BANNER CONTAINER *****/



/***** TIMELINE CONTAINER *****/

.timelinecontainer {
	position: relative;
	width: 100%;	
	min-height: 600px;
	margin-top: 10px;
}

.timeline {
	margin-left: 50%;
	height: 99%;
	width: 3px;
	background: #c3ccdf;	
	position: absolute;
}

.timeline:hover {
	cursor: pointer;
}

#timelineblock {
	position: relative
	padding: 0px;
	margin: 0px;
	margin-top: 30px;
	list-style-type: none;
	width:860px;		
	
}


.block {
	padding: 5px;
	margin: 0px;
	width: 340px;


	background: #fff;
	border: 1px solid #c4cde0;

	-moz-border-radius:3px; 
	border-radius:3px; 
	-webkit-border-radius:3px; 
}

.block:hover {
	border: 1px solid #9fafd0;
}
/***** END TIMELINE CONTAINER *****/



/***** ARROW EDGES *****/


#edge {
	position: absolute;
	padding: 0px;
}

.redge {
	display: block;
	margin:0px;
	margin-left: 345px;

	width:20px;
	height:15px;
	background: url(../images/right.gif);
}

.ledge {
	display: block;
	margin:0px;
	margin-left: -28px;

	width:23px;
	height:15px;
	background: url(../images/left.gif);
}

.redge_h {
	background: url(../images/right.gif) 21px;
}

.ledge_h {
	background: url(../images/left.gif) 23px;
}


/***** END ARROW EDGES  *****/


/*****  TIMELINE CONTENTS *****/


.block p {
	padding: 7px 0 7px 0;
	margin: 0px;
	font-size: 10px;
	font-family: Arial;

}


.pic {
	width: 310px;
	margin: 5px 0 0 10px;
	height: 210px;
	padding: 3px;
}

/***** END  TIMELINE CONTENTS *****/


.borders {
	border: 1px solid #000;
}

