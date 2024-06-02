   <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
   {{-- <script src="{{ asset('asset/admin/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('asset/admin/js/perfect-scrollbar.min.js') }}"></script>
   <script src="{{ asset('asset/admin/js/mousetrap.min.js') }}"></script>
   <script src="{{ asset('asset/admin/js/waves.min.js') }}"></script>
   <script src="{{ asset('asset/admin/js/app.js') }}"></script>
   <!-- END GLOBAL MANDATORY SCRIPTS -->

   <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
   <script src="{{ asset('asset/admin/js/apexcharts.min.js') }}"></script>
   <script src="{{ asset('asset/admin/js/dash_1.js') }}"></script>
   <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS --> --}}


   <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
   <script src="{{ asset('asset/admin/src/plugins/src/global/vendors.min.js') }}"></script>
   <script src="{{ asset('asset/admin/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
   <script src="{{ asset('asset/admin/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
   <script src="{{ asset('asset/admin/src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
   <script src="{{ asset('asset/admin/src/plugins/src/waves/waves.min.js') }}"></script>
   <script src="{{ asset('asset/admin/layouts/modern-dark-menu/app.js') }}"></script>
   <!-- END GLOBAL MANDATORY SCRIPTS -->


   <!-- BEGIN PAGE LEVEL SCRIPTS -->
   <script src="{{ asset('asset/admin/plugins/src/table/datatable/datatables.js') }}"></script>
   <script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
   <script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/jszip.min.js') }}"></script>
   <script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
   <script src="{{ asset('asset/admin/plugins/src/table/datatable/button-ext/buttons.print.min.js') }}"></script>
   <script src="{{ asset('asset/admin/plugins/src/table/datatable/custom_miscellaneous.js') }}"></script>
   <!-- END PAGE LEVEL SCRIPTS -->


    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('asset/admin/src/plugins/src/apex/apexcharts.min.js') }}"></script>
    <script src="{{ asset('asset/admin/src/assets/js/dashboard/dash_1.js') }}"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <!-- Include Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
   @if(session('error'))
       toastr.error("{{ session('error') }}");
   @endif

   // New code for success message
   @if(session('success'))
       toastr.success("{{ session('success') }}");
   @endif

   // Initialize Toastr
   $(document).ready(function() {
       toastr.options = {
           "positionClass": "toast-top-right",
           "closeButton": true,
           "progressBar": true
       };
   });

</script>

<script>
   function copyCommissionCode(code) {
       // Create a textarea element
       var textarea = document.createElement('textarea');

       // Set the value of the textarea to the commission code
       textarea.value = code;

       // Append the textarea to the document body
       document.body.appendChild(textarea);

       // Select the content of the textarea
       textarea.select();

       // Copy the selected text to the clipboard
       document.execCommand('copy');

       // Remove the textarea from the document body
       document.body.removeChild(textarea);

       // Alert the user that the commission code has been copied
       toastr.success("Commission code copied: " + code);
     
   }

   function copyCompanyCode(code) {
       // Create a textarea element
       var textarea = document.createElement('textarea');

       // Set the value of the textarea to the commission code
       textarea.value = code;

       // Append the textarea to the document body
       document.body.appendChild(textarea);

       // Select the content of the textarea
       textarea.select();

       // Copy the selected text to the clipboard
       document.execCommand('copy');

       // Remove the textarea from the document body
       document.body.removeChild(textarea);

       // Alert the user that the commission code has been copied
       toastr.success("Company  code copied: " + code);
     
   }
</script>