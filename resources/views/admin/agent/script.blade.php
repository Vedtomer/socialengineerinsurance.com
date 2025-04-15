<script>
        $(document).ready(function() {
            // Initialize Select2 for agent search
            $('.select2-agent').select2({
                placeholder: "Search agent...",
                allowClear: true,
                minimumInputLength: 0 
            }).on('change', function() {
                $('#filterForm').submit();
            });

            // Initialize Select2 inside modal - Fixed to properly display agent names
            $('.select2-modal').select2({
                dropdownParent: $('#commissionModal'),
                placeholder: "Select agent...",
                width: '100%',
                allowClear: true,
                minimumInputLength: 0
            });

            // Update commission symbol based on type
            $('#commissionType').change(function() {
                const type = $(this).val();
                $('#commissionSymbol').text(type === 'fixed' ? '₹' : '%');
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Copy to clipboard functionality
            $('.copy-code').click(function() {
                const code = $(this).data('code');
                navigator.clipboard.writeText(code).then(() => {
                    toastr.success('Code copied to clipboard');
                });
            });

            // Group checkbox functionality
            $('.group-checkbox').click(function() {
                const group = $(this).closest('.commission-group');
                group.find('.commission-checkbox').prop('checked', this.checked);
                updateBulkActionButtons();
            });

            // Individual checkbox handling
            $(document).on('change', '.commission-checkbox', function() {
                updateBulkActionButtons();
                
                // Update group checkbox if all or none are checked
                const group = $(this).closest('.commission-group');
                const total = group.find('.commission-checkbox').length;
                const checked = group.find('.commission-checkbox:checked').length;
                
                group.find('.group-checkbox').prop('checked', total === checked && total > 0);
            });


            // Update bulk delete button state
            function updateBulkActionButtons() {
                const checkedCount = $('.commission-checkbox:checked').length;
                $('#deleteSelectedBtn').prop('disabled', checkedCount === 0)
                    .html(`<i class="fas fa-trash-alt me-1"></i> Delete Selected (${checkedCount})`);
            }

            // Initialize delete functionality
            $('.delete-commission').click(function() {
                const id = $(this).data('id');
                $('#deleteForm').attr('action', '{{ route("commission.delete", "") }}/' + id);
                $('#deleteModal').modal('show');
            });

            // Confirm single delete
            $('#confirmDelete').click(function() {
                $('#deleteForm').submit();
            });

            // Bulk delete functionality
            $('#deleteSelectedBtn').click(function() {
                const ids = $('.commission-checkbox:checked').map((i, el) => el.value).get();
                $('#deleteIds').val(ids.join(','));
                $('#multiDeleteModal').modal('show');
            });

            // Confirm bulk delete
            $('#confirmBulkDelete').click(function() {
                $('#bulkDeleteForm').submit();
            });

            // Save commission form
            $('#saveCommission').click(function() {
                $('#commissionForm').submit();
            });

            // Edit commission
            $('.edit-commission').click(function() {
                const id = $(this).data('id');
                const agentId = $(this).data('agent');
                const productId = $(this).data('product');
                const companyId = $(this).data('company');
                const commType = $(this).data('comm-type');
                const commValue = $(this).data('comm-value');
                const payment = $(this).data('payment');
                const gst = $(this).data('gst');
                const discount = $(this).data('discount');
                const payout = $(this).data('payout');
                const settlement = $(this).data('settlement');
                
                // Set form values
                $('#commissionId').val(id);
                $('#agentSelect').val(agentId).trigger('change');
                $('#productSelect').val(productId);
                $('#companySelect').val(companyId);
                $('#commissionType').val(commType).trigger('change');
                $('#commissionValue').val(commValue);
                $('#paymentType').val(payment);
                $('#gstValue').val(gst);
                $('#discountValue').val(discount);
                $('#payoutValue').val(payout);
                $('#commissionSettlement').prop('checked', settlement == 1);
                
                // Update modal title and button text
                $('#modalTitle').text('Edit Agent Code');
                $('#saveButtonText').text('Update');
                
                // Show modal
                $('#commissionModal').modal('show');
            });
            
            // Reset form when modal is closed
            $('#commissionModal').on('hidden.bs.modal', function() {
                $('#commissionForm')[0].reset();
                $('#commissionId').val('');
                $('#modalTitle').text('Add New Agent Code');
                $('#saveButtonText').text('Save');
                $('#agentSelect').val('').trigger('change');
                $('#commissionSettlement').prop('checked', false);
            });
            
            // Fix Select2 rendering in modal when shown
            $('#commissionModal').on('shown.bs.modal', function() {
                $('.select2-modal').select2({
                    dropdownParent: $('#commissionModal'),
                    placeholder: "Select agent...",
                    width: '100%',
                    allowClear: true
                });
            });
            
            // Show modal if there are validation errors
            @if($errors->any())
                $('#commissionModal').modal('show');
            @endif
        });
    </script>