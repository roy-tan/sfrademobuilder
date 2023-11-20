<category category-id="root">
   <display-name xml:lang="x-default"><![CDATA[{{$config['store_name']}} Catalog]]></display-name>
   <description xml:lang="x-default" />
   <online-flag>true</online-flag>
   <parent>root</parent>
   <page-attributes />
   <custom-attributes>
      <custom-attribute attribute-id="showInMenu">true</custom-attribute>
   </custom-attributes>
   <attribute-groups>
      <attribute-group group-id="storefrontAttributes">
         <display-name xml:lang="x-default">Storefront Attributes</display-name>
      </attribute-group>
   </attribute-groups>
   <refinement-definitions>
      <refinement-definition bucket-type="none" type="category">
         <display-name xml:lang="x-default">Category</display-name>
         <sort-mode>category-position</sort-mode>
         <cutoff-threshold>5</cutoff-threshold>
      </refinement-definition>
      
      <refinement-definition bucket-type="thresholds" type="price">
         <display-name xml:lang="x-default">Price</display-name>
         <sort-mode>value-name</sort-mode>
         <sort-direction>ascending</sort-direction>
         <cutoff-threshold>5</cutoff-threshold>
         <bucket-definitions>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$0 - $19.99</display-name>
               <threshold>20</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$20 - $49.99</display-name>
               <threshold>50</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$50 - $99.99</display-name>
               <threshold>100</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$100 - $499.99</display-name>
               <threshold>500</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$500 - $999.99</display-name>
               <threshold>1000</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$1000 - $1,499.99</display-name>
               <threshold>1500</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$1500 - $1,999.99</display-name>
               <threshold>2000</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$2000 - $2,499.99</display-name>
               <threshold>2500</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$2500 - $4,999.99</display-name>
               <threshold>5000</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$5000  - $9,999.99</display-name>
               <threshold>10000</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$10000 - $14,999.99</display-name>
               <threshold>15000</threshold>
            </price-bucket>
            <price-bucket currency="USD">
               <display-name xml:lang="x-default">$15,000 +</display-name>
               <threshold>100000</threshold>
            </price-bucket>
         </bucket-definitions>
      </refinement-definition>
   </refinement-definitions>
</category>
