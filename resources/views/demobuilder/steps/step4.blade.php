<div class="step">
   <h3 class="main_question"><strong>4/6</strong>Products' configuration</h3>
   <div class="alert alert-info">
      Now let's extract product information from PDPs. Analyse any PDP and enter the right selectors below. Don't forget that you can always use the Selector Checker <i class="fa fa-magic"></i>.
   </div>

   <div class="form-group add_top_30">
   <p><strong>Mandatory information</strong></p>
   </div>

   <div class="form-group add_top_30">
      <label>* Product name selector
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input required="required" type="text" name="selector_product_name" class="form-control" placeholder=".product_name">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_name')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>


   <div class="form-group add_top_30">
      <label>* Product price selector
      <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Don't worry. If the selector retrieves the currency symbol, the builder will remove it later">?</span>
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input required="required" type="text" name="selector_product_primary_price" class="form-control" placeholder=".product_price">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_primary_price')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>

   <div class="form-group add_top_30">
      <label>* Product picture selector
        <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter the product picture selector. It should be an 'img' selector">?</span>
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input required="required" type="text" name="selector_product_picture" class="form-control" placeholder="img.product_picture">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_picture')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>

   <div class="form-group add_top_30">
      <label>* Product short description selector
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input required="required" type="text" name="selector_product_short_description" class="form-control" placeholder=".product_short_description">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_short_description')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>

   <div class="form-group add_top_30">
   <p><strong>Optional information</strong></p>
   </div>

   <div class="form-group add_top_30">
      <label>Product additional picture selector
        <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Enter an additional picture selector. It should be an 'img' selector">?</span>
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input type="text" name="selector_product_picture2" class="form-control" placeholder="img.product_picture">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_picture2')"  type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>


   <div class="form-group add_top_30">
      <label>Product long description selector
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input  type="text" name="selector_product_long_description" class="form-control" placeholder=".product_long_description">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_long_description')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>


   <div class="form-group add_top_30">
      <label>Product brand selector
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input type="text" name="selector_product_brand" class="form-control" placeholder=".product_brand">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_brand')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>

   <div class="form-group add_top_30">
      <label>Product manufacturer selector
      </label>
      <div class="row">
         <div class="col col-md-9">
            <input type="text" name="selector_product_manufacturer" class="form-control" placeholder=".product_manufacturer">
         </div>
         <div class="col col-md-3">
            <button onclick="javascript:checkSelector('selector_product_manufacturer')" type="button" class="btn btn-info open-checker"><i class="fa fa-magic"></i></button>
         </div>
         
      </div>
   </div>
</div>
<!-- /step-->