<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
{META}
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}"  />
<link rel="stylesheet" href="../templates/BlackSoul/{T_HEAD_STYLESHEET}" type="text/css">
<style type="text/css">
<!--

/*
  The original subSilver Theme for phpBB version 2+
  Created by subBlue design
  http://www.subBlue.com

  NOTE: These CSS definitions are stored within the main page body so that you can use the phpBB2
  theme administration centre. When you have finalised your style you could cut the final CSS code
  and place it in an external file, deleting this section to save bandwidth.
*/
 
 /* General page style. The scroll bar colours only visible in IE5.5+ */
body {
	background-color: #000000;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;
}
 /* General font families for common tags */
font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
p, td		{ font-size : {T_FONTSIZE2}; color : #ffffff; }
a:link,a:active,a:visited { color : #2AAAA8; }
a:hover		{ text-decoration: underline; color : #2AAAA8; }
hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}
h1,h2		{ font-family: "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; font-size : 22px; font-weight : bold; text-decoration : none; line-height : 120%; color : #000000;}

/* This is the border line & background colour round the entire page */
.bodyline	{ background-color: #000000; border: 1px #000000 solid; }

/* This is the outline round the main forum tables */
.forumline	{ background-color: #000000; border: 2px #000000 solid; }


/* Main table cell colours and backgrounds */
td.row1	{ background-color: #2F2F4F; }
td.row2	{ background-color: #2F2F4F; }
td.row3	{ background-color: #2F2F4F; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
		background-color: #006699;
		background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th	{
	color: #FFFFFF; font-size: 11px; font-weight : bold;
	background-color: #113274; height: 25px;
	
}

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
			background-color:#006699; border: #000000; border-style: solid; height: 28px;
}


/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: #000000; border-style: solid; height: 28px; }
td.row3Right,td.spaceRow {
	background-color: #113274; border: #000000; border-style: solid; }

th.thHead,td.catHead { font-size: 12px; border-width: 0px 0px 0px 0px; }
th.thSides,td.catSides,td.spaceRow	 { border-width: 0px 0px 0px 0px; }
th.thRight,td.catRight,td.row3Right	 { border-width: 0px 0px 0px 0px; }
th.thLeft,td.catLeft	  { border-width: 0px 0px 0px 0px; }
th.thBottom,td.catBottom  { border-width: 0px 0px 0px 0px; }
th.thTop	 { border-width: 0px 0px 0px 0px; }
th.thCornerL { border-width: 0px 0px 0px 0px; }
th.thCornerR { border-width: 0px 0px 0px 0px; }


/* The largest text used in the index page title and toptic title etc. */
.maintitle,h1,h2	{
			font-weight: none; font-size: 18px; font-family: "Verdana",Verdana, Arial, Helvetica, sans-serif;
			text-decoration: none; line-height : 120%; color : #5D7790;
}


/* General text */
.gen { font-size : 12px; }
.genmed { font-size : 11px; }
.gensmall { font-size : 10px; }
.gen,.genmed,.gensmall { color : #FFFFFF; }
a.gen,a.genmed,a.gensmall { color: #006699; text-decoration: none; }
a.gen:visited,a.genmed:visited,a.gensmall:visited { color: #006699; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #006699; text-decoration: underline; }



/* The register, login, search etc links at the top of the page */
.mainmenu		{ font-size : 11px; color : #000000 }
a.mainmenu		{ text-decoration: none; color : #006699;  }
a.mainmenu:hover{ text-decoration: underline; color : #DD6900; }


/* Forum category titles */
.cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #00FF00}
a.cattitle		{ text-decoration: none; color : #00FF00; }
a.cattitle:visited 	{ text-decoration: none; color : #00FF00; }
a.cattitle:hover	{ text-decoration: underline; color : #00FF00; }

/* Forum title: Text and link to the forums used in: index.php */
.forumlink		{ font-weight: bold; font-size: 12px; color : #2AAAA8; }
a.forumlink 	{ text-decoration: none; color : #2AAAA8; }
a.forumlink:visited 	{ text-decoration: none; color : #2AAAA8; }
a.forumlink:hover{ text-decoration: underline; color : #2AAAA8; }


/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav			{ font-weight: bold; font-size: 11px; color : #2AAAA8;}
a.nav			{ text-decoration: none; color : #2AAAA8; }
a.nav:hover		{ text-decoration: underline; color : #2AAAA8;}



/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name			{ font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT};}

/* Location, number of posts, post date etc */
.postdetails		{ font-size : {T_FONTSIZE1}px; color : {T_BODY_TEXT}; }


/* The content of the posts (body of text) */
.postbody { font-size : {T_FONTSIZE3}px; line-height: 18px}
a.postlink:link	{ text-decoration: none; color : {T_BODY_LINK} }
a.postlink:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.postlink:hover { text-decoration: underline; color : {T_BODY_HLINK}}


/* Quote & Code blocks */
.code { 
	font-family: {T_FONTFACE3}; font-size: {T_FONTSIZE2}px; color: {T_FONTCOLOR2};
	background-color: {T_TD_COLOR1}; border: {T_TR_COLOR3}; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
	font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_FONTCOLOR1}; line-height: 125%;
	background-color: {T_TD_COLOR1}; border: {T_TR_COLOR3}; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}


/* Copyright and bottom info */
.copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #FFFFFF; letter-spacing: -1px;}
a.copyright		{ color: #444444; text-decoration: none;}
a.copyright:hover { color: #2AAAA8; text-decoration: underline;}

/* Form elements */
input,textarea, select {
	color : {T_BODY_TEXT};
	font: normal {T_FONTSIZE2}px {T_FONTFACE1};
	border-color : {T_BODY_TEXT};
}

/* The text input fields background colour */
input.post, textarea.post, select {
	background-color : {T_TD_COLOR2};
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
	background-color : {T_TR_COLOR1};
	color : {T_BODY_TEXT};
	font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
}

/* The main submit button option */
input.mainoption {
	background-color : {T_TD_COLOR1};
	font-weight : bold;
}

/* None-bold submit button */
input.liteoption {
	background-color : {T_TD_COLOR1};
	font-weight : normal;
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: {T_TR_COLOR2}; border-style: none; }


/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("../templates/BlackSoul/formIE.css"); 
-->
</style>
<title>{SITENAME} - {L_PHPBB_ADMIN}</title>
</head>
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">

<a name="top"></a>
