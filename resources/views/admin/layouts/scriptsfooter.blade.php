
<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('app-assets/js/core/app-menu.js')}}"></script>
<script src="{{ asset('app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<script src="{{asset('js/admin/js_own/abcscriptv2.js')}}"></script>
<script src="{{asset('js/admin/js_own/functions.js')}}"></script>
<script src="{{asset('js/admin/js_own/validador.js')}}"></script>

  <script src="{{asset('js/jquery.creditCardValidator.js')}}"></script>



<!-- BEGIN: Page JS-->
<!-- END: Page JS-->


<!-- BEGIN: Page JS-->
<script src="{{ asset('app-assets/js/scripts/pages/auth-login.js')}}"></script>
<!-- END: Page JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/polyfill.min.js')}}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Page JS-->
<!-- END: Page JS-->

<!-- BEGIN: Page JS-->
   <script src="{{ asset('app-assets/js/scripts/ui/ui-feather.js')}}"></script>
   <!-- END: Page JS-->

   
  <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

<script>
    // $(window).on('load', function() {
    $(document).ready(function(){
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
@section('bottom-js')
@show


</html>
