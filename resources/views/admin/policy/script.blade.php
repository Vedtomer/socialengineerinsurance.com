 <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup click handler for view details buttons
        const viewButtons = document.querySelectorAll('.view-details');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Set all the modal values from data attributes
                document.getElementById('modal-policy-no').textContent = this.dataset.policy;
                document.getElementById('modal-policy-type').textContent = this.dataset.type;
                document.getElementById('modal-customer-name').textContent = this.dataset
                    .customer;
                document.getElementById('modal-policy-date').textContent = this.dataset.date;
                document.getElementById('modal-net-amount').textContent = '₹' + formatNumber(
                    this.dataset.net);
                document.getElementById('modal-gst').textContent = '₹' + formatNumber(this
                    .dataset.gst);
                document.getElementById('modal-premium').textContent = '₹' + formatNumber(this
                    .dataset.premium);
                document.getElementById('modal-commission').textContent = this.dataset
                    .commission + '₹';
                document.getElementById('modal-agent').textContent = this.dataset.agent;
                document.getElementById('modal-company').textContent = this.dataset.company;
                document.getElementById('modal-payment').textContent = this.dataset.payment;
                document.getElementById('modal-discount').textContent = this.dataset.discount +
                    '%';
                document.getElementById('modal-payout').textContent = this.dataset.payout + '₹';

                // Update download link
                const downloadLink = document.getElementById('download-policy');
                downloadLink.href = '/storage/policies/' + this.dataset.policy + '.pdf';

                // Set appropriate policy type icon
                const policyType = this.dataset.type.toLowerCase();
                let iconClass = 'fa-car-burst';

                if (policyType.includes('health')) {
                    iconClass = 'fa-heart-pulse';
                } else if (policyType.includes('life')) {
                    iconClass = 'fa-user-shield';
                } else if (policyType.includes('home')) {
                    iconClass = 'fa-house-chimney';
                } else if (policyType.includes('travel')) {
                    iconClass = 'fa-plane';
                } else if (policyType.includes('business')) {
                    iconClass = 'fa-briefcase';
                }

                // Update the icon
                const typeIconElement = document.querySelector('.icon-box .fa-car-burst');
                if (typeIconElement) {
                    typeIconElement.className = 'fa-solid ' + iconClass + ' text-info';
                }
            });
        });

    });
    // Format numbers with commas
    function formatNumber(num) {
        return new Intl.NumberFormat('en-IN').format(num);
    }



    function policyDelete(id) {
        Swal.fire({
            title: "Please confirm to Delete",
            text: "Do you want to proceed?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Proceed",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                var token = '{{ csrf_token() }}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                $.post('/admin/policy-list/delete/' + id)
                    .done(function(response) {
                        location.reload();
                    })
                    .fail(function(error) {
                        console.error(error);
                        Swal.fire({
                            title: "Error",
                            text: "An error occurred while processing your request.",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 4000
                        });
                    });
            }
        });
    }
</script>

<style>
    /* Custom table styling */
    .custom-table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table thead th {
        border: none;
        font-weight: 600;
        color: #495057;
        padding: 12px 15px;
    }

    .custom-table tbody tr {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 8px;
        background: #fff;
    }

    .custom-table tbody tr:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .custom-table tbody td {
        border-top: none;
        border-bottom: none;
        padding: 15px;
        vertical-align: middle;
    }

    .custom-table tbody tr td:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .custom-table tbody tr td:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
</style>
<script>
    $(document).ready(function() {
        // Initialize Select2 with proper width
        $('.js-example-basic-single').select2({
            dropdownParent: $('#filterModal'),
            width: '100%'
        });

        // Fix for Select2 width issue
        $('#filterModal').on('shown.bs.modal', function() {
            $('.js-example-basic-single').select2({
                dropdownParent: $('#filterModal'),
                width: '100%'
            });
        });
    });
</script>
