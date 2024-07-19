<?php

namespace App\Admin\Extensions\Tools;

use OpenAdmin\Admin\Actions\BatchAction;

class BatchBarcode extends BatchAction
{
    public function __construct()
    {
    }

    function handle()  {
        
    }

    public function script()
    {
        return <<<EOT
        document.querySelector('.{$this->getElementClass()}').addEventListener("click", function(e) {
        
            let selectedIds = admin.grid.selected; // Assuming this is an array of selected IDs
        
            if (!selectedIds || selectedIds.length === 0) {
                admin.toastr.error('No items selected');
                return;
            }
        
            let url = '/admin/menus/barcodes/print';
            let data = { ids: selectedIds };
        
            admin.ajax.post(url, data, function(response) {
                console.log('Full response object:', response);

                let res;
                try {
                    // Ensure response is handled as JSON
                    res = response.data || response.responseJSON || JSON.parse(response.responseText);
                    console.log('Response data:', res);

                    if (response.status === 200) {
                        admin.toastr.success('Printed');

                        let newWindow = window.open('', '_blank');
                        newWindow.document.write(res.html);
                        newWindow.document.close();

                        // Wait for the new window to finish loading before printing
                        newWindow.onload = function() {
                            // Print the contents of the new window
                            newWindow.print();

                            // Close the new window (optional)
                            newWindow.close();
                        };
                    } else {
                        admin.toastr.error('Print failed');
                    }
                } catch (error) {
                    console.error('Failed to process server response:', error);
                    admin.toastr.error('Failed to process server response');
                }
            });
        });
EOT;
    }
}
