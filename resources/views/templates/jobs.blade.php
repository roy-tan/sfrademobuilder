<jobs xmlns="http://www.demandware.com/xml/impex/jobs/2015-07-01">
    <job job-id="{{$config['store_id']}}-1-build-storefront" priority="0">
        <description>Imports a demo web site generated with SFRA demo builder tool.</description>
        <parameters/>
        <flow>
            <context site-id="Sites"/>
            
            <step step-id="ImportSite" type="ImportSiteArchive" enforce-restart="false">
                <description/>
                <parameters>
                    <parameter name="ImportFile">{{$config['store_id']}}.zip</parameter>
                    <parameter name="ImportMode">merge</parameter>
                </parameters>
            </step>
        </flow>
      <rules/>
        <triggers>
            <run-once enabled="false">
                <date>2019-02-23Z</date>
                <time>05:00:00.000Z</time>
            </run-once>
        </triggers>
    </job>

    <job job-id="{{$config['store_id']}}-2-build-indexes" priority="0">
        <description/>
        <parameters/>
        <flow>
            <context site-id="{{$config['store_id']}}"/>
            <step step-id="Reindex" type="SearchReindex" enforce-restart="false">
                <description/>
                <parameters>
                    <parameter name="Product related search indexes">true</parameter>
                    <parameter name="Content search index">true</parameter>
                    <parameter name="Indexer Type">Full Index Rebuild</parameter>
                </parameters>
            </step>
        </flow>
        <rules/>
        <triggers>
            <run-once enabled="false">
                <date>2019-03-03Z</date>
                <time>17:33:46.000Z</time>
            </run-once>
        </triggers>
    </job>


</jobs>