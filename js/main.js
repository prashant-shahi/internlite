/*[ Back to top ]
===========================================================*/
var windowH = $(window).height()/2;

$(window).on('scroll',function(){
	if ($(this).scrollTop() > windowH) {
		$("#myBtn").css('display','flex');
	} else {
		$("#myBtn").css('display','none');
	}
});

$('#myBtn').on("click", function(){
	$('html, body').animate({scrollTop: 0}, 300);
});
/*=============================================================*/
$('.closebtn').on('click', function(){
	$(this).parent().slideUp("slow", function(){
		// $(this).remove();
	});
})
/*=============================================================*/
$('.mybtnredirect').on('click', function(event) {
	event.preventDefault(); 
	var url = $(this).data('target');
	location.assign(url);
});
$('.mybtninternapply').on('click', function(event) {
	event.preventDefault(); 
	var internid = $(this).data('internid');

    // AJAX Request
    $.ajax({
    	url: 'internapply-ajax.php',
    	method: 'post',
    	data: { internid: internid },
    	dataType: 'json',
    }).done(function(data) {
		// Update average
		if(data['error']) {
			if(data['error']=="no_session") {
				alert("Error: No Student Session Found !!\n\r\n\rSign in as student !!");
			}
			else {
				alert("Unexpected Error !!\n\r\n\rContact Admin or Try Again later !!");
			}
		}
		else if(data['success']){
			alert("Success: Internship\n\r\n\rSuccessfully applied for internship !!");
			location.reload(false);
		}
	}).fail(function() {
		alert("Unexpected AJAX Error !!\n\r\n\rContact Admin or Try Again later !!");
	});
});
$('.mybtninvalidapply').on('click', function(event) {
	event.preventDefault(); 
	var user = $(this).data('user');
	if(user=="student")
		alert("You have already applied for this internship.\n\rEmployer will contact you if you are shortlisted.\n\rHave Patience.");
	if(user=="employer")
		alert("Employers cannot apply for internships.\n\rYou can post internships and view list of applications received for them.");
});