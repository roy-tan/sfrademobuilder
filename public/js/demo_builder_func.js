	/*  Wizard */
	jQuery(function ($) {
		"use strict";
		$("#wizard_container").wizard({
			stepsWrapper: "#wrapped",
			submit: ".submit",
			beforeSelect: function (event, state) {
				
				if (!state.isMovingForward)
					return true;
				var inputs = $(this).wizard('state').step.find(':input');
				return !inputs.length || !!inputs.valid();
			},
			afterForward: function( event, state ) { 
				switch (state.stepIndex) {
					case 2:
						buildCategories();
						break;
				
					default:
						break;
				}
			 }
			
		}).validate({
			errorPlacement: function (error, element) {
				if (element.is(':radio') || element.is(':checkbox')) {
					error.insertBefore(element.next());
				} else {
					error.insertAfter(element);
				}
			}
		});

		//  progress bar
		$("#progressbar").progressbar();
		$("#wizard_container").wizard({
			afterSelect: function (event, state) {
				$("#progressbar").progressbar("value", state.percentComplete);
				$("#location").text("(" + state.stepsComplete + "/" + state.stepsPossible + ")");
			}
		});

		// Validate select
		$('#wrapped').validate({
			ignore: [],
			rules: {
				select: {
					required: true
				},
				fileupload: {
					fileType: {
						types: ["jpg", "jpeg", "gif", "png", "pdf"]
					},
					maxFileSize: {
						"unit": "MB",
						"size": 10
					},
					minFileSize: {
						"unit": "KB",
						"size": "2"
					}
				},
				sandbox_username: {
					required: true,
					minlength: 2,
					maxlength: 42		
				}
				
			},

			messages: {
				sandbox_username: {
					remote:"Incorrect username or password"
				}
			}
			,
			errorPlacement: function (error, element) {
				if (element.is('select:hidden')) {
					error.insertAfter(element.next('.nice-select'));
				} else {
					error.insertAfter(element);
				}
			}
		});



	// Submit form
	$("button[name='process']").on('click', function (e) {
		e.preventDefault();
		var form = $("form#wrapped");
		var validator = form.validate();

		if(form.valid()){

			var input ={
				url : $("input[name='sandbox_base_url']").val(),
				username : $("input[name='sandbox_username']").val(),
				password : $("input[name='sandbox_password']").val(),
			  }

			  $.post( "/check-sandbox-credentials", input)
				  .done(function(data) {
					if(data.errors){
						alert('We were not able to verify your credentials. Please try again')
					}else{
					
						if(data.result){
							if(data.result==="KO"){
								var errors = { sandbox_username: "Incorrect username or password" };
								validator.showErrors(errors); 
							}else{
								exportConfiguration();
								$("#loader_form").fadeIn();
								form.submit();
							}
						}
					}
			});

		}
		
	});


});

