var check_selector;
var open_check_selector;
var check_selector_url = "/check-selector";
var current_store_url;
var categories;
var buildCategories;
var loadHomeCategories;
var exportConfiguration;
var checkSelector;

$( document ).ready(function() {

  /**
   * CSRF for laravel
   */
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  /**
   * Configure form checker validator
   */
  $("#form-checker").validate({
    rules: {
      selector_page_url:{
         required: true,
         url:true
      },
      selector_string: {
         required: true
      }
     }
    });

  /**
   * Add tootip
   */
  $('[data-toggle="tooltip"]').tooltip();

  /**
   * Add a category
   */
  $( "#add-category" ).click(function() {
    $('.category_item').last().clone().insertBefore('#category_add');
    $('.category_item').last().find('input:text').val('');
    $('.category_item').last().addClass('new-category');
    $('[data-toggle="tooltip"]').tooltip();
  });
  

   /**
   * Open selector checker
   */
  checkSelector = function(input_name){
    $('#selector_string').val( $('.form-control[name='+ input_name+']').val() );
    $('.modal-footer .fa.fa-spin').hide(); 
    $('#check-selector').modal('show');    
    $('.results-bloc').hide(); 
  }

  /**
   * Validate checker form
   */

  
  $( "#form-checker" ).submit(function(e) {
    e.preventDefault();
    $('.results-bloc').hide(); 
      
    var input ={
      selector_string : $('#selector_string').val(),
      selector_page_url : $('#selector_page_url').val()
    }

    if(! $("#form-checker").valid())return;
    $('.modal-footer .fa.fa-spin').show(); 

    $.post( check_selector_url, input)
    .done(function( data ) {
      
      
      $('.results-bloc').hide();
      
      if(data.includes("error")){
        $('#result-infos-error').show();
      
      }else{

        if(data.length){
          $("#results-count").text(data.length);
          var firstElement = data[0];
          $("#result-text").text(firstElement.title);
          if(firstElement.url){
            var link = firstElement.url.replace(/&amp;/g, '&');
            $("#result-link").attr('href',link);
            $("#result-link").show()
          }else{
            $("#result-link").hide()
          }
          $('#result-infos-incorrect').hide();       
          $('#result-infos-correct').show(); 
          $('#result-infos-details').show(); 
        }else{
          $('#result-infos-incorrect').show();       
          $('#result-infos-correct').hide();  
        }
      }
      
      $('.modal-footer .fa.fa-spin').hide(); 

    })
    .fail(function() {
      $('.modal-footer .fa.fa-spin').hide(); 
    })
    .always(function() {
      $('.modal-footer .fa.fa-spin').hide(); 
    });;

  });

    /**
   * Validate load data
   */
  $( "#form-load-data .btn-success" ).click(function(e) {
    e.preventDefault();
    var lines = $('#demo-data').val().split('\n');
    $('.category_item.new-category').remove();
    var category_index = 1;
    for(var i = 0;i < lines.length;i++){
      var line = lines[i];  
      if(line.trim()!=''){
        var res = line.split("=");
        if(res.length==2){

          var inputName = res[0].trim();
          var inputValue = res[1].trim();

          if(inputName.startsWith('category_url') || inputName.startsWith('category_name') || inputName.startsWith('category_parent_name')){
            if(inputName.startsWith('category_url')){
               if(category_index>1){
                $( "#add-category" ).click();
               } 
               category_index++;
            }
            $('.category_item').last().find('.'+ inputName).val(inputValue);
          
          }else{
            $('.form-control[name='+ inputName+']').val(inputValue);
          }
        }
      }
    }
    $('#load-data').modal('hide'); 

  });

  exportConfiguration = function(){
   var txt = "";
   var categories_field_index = 0;
    $('.form-control').each(function( ) {
      var field;
      var value = $(this).val();
      var inputName = $(this).attr('name');
      if( inputName != 'selector_string' && inputName != 'selector_page_url' ){
        
        if(inputName.endsWith('[]')){
          var currentIndex = parseInt(categories_field_index / 3) + 1;
          field = inputName.substring(0, inputName.length - 2);
          categories_field_index ++;
        
        }else if(inputName=='sandbox_password'){
          value = "xxxxxxx";
          field = inputName;
        
        }else{
          field = inputName;
        }
        txt = txt + '\n' + field + ' = ' + value;
      }
      
    });
    $("#configuration_export").val(txt);
    console.log(txt)
    
  }

  loadHomeCategories = function(){
    var lines = $('#demo-data').val().split('\n');
    for(var i = 0;i < lines.length;i++){
      var line = lines[i];  
      if(line.trim()!=''){
        var res = line.split("=");
        if(res.length==2){
          var inputName = res[0].trim();
          var inputValue = res[1].trim();
          if(inputName.startsWith('home_category_title')){
            $('.form-control[name='+ inputName+']').val(inputValue);
          }
        }
      }
    }
  }

  
  buildCategories = function(){
    categories = $("input[name='category_name[]']")
              .map(function(){return $(this).val();}).get();
    
    $("select.category").empty();       
    for(var i=0; i< categories.length;i++){
      jQuery('<option/>', {
            value: categories[i],
            html: categories[i]
            }).appendTo('select.category'); //appends to select if parent div has id dropdown
    }
    loadHomeCategories();
    
  }

});