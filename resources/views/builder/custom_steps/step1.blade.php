<div class="step">

    <h3 class="main_question"><strong>1/6</strong>Let's get started</h3>

    <div class="alert alert-info">
    Please enter information about the website you want to clone.
    </div>

    <div class="form-group add_top_30">
        <label>* Store name
        </label>
        <input required="required" type="text" name="store_name" class="form-control" placeholder="My store">
    </div>

    <div class="form-group add_top_30">
        <label>* Store url</label>
        <input required="required" type="text" name="store_root_url" class="form-control" placeholder="https://www.mystore.com">
    </div>

    <div class="form-group add_top_30">
        <label>* Store ID
        <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="ID of your store. Enter letters or numbers only.">?</span>
        </label>
        <input required="required" type="text" name="store_id" class="form-control" placeholder="MYSTORE">
    </div>

    <div class="form-group add_top_30">
        <label>* Default country
        <span  class="tooltip-icon" data-toggle="tooltip" data-placement="right" title="Choose the default locale and currency of your store.">?</span>
        </label>
        <select class="form-control required" name="store_locale">
            @foreach(config('demo.frontend_countries') as $locale=>$country)
            <option value="{{$locale}}">{{$country}} ({{$locale}} / {{(config('demo.locales'))[$locale]}})</option>
            @endforeach
        </select>
        </div>
</div>
<!-- /step-->
