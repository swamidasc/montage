$(document).ready(function(){
	// Infield Labels
	$(".infield label").inFieldLabels();

	// Open Moderation Window
	$('td.mod .btn, .escl').on("click", function(e){
		window.open(this.href, $(this).attr('id'), "width=1600, height=900");
		e.preventDefault();
	});
	
	// Close Moderation Window 
	$('.close .ion-close-circled').click(function(e){
		// What is close()?
		close();
		e.preventDefault();
	});
	
	// Criteria
	$('body').on('click', '.info a', function(){
		openCriteria();
	});

	// Close Magnify Click
	$('#overlayBG').click(function(e){
		closeCriteria();
	});

	// Open Profanity Checker
	$('body').on('click', '.help a', function(){
		openProfanityChecker();
	});
	
	// Close Profanity Click
	$('#overlayProfanityBG').click(function(e){
		closeProfanityChecker();
	});	


	// ----------- IMAGE ----------------------- //
	// Moderate Image Click 
	$('body').on('click', '#mod a', function(){
		var id = $(this).data('imgid');
		moderateImage(id);
	});
		
	// Image Slider 
	if ($('#mod').length > 0) { 
		$('#mod').cycle({
			fx:     'scrollHorz', 
		    timeout: 0, 
		    prev:   '#back',
		    nowrap:  1,
		    before:   onBefore,
		    after:   onAfter,
		    onPrevNextEvent: slidecounter,
		    speed: 500,
		    speedIn: 250,
		    speedOut: 250
		});
	}
	
	$("#next").click(function(){ 
		if ($('#modVid').length > 0) { 
			var type = '#modVid';
		} else {
			var type = '#mod';
		}			
		var gonext = 1;


	//	var cookiename = "cookieconfirm_"+$('#projectid').val()
	
		if (typeof $('#confirm').val() !== 'undefined') {  
			var screens = $(type+' div.slide');								
			var confirms = $('#confirm').val().split(',');
			var confirmname = $('#confirmname').val().split(',');
			var remains = 0;
				
			screens.each(function(index,screen){				
				if ($('#lastSlide').length > 0 && index == 0) {
					//alert('skipping slide 1: '+index);
					return true;							
				} else if (screens.length == 3 && index != 1 && $('#lastSlide').length < 1) {
					//alert('skipping slide 2: '+index);
					return true;						
				} else if (screens.length <= 2 && index > 0 && index < screens.length  && $('#lastSlide').length < 1) {
					//alert('skipping slide 3: '+index);
					return true;
				} 
				
				var cslides = $(this).find(".status");	

				cslides.each(function(cslide){
					var imgStatus = $(this).val().split(',');
					imgStatus.forEach((status) => {	
						if (confirms.indexOf(status) != -1) {
							++remains;	
						}
					});
				});					
				
				
				
							
				cslides.each(function(cslide){
					
					var imgId = $(this).attr("name");
					var statusColor = $(this).attr("statusColor");
					var imgStatus = $(this).val().split(',');
					
					var needconfirm  = compare(confirms,imgStatus);
					var needlength = needconfirm.length;
								
					imgStatus.forEach((status) => {	
												
						if (confirms.indexOf(status) != -1) {
							gonext = 0;
							var alertBG = $('#link_'+imgId).css("background-color");
							$.confirm({
								title: '<span style="color: '+alertBG+'">You marked an item as: '+confirmname[confirms.indexOf(status)]+'</style>',
								content: '<p>Are you sure you want to do this?</p>',
								boxWidth: '250px',
								buttons: {
									yes: function () {	
										--remains;
										if (remains == 0) {
											$(type).cycle('next');
										}
        							},
									no: function () {		
										
        							}
								}
							});
						}	
					});
				});
			});	
		}

		if ($('#aim_nudity').val() == 1) {			
			var aimid;
			var aimimg;
			var aimimgs = [];
			var found = 0;

			$('input:hidden').each(function() {	
				var aimid = $(this).attr('id');
				if (aimid.match(/aim\_/g) && $(this).val() != 9999) {
					var aimimg = aimid.split('_');		
					if(!$.isNumeric(aimimg[1])) {
						return true;
					}
					if ($(this).val() == "") {
						$('#'+$(this).attr('id')).val(parseInt($('#slideid').val())+1);
					}		
					if ($('#aim_' + aimimg[1]).val() == $('#slideid').val()) {
						aimimgs.push(aimimg[1]);
					}
				}
			});
			
			for (var i = 0; i < aimimgs.length; i++) {				
				if ($('#val_'+aimimgs[i]).val() != 2) {
						found = 1;
				}
			}				
		
			if (found == 1) {
				alert('AIM has detected nudity on this page please review again.');	
				for (var i = 0; i < aimimgs.length; i++) {				
					if ($('#val_'+aimimgs[i]).val() != 2) {
						$('#aim_' + aimimgs[i]).val(9999);
					}
				}		
				gonext = 0;				
				return true;		
			}	
		} 
	
		
		if (gonext == 1) {
    		$(type).cycle('next');
    	}			
	});

	// Load Images
	var imgLoad = imagesLoaded('#mod');
	imgLoad.on( 'progress', function( instance, image ) {
		var result = image.isLoaded ? 'loaded' : 'broken';  
		if(result == 'broken'){
			if ($('#changed_'+image.img.id).val() != 1) {
				$('#changed_'+image.img.id).val(1);
			    moderateImage(image.img.id, 'escalate');
			}
			if($(type).hasClass('tags')) {
				setToDefault(image.img.id);
			}
		}
	});
	
	// Complete Loading Images
	imgLoad.on( 'always', function() {
		$('#loading').remove();
	});
	
	// Zoom
	if($(window).width() >= 460) {	
		$("#mod img.zoom").elevateZoom({
			zoomType: "lens",
			containLensZoom: true,
		});
	}

	// Magnify Click
    $(".magnify").fancybox({
	    type        : 'image',
        beforeLoad: function() {
            this.title = $(this.element).data('title');
        },
	    helpers : {
	        title: {
	            type: 'inside'
	        }
	    }
    });

	// Magnify Click
	$(".magnify.text").fancybox();

	
	// Excalated Text
	$('body').on('click', '.reasonBtn', function(){
		var id = $(this).data('reason');
		var imgID = $('#'+id.slice(8));
		//console.log(imgID);
		if($(id).hasClass('open')){
			$(id).slideUp().removeClass('open');
			// Enable Zoom
			imgID.elevateZoom({
				zoomType: "lens",
				containLensZoom: true,
			});
		} else {
			$(id).slideDown().addClass('open');
			// Disable Zoom	
			$('.zoomContainer').remove();
			imgID.removeData('elevateZoom');
			imgID.removeData('zoomImage');
		}
	});
	
	
	
	// Image With Labels (On Change Event)
	// ===================================================== //
	$('body').on('change', '.labels input', function(){
		var labelID = '#'+$(this).attr('id');
		moderateImageLabel(labelID);
    });
	

	
	// ----------- VIDEO ----------------------- //
	// Moderate Video Click
	$('body').on('click', '#modVid a', function(e){
        if($(e.target).is('a')){
			var id = $(this).data('imgid');
			moderateImage(id);		    
		    } else {
	            e.preventDefault();
	            return;
		    }

	});

	// Video Slider
	if ($('#modVid').length > 0) { 
		
		
		$('#modVid').cycle({
			fx:     'scrollHorz', 
		    timeout: 0, 
		    prev:   '#back',
		    nowrap:  1,
		    before:   videoOnBefore,
		    after:   videoOnAfter,
		    onPrevNextEvent: slidecounter
		    
		});
	}
	
	//Load Videos & Play First Video
	loadVideos();
	
	
	// ----------- Admin ----------------------- //
	// date picker
	$('#datetimepicker_start').datetimepicker({step:10});
	$('#datetimepicker_end').datetimepicker({step:10});
	
	// Add Status Row
	$('#addStatus').click(function(e){
		var rowCount = $('#statusTable tr').length;
        var newRow = '<tr id="status_'+rowCount+'"><td><input class="statname" type="text" name="statname_'+rowCount+'" required="required"/></td><td><input class="statval" type="text" name="statval_'+rowCount+'" required="required"/></td><td><input class="statcolor" type="text" name="statcolor_'+rowCount+'" required="required"/></td><td align="center"><input class="statcolor" type="checkbox" name="tag_'+rowCount+'" value="" /></td><td align="center"><input class="statval" type="radio" name="labeldefault" value="'+rowCount+'" /></td><td class="actions"><a href="javascript:void(0);" class="ion-close-circled removeStatus"></a></td></tr>';
		$('#statusTable tr:last').after(newRow);
	});
	
	// Remove Status Row
	$('body').on('click', '.removeStatus', function(){
		$(this).closest('tr').remove();
		reIndexRows();
	});
	
	
   // profanity filter search
	$('#profanitybutton').click(function(e){
		$('#profanityResults').hide().removeClass();
		$('.checkProfanity p span').removeClass();
		
		if($('#profanitytext').val() != ''){
			profanitycheck($('#projectid').val(),$('#profanitytext').val());
			$('.checkProfanity p span').addClass('checking');
		} else {
			$('#profanityResults').html('Please enter a word.');
			$('#profanityResults').slideDown();
		}
		return false;
	});	 
	
	
// Image Moderation QA Check
	$( "#imageCheck" ).submit(function( event ) {
	
		
	
		var imgurl = $('#imgurl').val().trim();
		var modid = $('#qcuserid').val().trim();
		var tn = $('#tn').val().trim();
		var projid = $('#projectid').val().trim();
		var results = $('#profanityResults');

		results.removeClass().html('').hide();

		if ( imgurl == ''){
			results.addClass('error').html('Please enter a valid image URL.').slideDown();
			return false;	
		} else {			
	    	checkImage(imgurl,tn,modid,projid);
		}
		return false;			
	});
	
	$('#tryAgain').click(function(){
		$('#imageQAResults').fadeOut();
		$('#imgurl').val("");
		$('[id^=imageStatus]').css('display', 'none');
		$('.checkQAImage').animate({height: '46px'}, 500, function() {
			$('#imageCheck').fadeIn();
			$('.checkQAImage').height('auto');
	 	});
	});	
	
	// Moderation Mobile Menu
	$("#menu").bind('click', function() {
		if($('body').hasClass('openMenu')){
			$('body').removeClass('openMenu');
		} else {
			$('body').addClass('openMenu');
		}
		return false
	});	
});





// * ============================================================================================================== * //
// *
// *	IMAGE FUNCTIONS
// *
// * ============================================================================================================== * //

	

// Slider Functions
//-------------------------------------------------------------------------- //

function slidecounter (isNext, zeroBasedSlideIndex, slideElement) {
	if (isNext) {
		$('#slideid').val(parseInt($('#slideid').val()) + 1);  
	} else {
		$('#slideid').val(parseInt($('#slideid').val()) - 1); 
	}
}


function onBefore(curr, next, opts, fwd){	
	if (!opts.removeSlide) {return;}
}

function onAfter(curr, next, opts, fwd) {
	if (!opts.removeSlide) {return;}

	// Restart Zoom
	if($(window).width() >= 460) {		
		restartZoom();
	}

	if((opts.nextSlide == opts.startingSlide) && (fwd == 1)){	
		// Out of Images
		if ($('#lastSlide').length > 0) { 
			// Remove Controls
			//console.log('Submit Remaining Slides Remove Buttons');
			submitModerations(opts, 'all');
			$( "#next, #back" ).fadeOut();
			
			// start to reload to check for more images
			setTimeout(function(){
				$.cookie("reload", "1");
				location.reload();
			}, 5000);
			
		} else {
			//console.log('Add Slide');		
			opts.addSlide('<div class="slide">Placeholder Slide</div>');
			loadNewImages();				
		}
	}

	if((opts.currSlide == 2) && (fwd == 1)){
		if ($('#lastSlide').length == 0) { 
		//	console.log('Once Slide Submit');
			submitModerations(opts);
		}
	}
}


// Load New Images
//-------------------------------------------------------------------------- //
function loadNewImages(){
	
	// Hide Next Button
	$( "#next" ).fadeOut();
	// Get New Images
	var pathArray = window.location.pathname.split( '/' );
		
		// get pending images
		var modScreen = pathArray[3];
		if (pathArray[4]) {
			// get escalated images
			modScreen = modScreen + '/' + pathArray[4]
		}

	$('.slide').last().load('/projects/nextmoderate/'+modScreen+' .next', function(response, status, xhr){

		//console.log('Loading New Images...');
		
		// Error Message
		if ( status == "error" ) {
			alert(status+' There was a problem loading new Images')
		}		
		
		// Load Images
		var imgLoad = imagesLoaded('.next');
		
		// Detect Broken Images
		imgLoad.on( 'progress', function( instance, image ) {
			var result = image.isLoaded ? 'loaded' : 'broken';  
			if(result == 'broken'){
				if ($('#changed_'+image.img.id).val() != 1) {
				    $('#changed_'+image.img.id).val(1);
					moderateImage(image.img.id, 'escalate');
				}
			}
		});
		
		// Complete Loading Images
		imgLoad.on( 'always', function() {			
			$( "#next" ).fadeIn();
			$('.next').removeClass('next');
			//console.log('Images loaded');
		});

	});
}


// Restart Zoom
//-------------------------------------------------------------------------- //

function restartZoom(){
	var img = $("#mod img.zoom");
		
	//Remove
	$('.zoomContainer').remove();
	img.removeData('elevateZoom');
	img.removeData('zoomImage');
	
	//Re-create
	img.elevateZoom({
		zoomType: "lens",
		containLensZoom: true,
	});
}

function removeZoomMobile(){
	var img = $("#mod img.zoom");
	$('.zoomContainer').remove();
	img.removeData('elevateZoom');
	img.removeData('zoomImage');	
}



// Submit Moderations from Starting Slide.
//-------------------------------------------------------------------------- //
function submitModerations(opts, id){
	
	// Submit
	var tn = $('#tn').val();
	var modid = $('#modid').val(); 
	
	var modURL = $('#endpoint').val()+"?action=mod&modid="+modid+"&tn="+tn+"&";

	if ($('#modVid').length > 0) { 
		var type = '#modVid';
	} else {
		var type = '#mod';
	}
	        
    if (id == 'all') {
	    var slides = $(type+' div.slide .status');
    } else {
	    var slides = $(type+' div.slide:first .status');
    }    
    
    slides.each(function(index){
       	var memo = '';
    	var imgId = $(this).attr("name");
    	var imgStatus = $(this).val();
    	
    	memo = $('#memo_'+imgId).val(); 
  		//console.log(imgId+'='+imgStatus+'=memo='+memo);
		modURL += '&'+imgId+'='+imgStatus;
    	if (typeof memo !== 'undefined') {
	    	modURL += '&memo_'+imgId+'='+encodeURIComponent(memo);
    	}

    tstamp = $('#time_'+imgId).val(); 
    	if (typeof tstamp !== 'undefined') {
	    	modURL += '&stamp_'+imgId+'='+tstamp;
    	}



	});

	//console.log(modURL);

	$.ajax({
    	url: modURL,
	    jsonp: "callback",
		dataType: "jsonp",
	    success: function( response ) {
        	//console.log( response ); // server response
		}
	});

	// Remove Slide
	//console.log('Then Remove Slide');
	opts.removeSlide();
}



// * ============================================================================================================== * //
// *
// *     	IMAGE WITH LABELS
// *			(Tagging)
// *
// * ============================================================================================================== * //

function moderateImageLabel(labelID){		

	
	var id = $(labelID).data('imgid'),
		color = $(labelID).data('color'),
		type = $(labelID).attr('type'),
		statusVal = $(labelID).attr('value');		
	
	// Hide BG on other Labels 
	$('#labels_'+id+' label').css({'background-color': 'transparent'});

	// Nothing Selected // Set to Default
	if ($('#labels_'+id+' input[type=checkbox]:checked').length == 0 && $('#labels_'+id+' input[type=radio]:checked').length == 0) {

		setToDefault(id);

	// Selection Made
	} else {
		
		// Radio Button
		if(type == 'radio') {
			// Deselect Checkboxes Buttons
			$('#labels_'+id+' input:checkbox').attr('checked', false);
			
			// Change BG Color
			$(labelID).next("label").css({'background-color': color});
			$('#link_'+id).css({'background-color': color });
			
			// Change Status
			$('#val_'+id).val(statusVal);
		
		// CheckBoxes	
		} else {
			
			// Deselect Radio Buttons
			$('#labels_'+id+' input:radio').attr('checked', false);
	
			// Change BG Color
			$('#link_'+id).css({'background-color': '#aa0000' });
	
			// Change Status
			var timestamps = [];		
			var checkBoxes = $('#labels_'+id+' input[type=checkbox]:checked').map(function() {	
				timestamps.push($('#'+this.id).attr( "data-tmstmp"));
			    return this.value;
			}).get().join();

			$('#time_'+id).val(timestamps.join(","));
			$('#val_'+id).val(checkBoxes);
		}
	}
}


// Set to Default (Test for Broken Images)
function setToDefault(id){				
	if ($('#changed_'+id).val() == 1) {
		$('#broken_'+id).prop('checked', true).trigger('change');
	} else {
		$('#labels_'+id+' input[data-default="true"]').prop('checked', true).trigger('change');	
	}		
}




// * ============================================================================================================== * //
// *
// *     VIDEO FUNCTIONS
// *
// * ============================================================================================================== * //


// Video
//-------------------------------------------------------------------------- //
function videoOnBefore(curr, next, opts, fwd){
	if (!opts.removeSlide) {return;}		

	// Current Player
    //var currentPlayer = $(curr).find('.jwplayer').attr('id');
	//jwplayer(currentPlayer).stop();

	var currentPlayer = $(curr).find('video').attr('id');
	$('#'+currentPlayer).get(0).load();
	
	//console.log(currentPlayer);

	if((opts.nextSlide == opts.startingSlide) && (fwd == 1)){	
		//console.log('here');
	} else {
		//var nextPlayer = $(next).find('.jwplayer').attr('id');
		//jwplayer(nextPlayer).play();
		//var nextPlayer = $(next).find('video').attr('id');
		//$('#'+nextPlayer).get(0).play();
	}

}

function videoOnAfter(curr, next, opts, fwd) {
	if (!opts.removeSlide) {return;}


	if((opts.nextSlide == opts.startingSlide) && (fwd == 1)){	
		// Out of Videos
		if ($('#lastSlide').length > 0) { 
			// Remove Controls
			//console.log('Submit Remaining Slides Remove Buttons');
			submitModerations(opts, 'all');
			$( "#next, #back" ).fadeOut();
			
			// start to reload to check for more images
			setTimeout(function(){
				$.cookie("reload", "1");
				location.reload();
			}, 5000);
			
		} else {
		//	console.log('Add Slide');
			opts.addSlide('<div class="slide">Placeholder Slide</div>');
			loadNewVideos();
			var nextPlayer = $(next).find('video').attr('id');
			$('#'+nextPlayer).get(0).play();			
		}
	}

	if((opts.currSlide == 2) && (fwd == 1)){
		if ($('#lastSlide').length == 0) { 
		//	console.log('One Slide Submit');
				submitModerations(opts);
		}
	}
}



// Load New Videos
//-------------------------------------------------------------------------- //
function loadNewVideos(){
	
	// Hide Next Button
	$( "#next" ).fadeOut();

	// Get New Images
	var pathArray = window.location.pathname.split( '/' );
		
		// get pending videos
		var modScreen = pathArray[3];
		if (pathArray[4]) {
			// get escalated videos
			modScreen = modScreen + '/' + pathArray[4]
		}

	$('.slide').last().load('/projects/nextmoderate/'+modScreen+' .next', function(response, status, xhr){
	//	console.log('Loading New Videos...');
		loadVideos();
		$( "#next" ).fadeIn();

	});

	$('video').bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
    	var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
		var event = state ? 'FullscreenOn' : 'FullscreenOff';

		if(event == 'FullscreenOn'){
			$(this).addClass('fullscreen');
    	}  else {
	    	$(this).removeClass('fullscreen');
    	}
	});
}



// Start Videos
//-------------------------------------------------------------------------- //
function loadVideos(){

	$('video').each(function() {
		if($(this).hasClass('firstVid')){
			var vidID = $(this).attr('id');
		//	console.log(vidID);
			$('#'+vidID).get(0).play();
			$(this).removeClass('firstVid');
		}
	});
}



// * ============================================================================================================== * //
// *
// * GENERAL & OTHER FUNCTIONS
// *
// * ============================================================================================================== * //


// ReIndex Status
//-------------------------------------------------------------------------- //
function reIndexRows(){
	var rowCount = 0;
	$("tr").each(function() {
		$(this).attr('id', "status_"+rowCount);
		$(this).find('.statname').attr('name', "statname_"+rowCount);
		$(this).find('.statval').attr('name', "statval_"+rowCount);
		rowCount++;
	});
}


// Criteria Overlay
//-------------------------------------------------------------------------- //

// Open Criteria
function openCriteria(){
	$('#overlayBG, #criteria').fadeIn();
}

// Close Criteria
function closeCriteria(){
	$('#overlayBG, #overlay, #criteria').fadeOut(function(){
		$('#overlay').html('');
	});
}


// Profanity Checker
//-------------------------------------------------------------------------- //

// Open Checker
function openProfanityChecker(){
	$('#overlayProfanityBG, .checkProfanity').fadeIn();
}

// Close Checker
function closeProfanityChecker(){
	$('#overlayProfanityBG, .checkProfanity').fadeOut();
}


// Image Moderation Q/A Check
// -------------------------------------------------------------------- //
function checkImage(imgurl,tn,modid,projid){
	
	$.ajax({
		type: "GET",
		url: "https://im-api2.webpurify.com/image_queue/montage/qcimgsubmit.php",
		contentType: "application/json",
		data: 'imgurl='+encodeURIComponent(imgurl)+'&tn='+tn+'&modid='+modid,
		dataType: "jsonp",
		success: function(data) {
		
			
			
			if (data.failed) {
				//alert('test'+data.failed);
				var results = $('#imageQAResults');
				results.addClass('error').html('Please enter a valid URL.').slideDown();				
			} else if (data.rsp.imgid) {
			
				$("#QAloadImage").html('<img width="200" src=' + imgurl + '>');
			
			
				$("#QAReportLink").attr("href", "/projects/review/"+projid+"?imgid="+data.rsp.imgid+"&search=1&cachevalue=0ead218fc3fc973a16e887572457b475");
				
				$('#imageCheck').fadeOut();
				var h = $('#imageQAProcessing').height();
				$('.checkQAImage').animate({height: h}, 500, function() {
	    			$('#imageQAProcessing').fadeIn();
				});			
				getQAStatus(data.rsp.imgid,tn);
			} else {
				alert('problem with your image');
			}
		},
		error: function(data) {
			//console.log(data);
		}
	});
	return true;
}	


function getQAStatus(imgid,tn) {
	var getImageStatus = setInterval(function(){
		$.ajax({
	    	type: "GET",
			url: "https://im-api2.webpurify.com/image_queue/montage/qcimgstatus.php",
			contentType: "application/json",
			data: 'imgid='+imgid+'&tn='+tn,
			dataType: "jsonp",
			success: function(data) {
				if (data.rsp.status) {
					if (data.rsp.status != "0") {
						clearInterval(getImageStatus);
						imageQAResults(data.rsp.status);
					} 
				} else {
					alert('problem with the status');
				}
			},
			error: function(data) {
	        	//console.log(data);
			}
		}) 
	},1000);		
}


function imageQAResults(status){
	var results = $('#imageStatus');
	var todis = '#imageStatus' + status;	
	$(todis).css("display", "block");
	$('#imageQAProcessing').fadeOut();
	var h = $('#imageQAResults').innerHeight();
	$('.checkQAImage').animate({height: h}, 500, function() {
		$('#imageQAResults').fadeIn();
	});
}


// Window Load
//-------------------------------------------------------------------------- //

$(window).load(function(){
	$('body').removeClass('preload');	
});

// Hide Alerts 
setTimeout(function(){ 
	$('.message').slideUp();
}, 5000);

// profanity checker
function profanitycheck(id,text) {
		var profurl = '/projects/profanity/'+id+'/?text='+encodeURIComponent(text);	
		$.ajax({
    		url: profurl,
    		dataType: "json",
			success: function( response ) {
        		if (response.rsp.found == 1) {
					$('.checkProfanity p span').addClass('dirty');
					$('#profanityResults').html('Profanity Found.').addClass('dirty');
					$('#profanityResults').slideDown();
        		} else {
					$('.checkProfanity p span').addClass('clean');
					$('#profanityResults').html('No profanity found').addClass('clean');
					$('#profanityResults').slideDown();
        		}
			}
		});
}

$('video').bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
   	var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
	var event = state ? 'FullscreenOn' : 'FullscreenOff';

	if(event == 'FullscreenOn'){
		$(this).addClass('fullscreen');
   	}  else {
    	$(this).removeClass('fullscreen');
   	}
});


// Window Resize
//-------------------------------------------------------------------------- //
/*
$(window).resize(function(){
	if($(window).width() <= 460) {
		removeZoomMobile();	
	} else {
		restartZoom();
	}
});
*/


function compare(arr1,arr2) {
	const finalarray = [];
	
	arr1.forEach((e1)=>arr2.forEach((e2)=>
			{if(e1 === e2){
		finalarray.push(e1)
	}
	}
));
	return finalarray;
}
