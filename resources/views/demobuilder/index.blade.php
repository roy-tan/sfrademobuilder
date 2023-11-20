<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Salesforce Commerce Cloud Demo Builder">
	<meta name="author" content="SF">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo Builder : Salesforce Commerce Cloud </title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/menu.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
	<link href="/css/vendors.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="/css/custom.css" rel="stylesheet">

	<!-- MODERNIZR MENU -->
	<script src="/js/modernizr.js"></script>

</head>

<body>

	<div id="preloader">
		<div data-loader="circle-side"></div>
	</div><!-- /Preload -->

	<div id="loader_form">
		<div data-loader="circle-side-2"></div>
	</div><!-- /loader_form -->

	<div class="container-fluid full-height">
		<div class="row row-height">
			<div class="col-lg-6 content-left">
			@include("demobuilder.inc.image-panel")
			</div>
			<!-- /content-left -->

			<div class="col-lg-6 content-right" id="start">
				@if(!isset($is_success))
					@include("demobuilder.inc.form-bloc")
				@else
					@include("demobuilder.inc.success")
				@endif
			</div>
			<!-- /content-right-->
		</div>
		<!-- /row-->
	</div>
	<!-- /container-fluid -->

	<div class="cd-overlay-nav">
		<span></span>
	</div>
	<!-- /cd-overlay-nav -->

	<div class="cd-overlay-content">
		<span></span>
	</div>
	<!-- /cd-overlay-content -->

	@include("demobuilder.inc.modal-check-selector")
	@include("demobuilder.inc.modal-get-started")
	@include("demobuilder.inc.modal-load-data")

	<!-- COMMON SCRIPTS -->

	<script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/common_scripts.js"></script>
	<script src="/js/velocity.min.js"></script>
	<script src="/js/functions.js"></script>
	<script src="/js/file-validator.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

	<!-- Wizard script -->
	<script src="/js/demo_builder_func.js"></script>

	<!-- Custom script -->
	<script src="/js/custom.js"></script>

	<script type="text/javascript">

		//this code is used to prevent csrf attacks
		var csrfToken = $('[name="csrf_token"]').attr('content');
		setInterval(refreshToken, 10*60*1000); // 10 min

		function refreshToken(){
			$.get('refresh-csrf').done(function(data){
				csrfToken = data; // the new token
			});
		}

		setInterval(refreshToken, 10*60*1000); // 10 min

	</script>

</body>
</html>
