<div id="wizard_container">
    <div id="top-wizard">
            <div id="progressbar"></div>
        </div>
        <!-- /top-wizard -->
        <form action="/build-demo" id="wrapped" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div id="middle-wizard">
				@include("builder.custom_steps.step1")
                @include("builder.custom_steps.step2")
                @include("builder.custom_steps.step3")
                @include("builder.custom_steps.step4")
                @include("builder.custom_steps.step5")
                @include("builder.custom_steps.step6")
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
