<div id="wizard_container">
    <div id="top-wizard">
            <div id="progressbar"></div>
        </div>
        <!-- /top-wizard -->
        <form action="/build-demo" id="wrapped" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div id="middle-wizard">
                @include("demobuilder.steps.step1")
                @include("demobuilder.steps.step2")
                @include("demobuilder.steps.step3")
                @include("demobuilder.steps.step4")
                @include("demobuilder.steps.step5")
                @include("demobuilder.steps.step6")
        
            </div>
            <!-- /middle-wizard -->
            <div id="bottom-wizard">
                <button type="button" name="backward" class="backward">Prev</button>
                <button type="button" name="forward" class="forward">Next</button>
                <button type="submit" name="process" class="submit">Submit</button>
            </div>
            <!-- /bottom-wizard -->
        </form>
</div>
<!-- /Wizard container -->