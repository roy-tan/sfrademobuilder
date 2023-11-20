<pricebooks xmlns="http://www.demandware.com/xml/impex/pricebook/2006-10-31">

	<pricebook>
		<header pricebook-id="{{$config['store_id']}}-list-prices">
			<currency>{{$config['store_currency']}}</currency>
			<display-name xml:lang="x-default">{{$config['store_name']}} Prices</display-name>
			<description xml:lang="x-default">{{$config['store_name']}} Prices</description>
			<online-flag>true</online-flag>
		</header>

		<price-tables>
      @foreach($products as $product)
			<price-table product-id="{{$product['id']}}">
				<amount quantity="1">{{$product['price']}}</amount>
			</price-table>
      @endforeach
		</price-tables>
	</pricebook>
</pricebooks>
