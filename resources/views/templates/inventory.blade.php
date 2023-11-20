<inventory xmlns="http://www.demandware.com/xml/impex/inventory/2007-05-31">

	<inventory-list>
		<header list-id="{{$config['store_id']}}-inventory">
			 <default-instock>false</default-instock>
			<description>Inventory</description>
		</header>

		<records>

    @foreach($products as $product)
		<record product-id="{{$product['id']}}">
			<allocation>{{ rand(1, 1000) }}</allocation>
			<perpetual>false</perpetual>
			<preorder-backorder-handling>none</preorder-backorder-handling>
		</record>
    @endforeach

		</records>

	</inventory-list>
</inventory>
