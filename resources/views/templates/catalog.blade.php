<catalog xmlns="http://www.demandware.com/xml/impex/catalog/2006-10-31" catalog-id="{{$config['store_id']}}-Catalog">

    <header>
        <image-settings>
            <internal-location base-path="/images"/>
            <view-types>
                <view-type>large</view-type>
                <view-type>medium</view-type>
                <view-type>small</view-type>
                <view-type>swatch</view-type>
            </view-types>
            <alt-pattern>${productname}</alt-pattern>
            <title-pattern>${productname}</title-pattern>
        </image-settings>
    </header>

<!-- START ROOT CATEGORY -->

@include('templates.inc.root-category')

<!-- END ROOT CATEGORY -->

<!-- START CATEGORIES -->

@foreach($categories as $category)
<category category-id="{!!$category['id']!!}">
   <display-name xml:lang="x-default"><![CDATA[{!!$category['name']!!}]]></display-name>
   <description xml:lang="x-default" />
   <online-flag>true</online-flag>
   <parent>{{$category['parent']}}</parent>
   <page-attributes />
   <custom-attributes>
      <custom-attribute attribute-id="showInMenu">true</custom-attribute>
   </custom-attributes>
</category>
@endforeach

<!-- END CATEGORIES -->


<!-- START PRODUCTS -->

@foreach($products as $product)
    <product product-id="{{$product['id']}}">
        <ean/>
        <upc/>
        <unit/>
        <min-order-quantity>1</min-order-quantity>
        <step-quantity>1</step-quantity>
        <display-name xml:lang="x-default">{!!$product['name']!!}</display-name>
        <short-description xml:lang="x-default">{{$product['short-description']}}</short-description>
        <long-description xml:lang="x-default">{{ isset($product['long-description']) ? $product['long-description'] : $product['short-description'] }}</long-description>
        <store-force-price-flag>false</store-force-price-flag>
        <store-non-inventory-flag>false</store-non-inventory-flag>
        <store-non-revenue-flag>false</store-non-revenue-flag>
        <store-non-discountable-flag>false</store-non-discountable-flag>
        <online-flag>true</online-flag>
        <available-flag>true</available-flag>
        <searchable-flag>true</searchable-flag>
        <images>
            <image-group view-type="small">
                @if(isset($product['picture']))
                <image path="{{$product['picture']}}"/>
                @endif
                @if(isset($product['picture2']))
                <image path="{{$product['picture2']}}"/>
                @endif
            </image-group>
            <image-group view-type="medium">
                @if(isset($product['picture']))
                <image path="{{$product['picture']}}"/>
                @endif
                @if(isset($product['picture2']))
                <image path="{{$product['picture2']}}"/>
                @endif
            </image-group>
            <image-group view-type="large">
                @if(isset($product['picture']))
                <image path="{{$product['picture']}}"/>
                @endif
                @if(isset($product['picture2']))
                <image path="{{$product['picture2']}}"/>
                @endif
            </image-group>
           
        </images>
        <brand>{{$product['brand']}}</brand>
        <manufacturer-name>{{$product['manufacturer']}}</manufacturer-name>
        <manufacturer-sku>{{$product['id']}}</manufacturer-sku>
        <page-attributes/>
        <classification-category>{{$product['category']}}</classification-category>
        <pinterest-enabled-flag>false</pinterest-enabled-flag>
        <facebook-enabled-flag>false</facebook-enabled-flag>
        <store-attributes>
            <force-price-flag>false</force-price-flag>
            <non-inventory-flag>false</non-inventory-flag>
            <non-revenue-flag>false</non-revenue-flag>
            <non-discountable-flag>false</non-discountable-flag>
        </store-attributes>
    </product>
@endforeach

<!-- END PRODUCTS -->

<!-- START PRODUCTS ASSIGNMENT -->

@foreach($products as $product)
   <category-assignment category-id="{{$product['category']}}" product-id="{{$product['id']}}">
		<primary-flag>true</primary-flag>
	</category-assignment>
@endforeach

<!-- END PRODUCTS ASSIGNMENT -->


</catalog>
