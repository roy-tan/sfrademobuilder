<div class="content-left-wrapper">
    <a href="/" id="logo"><img src="/img/logo.png" alt="" width="49" height="35"></a>
    <div id="social">
        <ul>
            <li><a href="mailto:{{config('demo.support_email')}}"><i class="icon-email"></i></a></li>
        </ul>
    </div>
    <!-- /social -->

    <style>
        a.btn_disabled,
        .btn_disabled {
          border: none;
          color: #fff;
          background: #434bdf;
          outline: none;
          cursor: pointer;
          display: inline-block;
          text-decoration: none;
          padding: 12px 25px;
          color: #fff;
          font-weight: 600;
          text-align: center;
          line-height: 1;
          -moz-transition: all 0.3s ease-in-out;
          -o-transition: all 0.3s ease-in-out;
          -webkit-transition: all 0.3s ease-in-out;
          -ms-transition: all 0.3s ease-in-out;
          transition: all 0.3s ease-in-out;
          -webkit-border-radius: 3px;
          -moz-border-radius: 3px;
          -ms-border-radius: 3px;
          border-radius: 3px;
          font-size: 14px;
          font-size: 0.875rem;
          margin: 25px 0 25px 0;
        }
        a.btn_disabled:hover,
        .btn_disabled:hover {
          background-color: #d80075;
        }

        a.btn_disabled.rounded,
        .btn_disabled.rounded {
          cursor: not-allowed;
          pointer-events: none;
          -webkit-border-radius: 25px !important;
          -moz-border-radius: 25px !important;
          -ms-border-radius: 25px !important;
          border-radius: 25px !important;
          -webkit-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.2);
          -moz-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.2);
          box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.2);
          margin: 25px 0 25px 0;
        }

    </style>

    <div>
        <figure><img src="/img/demobuilder.png" alt="" class="img-fluid home-image"></figure>
        <h2>Commerce Cloud Demo Builder</h2>
        <p>Welcome to the Commerce Cloud Demo Builder. This tool will help you to create & deploy the right demonstration to your Sandbox.
            </p>
        <p>Select the demo you would like to create</p>
        <button class="btn_1 rounded" onclick="window.location.href='/sfra';">Work in Progress: SFRA to your sandbox! (Do not use yet)</button><br>
        <button class="btn_1 rounded" onclick="window.location.href='/customdemo';">I would like to create a Custom Demonstration</button><br>
        <button class="btn_disabled rounded" onclick="window.location.href='/nto';">Coming soon: Deploy NTO to my Sandbox</button><br>

    </div>
    <div class="copy">© {{date('Y')}} Salesforce Commerce Cloud</div>

    <!-- Preload pictures -->
    <div style="display:none">
    <img class='homepage-preview' src='/img/home/home1/main-color.png' >
    <img class='homepage-preview' src='/img/home/home1/logo.png' >
    <img class='homepage-preview' src='/img/home/home1/top-title.png' >
    <img class='homepage-preview' src='/img/home/home1/top-picture.png' >
    <img class='homepage-preview' src='/img/home/home1/categories.png' >
    <img class='homepage-preview' src='/img/home/home1/bottom-title.png' >
    <img class='homepage-preview' src='/img/home/home1/bottom-picture.png' >
    </div>
    <!-- / Preload pictures -->
</div>
