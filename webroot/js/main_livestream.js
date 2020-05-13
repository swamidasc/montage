$(document).ready(function(){
	
	var itime = 1;
    setInterval(function () {
        $("#stopWatch").html(sformat(itime));
        itime++;
    }, 1000);
	
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


	// reloaf iframe	
	$('body').on('click', '.refresh a', function(){
		$( '#live_iframe' ).attr( 'src', function ( i, val ) { return val; });;
	});
	
	
	// Close Profanity Click
	$('#overlayProfanityBG').click(function(e){
		closeProfanityChecker();
	});	
	
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
	
	
	$("#inPlaceSubmit").bind('click', function() {
		var confirms = $('#confirm').val().split(',');
		var confirmname = $('#confirmname').val().split(',');
		var selectedstats = $('#labels_'+$('#vidid').val()+' input:checked').val();
		
		if (confirms.indexOf(selectedstats) != -1) {
			
			// this is so stream ended doesn't have a title in the alert.
			if (selectedstats == 100) {
				var title = "";
			} else {
				var title = "<span style=\"color: #4248f4\">This will terminate the stream</style>";
			}
			
			$.confirm({
				title: title,
				content: '<p>You marked an item as: '+confirmname[confirms.indexOf(selectedstats)]+'</p><p>Are you sure you want to do this?</p>',
				boxWidth: '250px',
				buttons: {
					yes: function () {	
        				liveStreamModerate(selectedstats);
        			},
					no: function () {		
						$( "#inplacebutton" ).fadeOut( "fast", function() {
						$('#livestream1').trigger("reset");
						moderateImageLabel("#watching");	   		
						});
						$( "#memoButtonSpan" ).fadeOut( "fast", function() { 
  						$('.comments').removeClass('open');
	 	 				});  	
        			}
				}
			});
		} else {
			liveStreamModerate(selectedstats);						
		}
			$( "#memoButtonSpan" ).fadeIn( "fast", function() { 
  			$('.comments').addClass('open');
			});     	 		
		
	});	


	$("#memoButton").bind('click', function() {
		var tn = $('#tn').val();	
		var modid = $('#modid').val();
		var vidid = $('#vidid').val();
		memo = $('#memo_'+vidid).val(); 
		var modURL = $('#endpoint').val()+"?action=submitMemo&vidid="+vidid+"&modid="+modid;
		if (typeof memo !== 'undefined') {
	   		modURL += '&memo='+encodeURIComponent(memo);
    		}
		
		$.ajax({
			url: modURL,
			jsonp: "callback",
			dataType: "jsonp",
			success: function( response ) {
     	 		console.log( response ); // server response
	 	 		$( "#memoButtonSpan" ).fadeOut( "slow", function() { 
		 	 		$('.comments').removeClass('open');
	 	 		});  
	 	 		$('#memo_'+vidid).val(''); 	 		
			}
		});						
	});	



});


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



// * ============================================================================================================== * //
// *
// *     	VIDEOS WITH LABELS
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
			var checkBoxes = $('#labels_'+id+' input[type=checkbox]:checked').map(function() {
			    return this.value;
			}).get().join();
			$('#val_'+id).val(checkBoxes);
			
		}
	}
}




// * ============================================================================================================== * //
// *
// *     VIDEO FUNCTIONS
// *
// * ============================================================================================================== * //



// submt live stream statuses
//-------------------------------------------------------------------------- //
function liveStreamModerate(selectedstats) {
	var tn = $('#tn').val();	
	var modid = $('#modid').val();
	var vidid = $('#vidid').val();
	memo = $('#memo_'+vidid).val(); 
	var modURL = $('#endpoint').val()+"?action=mod&modid="+modid+"&tn="+tn+"&";
	//console.log(vidid+'='+selectedstats+'=memo='+memo);
	modURL += vidid+'='+selectedstats;
    if (typeof memo !== 'undefined') {
	   	modURL += '&memo_'+vidid+'='+encodeURIComponent(memo);
    }
	$.ajax({
    	url: modURL,
	    jsonp: "callback",
		dataType: "jsonp",
	    success: function( response ) {
        	console.log( response ); // server response
		$( "#inplacebutton" ).fadeOut( "slow", function() {
	   		$('#livestream1').trigger("reset");
	   		moderateImageLabel("#watching");	
	   		   		
	   		if (selectedstats == 105  || selectedstats == 100 || selectedstats == -2) {
	  			location.reload();
  			}
  		});
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

// for timer
function sformat( s ) {
    var fm = [
        Math.floor(Math.floor(Math.floor(s/60)/60)/24)%24, //DAYS
        Math.floor(Math.floor(s/60)/60)%60, //HOURS
        Math.floor(s/60)%60, //MINUTES
        s%60 //SECONDS
            ];
    return $.map(fm,function(v,i) { return ( (v < 10) ? '0' : '' ) + v; }).join( ':' );
}


// Window Load
//-------------------------------------------------------------------------- //

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
