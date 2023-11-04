<!-- BEGIN: Vendor JS-->
<script src="{{asset('assetsFront/app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('assetsFront/app-assets/vendors/js/ui/jquery.sticky.js')}}"></script>
<script src="{{asset('assetsFront/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('assetsFront/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assetsFront/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{asset('assetsFront/app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('assetsFront/app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{asset('assetsFront/app-assets/js/scripts/forms/form-validation.js')}}"></script>
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
