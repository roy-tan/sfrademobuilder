<!-- Modal terms -->
<div class="modal fade" id="check-selector" tabindex="-1" role="dialog" aria-labelledby="check-selector" aria-hidden="true">
<form id="form-checker" name="form-checker">	
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="search-link">Selector Checker</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label>* CSS selector</label>
				<span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter the selector you want to check">?</span>
				</label>
				<input zrequired="required" type="text" name="selector_string" id="selector_string" class="form-control" placeholder="">
			</div>
			<div class="form-group">
				<label>* Category or product page</label>
				<span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter the product or category page to be parsed">?</span>
				</label>
				<input zrequired="required" type="text" name="selector_page_url" id="selector_page_url" class="form-control" placeholder="">
			</div>
			<div id="results" style="min-height:130px" class="add_top_30 " >
				<div id="result-infos-correct" class="results-bloc">
					<span style="color:green;font-weight:bold">Correct!</span> <span id="results-count"></span> occurence(s) found. <span>Displaying first occurence.
				</div>
				<div id="result-infos-incorrect" class="results-bloc">
					<span style="color:red;font-weight:bold">Incorrect!</span> <span>No occurence found. Please try another selector.</span>
				</div>
				<div id="result-infos-error" class="results-bloc">
					<span style="color:red;font-weight:bold">An error has occured!</span> <span>This may be due to restrictions on the remote web server.</span>
				</div>
				<div class="add_top_20 results-bloc" id="result-infos-details">
					<i class="fa fa-arrow-right" style="vertical-align:super"></i> <span class="wrap-text strong" id="result-text"></span> <a style="vertical-align:super;color:#434bdf" id="result-link" href="#" target="_blank">(Test)</a>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-success" ><i class="fa fa-spinner fa-spin"></i>Check now</button>
		</div>
		<!-- /.modal-content -->
	</div>
</div>
</form>
</div>
<!-- /.modal -->