
function screensize() {
    
var wndow_ht = $( window ).height();
//$('body').css("min-height",wndow_ht);
 
var myPopPage_ht = $( ".myPopPage" ).height();
var top_cal = (wndow_ht - myPopPage_ht)/2;
$('.myPopPage').css("top",top_cal);

/*
if(wndow_ht<aboutBox_ht) {
	$('.myPopPage').css("top","10px");	
	$('.myPopPage').css("padding","0px 0px 10px 0px");	
}
*/

};

$( document ).ready(function() {
  screensize();	
});
$( window ).resize(function() {
  screensize();
});
$( window ).load(function() {
  screensize();
});

//popP1
function ExamOption(){
  $( "#mask" ).fadeIn();
  $( "#popP1" ).addClass( "open" );
}

function popp1close() {
$( "#popP1" ).removeClass( "open" );
$( "#mask" ).fadeOut();
};



function vdopopclose() {
  $( "#vdoPop" ).fadeOut();
};


function titlepopclose() {
  $( "#titlPop" ).fadeOut();
};

function openIntroPop(type){
  if(type=='title'){
    $( "#titlPop" ).fadeIn();
    popp1close();
    $( "#mask" ).fadeIn();
  }else if(type=='video'){
    $( "#vdoPop" ).fadeIn();
    popp1close();
    $( "#mask" ).fadeIn();
  }
}


//CLOSE
function allpclose() {
  $( "#mask" ).fadeOut();
  vdopopclose();
  popp1close();
  titlepopclose()
};
$( "#mask" ).click(function() {
  allpclose();
});
$( ".pcancel" ).click(function() {
  allpclose();
});