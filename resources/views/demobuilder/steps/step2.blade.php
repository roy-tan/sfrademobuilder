<div class="step">
    <h3 class="main_question"><strong>2/6</strong>List of categories</h3>
    <div class="alert alert-info">
    Enter the list of categories pages to be analysed. <br><br>
    <strong>Hint:</strong> you may use the page breadcrumb to copy paste category name or parent name.
    </div>
    <div class="form-group add_top_30 category_item">
        <div class="row">
            <div class="col col-md-6">
                <label>* Category URL</label>
                <input required="required" type="text" name="category_url[]" class="form-control category_url" placeholder="https://mystore.com/category">
            </div>
            <div class="col col-md-3">
                <label>* Name
                <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter the category name">?</span>
                </label>
                <input required="required" type="text" name="category_name[]" class="form-control category_name" placeholder="Women dresses">
            </div>
            <div class="col col-md-3">
                <label>Parent name
                <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter the category parent name. Leave empty if it's a level 1 category.">?</span>
                </label>
                <input  type="text" name="category_parent_name[]" class="form-control category_parent_name" placeholder="Women">
            </div>
        </div>
    </div>

    <div class="form-group add_top_30 " id="category_add">
        <div class="row">
            <div class="col col-md-12 text-right">
            <a id="add-category" class="btn btn-info"><i class="fa fa-plus"></i> </a>
            </div>
        </div>
    </div>
</div>
<!-- /step-->