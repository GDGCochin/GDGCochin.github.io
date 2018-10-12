$(window).bind("scroll resize load",scrollPositionUpdate);

$(document).ready(function(){

	$(".people-list.openable").click(function(){
		$photo = $(this).find(".pic img").attr("src");
		$name = $(this).find(".name").html();
		$content = $(this).find(".data-hidden").html();

		$(".profileOpener .pic img").attr("src",$photo);
		$(".profileOpener .title").html($name);
		$(".profileOpener .content-container").html($content);

		$(".profileOpener").fadeIn(300);
		
		return false;
	});
	$(".profileOpener .closex").click(function(){
		$(".profileOpener").fadeOut(300);
		return false;
	});

	$(document).on('click', '.navigation a[href^="#"]', function (event) {
	    event.preventDefault();

	    $("header .navigation li.selected").removeClass("selected");

	    $(this).closest("li").addClass("selected");

	    $('html, body').animate({scrollTop: Math.max(0, $($.attr(this, 'href')).offset().top - 64)}, 500);
	});


});
function scrollPositionUpdate(){
	var scrollPos = $(document).scrollTop();
	if(scrollPos > 150 ){$("header").addClass("scroll");}
	else{$("header").removeClass("scroll");}
	
    $('.navigation li a').each(function () {
        var href = $(this).attr("href");
        if(href == "#"){return;}

        var refElement = $(href);
        
        var realScrollPos = scrollPos + 64;

        if(refElement.length > 0){
        	console.log(refElement);
	        if (refElement.position().top <= realScrollPos && refElement.position().top + refElement.height() > realScrollPos) {
	            $('.navigation li').removeClass("selected");
	            $(this).closest("li").addClass("selected");
	        }
        	
        }
    });
}