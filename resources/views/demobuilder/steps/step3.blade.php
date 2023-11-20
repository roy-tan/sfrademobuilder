
<div class="step">

    <h3 class="main_question"><strong>3/6</strong>Categories' configuration</h3>

    <div class="alert alert-info">
    Now, help the builder to extract PDP links on your categories pages. You will use CSS selectors here. Analyse any of the Category pages and enter the right selector.
    <br><br>
    <strong>Hint</strong>: always use the Selector Checker <i class="fa fa-magic"></i> to test your selector before moving on. 
    </div>

    <div class="form-group add_top_30">
        <label>* PDP link selector
        <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter the PDP links selector. It should be an 'a' selector">?</span>
        </label>
        <div class="row">
        <div class="col col-md-9">
            <input required="required" type="text" name="selector_pdp_url" class="form-control" placeholder=".product a">
        </div>
        <div class="col col-md-3">
        <button onclick="javascript:checkSelector('selector_pdp_url')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
        </div>
        </div>


        <div class="form-group add_top_30">
        <label>* Max products per category</label>
        <select class="form-control required" name="category_max_products_count">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                </select>
        </div>
    </div>

</div>
<!-- /step-->