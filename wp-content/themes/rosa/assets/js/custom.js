jQuery(document).ready(function(){
	// code to add overlay for single-blog-post
	jQuery(".single-blog-post").find(".article__parallax").append("<div class='header-overlay'></div>");
})

//contact-form-7
 var temp = jQuery("#sercice-neede").parent().html();
 var data = temp + "<label for='input' class='control-label'>Sercice neede</label><i class='bar'></i>";
 jQuery("#sercice-neede").parent().html(data);
 var temp2 = jQuery("#time").parent().html();
 var data2 = temp2 + "<label for='input' class='control-label'>Time</label><i class='bar'></i>";
 jQuery("#time").parent().html(data2);
 var temp3 = jQuery("#book-name").parent().html();
 var data3 = temp3 + "<label for='input' class='control-label'>Your name</label><i class='bar'></i>";
 jQuery("#book-name").parent().html(data3);
 var temp4 = jQuery("#book-number").parent().html();
 var data4 = temp4 + "<label for='input' class='control-label'>Your phone number</label><i class='bar'></i>";
 jQuery("#book-number").parent().html(data4);
 var temp5 = jQuery("#book-email").parent().html();
 var data5 = temp5 + "<label for='input' class='control-label'>Your e-mail</label><i class='bar'></i>";
 jQuery("#book-email").parent().html(data5);